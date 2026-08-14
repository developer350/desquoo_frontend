<?php

namespace Modules\Admin\Http\Controllers;

use App\Helpers\BackendHelpers;
use App\Http\Controllers\Controller;
use App\Models\EnquiryLastRead;
use App\Models\VisitEnquiry;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use OpenSpout\Common\Entity\Style\Style;
use Rap2hpoutre\FastExcel\Facades\FastExcel;
use Yajra\DataTables\Facades\DataTables;

class VisitEnquiryController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $date_range = $request->date_range;

            $data = VisitEnquiry::when(
                BackendHelpers::isOrderColumnZero($request),
                fn ($query) => $query->orderByDesc('id')
            )
                ->when($date_range, fn ($query) => $query->whereBetween(
                    'created_at',
                    collect(explode(' to ', $date_range))->map(fn ($date) => Carbon::parse($date))
                ));

            return DataTables::eloquent($data)
                ->addIndexColumn()
                ->editColumn('created_at', function ($row) {
                    return $row->date_formatted;
                })
                ->addColumn('action', function ($row) {
                    return '<form action="'.route('visit-enquiries.destroy', base64_encode($row->id)).'" method="POST" style="display: inline-block;">'.csrf_field().method_field('DELETE').'<button type="button" class="btn btn-danger btn-sm delete-btn" data-delete-message-type="itemOnly" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Delete"><i class="fas fa-trash"></i></button></form>';
                })
                ->rawColumns(['action'])
                ->toJson();
        }

        EnquiryLastRead::updateOrCreate(
            ['admin_id' => Auth::guard('admin')->id()],
            ['visit_at' => now()]
        );

        return view('admin::visit-enquiry.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $visitEnquiry = VisitEnquiry::findOrFail(base64_decode($id));
            $visitEnquiry->delete();

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Visit Enquiry deleted successfully.']);
        } catch (ModelNotFoundException $e) {
            DB::rollBack();

            return response()->json(['success' => false, 'message' => 'The requested Visit Enquiry does not exist.']);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error($e);

            return response()->json(['success' => false, 'message' => 'Something went wrong.']);
        }
    }

    /**
     * Export the visit enquiries to an Excel file using FastExcel.
     */
    public function export(Request $request)
    {
        $officeEnquiries = VisitEnquiry::query();

        if ($request->start_date && $request->end_date) {
            $startDate = Carbon::parse($request->start_date);
            $endDate = Carbon::parse($request->end_date);

            $officeEnquiries->whereBetween('created_at', [$startDate, $endDate]);
        }

        $officeEnquiries = $officeEnquiries->get();

        if ($officeEnquiries->isEmpty()) {
            return redirect()->back()->with('error', 'No enquiries found.');
        }

        $filename = 'visit-enquiries-'.now()->format('Y-m-d-H-i').'.xlsx';

        return FastExcel::data($officeEnquiries->map(function ($visitEnquiry, $index) {
            return [
                'SN' => $index + 1,
                'Name' => $visitEnquiry->name,
                'Email' => $visitEnquiry->email,
                'Phone Number' => $visitEnquiry->phone_number,
                'pincode' => $visitEnquiry->pincode,
                'Received At' => $visitEnquiry->created_at->format('d M Y h:i A'),
            ];
        }))
            ->headerStyle((new Style)->setFontBold())
            ->rowsStyle((new Style)->setFontSize(12))
            ->download($filename);
    }
}
