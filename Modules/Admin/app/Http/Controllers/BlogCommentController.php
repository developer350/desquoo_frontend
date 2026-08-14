<?php

namespace Modules\Admin\Http\Controllers;

use App\Helpers\BackendHelpers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\BlogComment;
use App\Models\EnquiryLastRead;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use OpenSpout\Common\Entity\Style\Style;
use Rap2hpoutre\FastExcel\Facades\FastExcel;
use Yajra\DataTables\Facades\DataTables;

class BlogCommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $date_range = $request->date_range;

            $data = BlogComment::query()
                ->with('blog:id,title')
                ->when(
                    BackendHelpers::isOrderColumnZero($request),
                    fn($query) => $query->orderByDesc('id')
                )
                ->when($date_range, fn($query) => $query->whereBetween(
                    'created_at',
                    collect(explode(" to ", $date_range))->map(fn($date) => Carbon::parse($date))
                ));
            return DataTables::eloquent($data)
                ->addIndexColumn()
                ->addColumn('blog', function ($row) {
                    return $row->blog?->title;
                })
                ->editColumn('created_at', function ($row) {
                    return $row->date_formatted;
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="' . route('blog-comments.show', base64_encode($row->id)) . '" class="btn btn-primary btn-sm mr-1" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Show" style="margin-right: 3px;"><i class="fas fa-eye"></i></a>';
                    $btn .= '<form action="' . route('blog-comments.destroy', base64_encode($row->id))  . '" method="POST" style="display: inline-block;">' . csrf_field() . method_field('DELETE') . '<button type="button" class="btn btn-danger btn-sm delete-btn" data-delete-message-type="itemOnly" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Delete"><i class="fas fa-trash"></i></button></form>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->toJson();
        }

        EnquiryLastRead::updateOrCreate(
            ['admin_id' => Auth::guard('admin')->id()],
            ['blog_comment_at' => now()]
        );

        return view('admin::blog-comment.index');
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $blogComment = BlogComment::findOrFail(base64_decode($id));
        return view('admin::blog-comment.show', compact('blogComment'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $blogComment = BlogComment::findOrFail(base64_decode($id));
            $blogComment->delete();

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Blog Comment deleted successfully.']);
        } catch (ModelNotFoundException $e) {
            DB::rollBack();

            return response()->json(['success' => false, 'message' => 'The requested Blog Comment does not exist.']);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error($e);

            return response()->json(['success' => false, 'message' => 'Something went wrong.']);
        }
    }

    /**
     * Export the blog comments to an Excel file using FastExcel.
     */
    public function export(Request $request)
    {
        $blogComments = BlogComment::query();

        if ($request->start_date && $request->end_date) {
            $startDate = Carbon::parse($request->start_date);
            $endDate = Carbon::parse($request->end_date);

            $blogComments->whereBetween('created_at', [$startDate, $endDate]);
        }

        $blogComments = $blogComments->get();

        if ($blogComments->isEmpty()) {
            return redirect()->back()->with('error', 'No comments found.');
        }

        $filename = 'blog-comments-' . now()->format('Y-m-d-H-i') . '.xlsx';

        return FastExcel::data($blogComments->map(function ($blogComment, $index) {
            return [
                'SN' => $index + 1,
                'Blog' => $blogComment->blog?->title,
                'Name' => $blogComment->name,
                'Email' => $blogComment->email,
                'Comment' => $blogComment->comment,
                'Received At' => $blogComment->created_at->format('d M Y h:i A'),
            ];
        }))
            ->headerStyle((new Style())->setFontBold())
            ->rowsStyle((new Style())->setFontSize(12))
            ->download($filename);
    }
}
