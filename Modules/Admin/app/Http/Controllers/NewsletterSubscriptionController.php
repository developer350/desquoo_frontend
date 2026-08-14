<?php

namespace Modules\Admin\Http\Controllers;

use App\Helpers\BackendHelpers;
use App\Http\Controllers\Controller;
use App\Models\EnquiryLastRead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\NewsletterSubscription;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use OpenSpout\Common\Entity\Style\Style;
use Rap2hpoutre\FastExcel\Facades\FastExcel;
use Yajra\DataTables\Facades\DataTables;

class NewsletterSubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $date_range = $request->date_range;

            $data = NewsletterSubscription::when(
                BackendHelpers::isOrderColumnZero($request),
                fn($query) => $query->orderByDesc('id')
            )
                ->when($date_range, fn($query) => $query->whereBetween(
                    'created_at',
                    collect(explode(" to ", $date_range))->map(fn($date) => Carbon::parse($date))
                ));
            return DataTables::eloquent($data)
                ->addIndexColumn()
                ->editColumn('created_at', function ($row) {
                    return $row->date_formatted;
                })
                ->addColumn('action', function ($row) {
                    return '<form action="' . route('newsletter-subscriptions.destroy', base64_encode($row->id))  . '" method="POST" style="display: inline-block;">' . csrf_field() . method_field('DELETE') . '<button type="button" class="btn btn-danger btn-sm delete-btn" data-delete-message-type="itemOnly" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Delete"><i class="fas fa-trash"></i></button></form>';
                })
                ->rawColumns(['action'])
                ->toJson();
        }

        EnquiryLastRead::updateOrCreate(
            ['admin_id' => Auth::guard('admin')->id()],
            ['newsletter_at' => now()]
        );

        return view('admin::newsletter-subscription.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $newsletterSubscription = NewsletterSubscription::findOrFail(base64_decode($id));
            $newsletterSubscription->delete();

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Newsletter Subscription deleted successfully.']);
        } catch (ModelNotFoundException $e) {
            DB::rollBack();

            return response()->json(['success' => false, 'message' => 'The requested Newsletter Subscription does not exist.']);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error($e);

            return response()->json(['success' => false, 'message' => 'Something went wrong.']);
        }
    }

    /**
     * Export the newsletter subscriptions to an Excel file using FastExcel.
     */
    public function export(Request $request)
    {
        $newsletterSubscriptions = NewsletterSubscription::query();

        if ($request->start_date && $request->end_date) {
            $startDate = Carbon::parse($request->start_date);
            $endDate = Carbon::parse($request->end_date);

            $newsletterSubscriptions->whereBetween('created_at', [$startDate, $endDate]);
        }

        $newsletterSubscriptions = $newsletterSubscriptions->get();

        if ($newsletterSubscriptions->isEmpty()) {
            return redirect()->back()->with('error', 'No enquiries found.');
        }

        $filename = 'newsletter-subscriptions-' . now()->format('Y-m-d-H-i') . '.xlsx';

        return FastExcel::data($newsletterSubscriptions->map(function ($newsletterSubscription, $index) {
            return [
                'SN' => $index + 1,
                'Email' => $newsletterSubscription->email,
                'Received At' => $newsletterSubscription->created_at->format('d M Y h:i A'),
            ];
        }))
            ->headerStyle((new Style())->setFontBold())
            ->rowsStyle((new Style())->setFontSize(12))
            ->download($filename);
    }
}
