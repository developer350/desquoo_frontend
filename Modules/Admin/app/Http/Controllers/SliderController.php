<?php

namespace Modules\Admin\Http\Controllers;

use App\Helpers\BackendHelpers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Admin\Http\Requests\SliderRequest;
use App\Models\Slider;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Yajra\DataTables\Facades\DataTables;

class SliderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Slider::when(
                BackendHelpers::isOrderColumnZero($request),
                fn($query) => $query->orderByDesc('id')
            );
            return DataTables::eloquent($data)
                ->addIndexColumn()
                ->editColumn('media_type', function ($row) {
                    return $row->media_type_value;
                })
                ->editColumn('action_type', function ($row) {
                    return $row->action_type_value;
                })
                ->editColumn('sort_order', function ($row) {
                    return '<input type="text" value="' . $row->sort_order . '"
                        class="form-control w-50 sort-order numeric-input" data-model="Slider"
                        data-id="' . base64_encode($row->id) . '" name="sort_order">';
                })
                ->editColumn('status', function ($row) {
                    $fieldValue = $row->status ? 'checked' : '';
                    $fieldLabel = $row->status ? 'Enabled' : 'Disabled';
                    return '<div class="form-check form-switch">
                        <input type="checkbox" id="status' . base64_encode($row->id) . '" class="form-check-input toggle-switch" data-name="Status" data-labels="Enabled;Disabled" data-column="status" data-model="Slider"
                            value="' . $row->status . '"
                            data-id="' . base64_encode($row->id) . '" name="status"
                            ' . $fieldValue . '>
                        <label class="custom-control-label" for="status' . base64_encode($row->id) . '">' . $fieldLabel . '</label>
                    </div>';
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="' . route('sliders.edit', base64_encode($row->id)) . '" class="btn btn-primary btn-sm mr-1" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Edit" style="margin-right: 3px;"><i class="fas fa-edit"></i></a>';
                    $btn .= '<form action="' . route('sliders.destroy', base64_encode($row->id))  . '" method="POST" style="display: inline-block;">' . csrf_field() . method_field('DELETE') . '<button type="button" class="btn btn-danger btn-sm delete-btn" data-delete-message-type="itemOnly" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Delete"><i class="fas fa-trash"></i></button></form>';
                    return $btn;
                })
                ->rawColumns(['sort_order', 'status', 'action'])
                ->toJson();
        }
        return view('admin::slider.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sort_order = Slider::max('sort_order') + 1;
        return view('admin::slider.form', compact('sort_order'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SliderRequest $request)
    {
        DB::beginTransaction();

        try {
            $slider = Slider::create($request->all());

            if ($request->input('media_type') === 'image') {
                $slider->uploadMedia($request, 'image');
                $slider->uploadMedia($request, 'image_mobile');
            } elseif ($request->input('media_type') === 'video') {
                $slider->uploadMedia($request, 'video_thumbnail_image');
                $slider->uploadMedia($request, 'video_thumbnail_image_mobile');
                $slider->uploadMedia($request, 'video');
                $slider->uploadMedia($request, 'video_mobile');
            }

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Slider created successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);

            return response()->json(['success' => false, 'message' => 'Something went wrong.']);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $slider = Slider::findOrFail(base64_decode($id));
        return view('admin::slider.form', compact('slider'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SliderRequest $request, $id)
    {
        DB::beginTransaction();

        try {
            $slider = Slider::findOrFail(base64_decode($id));
            $slider->handleMediaChange($request);
            $slider->update($request->all());

            if ($request->input('media_type') === 'image') {
                $slider->uploadMedia($request, 'image');
                $slider->uploadMedia($request, 'image_mobile');
            } elseif ($request->input('media_type') === 'video') {
                $slider->uploadMedia($request, 'video_thumbnail_image');
                $slider->uploadMedia($request, 'video_thumbnail_image_mobile');
                $slider->uploadMedia($request, 'video');
                $slider->uploadMedia($request, 'video_mobile');
            }

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Slider updated successfully.']);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error($e);

            return response()->json(['success' => false, 'message' => 'Something went wrong.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $slider = Slider::findOrFail(base64_decode($id));
            $slider->delete();

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Slider deleted successfully.']);
        } catch (ModelNotFoundException $e) {
            DB::rollBack();

            return response()->json(['success' => false, 'message' => 'The requested Slider does not exist.']);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error($e);

            return response()->json(['success' => false, 'message' => 'Something went wrong.']);
        }
    }
}
