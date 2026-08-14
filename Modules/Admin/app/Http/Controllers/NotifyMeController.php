<?php

namespace Modules\Admin\Http\Controllers;

use App\Helpers\BackendHelpers;
use App\Http\Controllers\Controller;
use App\Models\NotifyMe;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class NotifyMeController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = NotifyMe::select('notify_mes.*')->with('product:id,name', 'productVariant:id,sku', 'productVariant.attributeValues.attribute:id,name')
                ->when(! empty($request->product), fn ($query) => $query->where('product_id', $request->product))
                ->when(! empty($request->date_range), function ($query) use ($request) {
                    $date_range = $request->date_range;
                    $dates = explode(' to ', $date_range);
                    if (count($dates) === 2) {
                        $start = Carbon::parse($dates[0])->startOfDay();
                        $end = Carbon::parse($dates[1])->endOfDay();
                        $query->whereBetween('created_at', [$start, $end]);
                    } elseif (count($dates) == 1) {
                        $query->whereDate('created_at', $date_range);
                    }
                })
                ->when(
                    BackendHelpers::isOrderColumnZero($request),
                    fn ($query) => $query->orderByDesc('id')
                );

            return DataTables::eloquent($data)
                ->addIndexColumn()
                ->editColumn('created_at', function ($row) {
                    return $row->date_formatted;
                })
                ->addColumn('action', function ($row) {
                    $btn = '<form action="'.route('notify-mes.destroy', base64_encode($row->id)).'" method="POST" style="display: inline-block;">'.csrf_field().method_field('DELETE').'<button type="button" class="btn btn-danger btn-sm delete-btn" data-delete-message-type="itemOnly" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Delete"><i class="fas fa-trash"></i></button></form>';

                    return $btn;
                })
                ->rawColumns(['status', 'action'])
                ->toJson();
        }

        return view('admin::notify.index');
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $review = NotifyMe::findOrFail(base64_decode($id));
            $review->delete();

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Notify Me deleted successfully.']);
        } catch (ModelNotFoundException $e) {
            DB::rollBack();

            return response()->json(['success' => false, 'message' => 'The requested Notify Me does not exist.']);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error($e);

            return response()->json(['success' => false, 'message' => 'Something went wrong.']);
        }
    }
}
