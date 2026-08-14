<?php

namespace Modules\Admin\Http\Controllers;

use App\Helpers\BackendHelpers;
use App\Http\Controllers\Controller;
use App\Models\ProductReview;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class ProductReviewController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = ProductReview::select('product_reviews.*')->with('product:id,name', 'user:id,name')
                ->when($request->has('status') && $request->status != '', fn ($query) => $query->where('status', $request->status))
                ->when(! empty($request->product), fn ($query) => $query->where('product_id', $request->product))
                ->when(! empty($request->user), fn ($query) => $query->where('user_id', $request->user))
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
                ->addColumn('image', function ($row) {
                    if ($row->review_image_value != null) {
                        return '<img src="'.$row->review_image_value.'" alt="review-image'.$row->id.'" class="table-thumbnail me-2">';
                    } else {
                        return 'N/A';
                    }
                })
                ->editColumn('created_at', function ($row) {
                    return $row->date_formatted;
                })
                ->editColumn('highlight', function ($row) {
                    if ($row->review_image_value != null) {
                        $fieldValue = $row->highlight ? 'checked' : '';
                        $fieldLabel = $row->highlight ? 'Enabled' : 'Disabled';

                        return '<div class="form-check form-switch">
                                    <input type="checkbox" id="highlight'.base64_encode($row->id).'" class="form-check-input toggle-switch" data-name="highlight" data-labels="Enabled;Disabled" data-column="highlight" data-model="ProductReview"
                                        value="'.$row->highlight.'"
                                        data-id="'.base64_encode($row->id).'" name="highlight"
                                        '.$fieldValue.'>
                                    <label class="custom-control-label" for="highlight'.base64_encode($row->id).'">'.$fieldLabel.'</label>
                                </div>';
                    } else {
                        return 'N/A';
                    }
                })
                ->editColumn('status', function ($row) {
                    $fieldValue = $row->status ? 'checked' : '';
                    $fieldLabel = $row->status ? 'Enabled' : 'Disabled';

                    return '<div class="form-check form-switch">
                        <input type="checkbox" id="status'.base64_encode($row->id).'" class="form-check-input toggle-switch" data-name="Status" data-labels="Enabled;Disabled" data-column="status" data-model="ProductReview"
                            value="'.$row->status.'"
                            data-id="'.base64_encode($row->id).'" name="status"
                            '.$fieldValue.'>
                        <label class="custom-control-label" for="status'.base64_encode($row->id).'">'.$fieldLabel.'</label>
                    </div>';
                })
                ->addColumn('action', function ($row) {
                    $btn = '<form action="'.route('product-reviews.destroy', base64_encode($row->id)).'" method="POST" style="display: inline-block;">'.csrf_field().method_field('DELETE').'<button type="button" class="btn btn-danger btn-sm delete-btn" data-delete-message-type="itemOnly" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Delete"><i class="fas fa-trash"></i></button></form>';

                    return $btn;
                })
                ->rawColumns(['status', 'action', 'image', 'highlight'])
                ->toJson();
        }

        return view('admin::product-review.index');
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $review = ProductReview::findOrFail(base64_decode($id));
            $review->delete();

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Product Review deleted successfully.']);
        } catch (ModelNotFoundException $e) {
            DB::rollBack();

            return response()->json(['success' => false, 'message' => 'The requested Product Review does not exist.']);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error($e);

            return response()->json(['success' => false, 'message' => 'Something went wrong.']);
        }
    }
}
