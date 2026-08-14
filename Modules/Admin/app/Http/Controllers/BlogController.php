<?php

namespace Modules\Admin\Http\Controllers;

use App\Helpers\BackendHelpers;
use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Admin\Http\Requests\BlogRequest;
use Yajra\DataTables\Facades\DataTables;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Blog::when(
                BackendHelpers::isOrderColumnZero($request),
                fn ($query) => $query->orderByDesc('id')
            );

            return DataTables::eloquent($data)
                ->addIndexColumn()
                ->addColumn('image', function ($row) {
                    return '<img src="'.$row->image_value.'" alt="'.$row->image_alt_text_value.'" class="table-thumbnail">';
                })
                ->editColumn('published_on', function ($row) {
                    return $row->published_on_value;
                })
                ->editColumn('status', function ($row) {
                    $fieldValue = $row->status ? 'checked' : '';
                    $fieldLabel = $row->status ? 'Published' : 'Draft';

                    return '<div class="form-check form-switch">
                        <input type="checkbox" id="status'.base64_encode($row->id).'" class="form-check-input toggle-switch" data-name="Status" data-labels="Published;Draft" data-column="status" data-model="Blog"
                            value="'.$row->status.'"
                            data-id="'.base64_encode($row->id).'" name="status"
                            '.$fieldValue.'>
                        <label class="custom-control-label" for="status'.base64_encode($row->id).'">'.$fieldLabel.'</label>
                    </div>';
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="'.route('blogs.edit', base64_encode($row->id)).'" class="btn btn-primary btn-sm mr-1" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Edit" style="margin-right: 3px;"><i class="fas fa-edit"></i></a>';
                    $btn .= '<a href="'.route('blogs.show', base64_encode($row->id)).'" class="btn btn-primary btn-sm mr-1" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Show" style="margin-right: 3px;"><i class="fas fa-eye"></i></a>';
                    $btn .= '<form action="'.route('blogs.destroy', base64_encode($row->id)).'" method="POST" style="display: inline-block;">'.csrf_field().method_field('DELETE').'<button type="button" class="btn btn-danger btn-sm delete-btn" data-delete-message-type="itemOnly" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Delete"><i class="fas fa-trash"></i></button></form>';

                    return $btn;
                })
                ->rawColumns(['image', 'status', 'action'])
                ->toJson();
        }

        return view('admin::blog.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin::blog.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BlogRequest $request)
    {
        DB::beginTransaction();

        try {
            $request->merge(['featured' => $request->has('featured') ? true : false]);

            $blog = Blog::create($request->all());

            $blog->uploadMedia($request, 'image');
            $blog->uploadMedia($request, 'banner');
            $blog->uploadMedia($request, 'banner_mobile');

            if ($blog->featured) {
                Blog::where('id', '!=', $blog->id)->update(['featured' => false]);
            }

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Blog created successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);

            return response()->json(['success' => false, 'message' => 'Something went wrong.']);
        }
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $blog = Blog::findOrFail(base64_decode($id));

        return view('admin::blog.show', compact('blog'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $blog = Blog::findOrFail(base64_decode($id));
        $relatedBlogs = $blog->related_blogs !== null ? Blog::select('id', 'title')->whereIn('id', $blog->related_blogs)->get() : collect();

        return view('admin::blog.form', compact('blog', 'relatedBlogs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BlogRequest $request, $id)
    {
        DB::beginTransaction();

        try {
            $request->merge(['featured' => $request->has('featured') ? true : false]);

            $blog = Blog::findOrFail(base64_decode($id));
            $blog->update($request->all());

            $blog->uploadMedia($request, 'image');
            $blog->uploadMedia($request, 'banner');
            $blog->uploadMedia($request, 'banner_mobile');

            if ($blog->featured) {
                Blog::where('id', '!=', $blog->id)->update(['featured' => false]);
            }

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Blog updated successfully.']);
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
            $blog = Blog::findOrFail(base64_decode($id));
            $blog->delete();

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Blog deleted successfully.']);
        } catch (ModelNotFoundException $e) {
            DB::rollBack();

            return response()->json(['success' => false, 'message' => 'The requested Blog does not exist.']);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error($e);

            return response()->json(['success' => false, 'message' => 'Something went wrong.']);
        }
    }

    /**
     * Fetch a paginated list of blogs.
     */
    public function getBlogs(Request $request)
    {
        $perPage = 30;

        $blogs = Blog::query()
            ->select('id', 'title')
            ->when($request->filled('search'), fn ($q) => $q->where('title', 'like', '%'.$request->search.'%'))
            ->when($request->filled('exclude_id'), fn ($q) => $q->whereKeyNot($request->exclude_id))
            ->paginate($perPage);

        return response()->json($blogs);
    }
}
