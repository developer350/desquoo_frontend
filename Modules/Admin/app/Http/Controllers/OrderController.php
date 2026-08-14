<?php

namespace Modules\Admin\Http\Controllers;

use App\Helpers\BackendHelpers;
use App\Http\Controllers\Controller;
use App\Mail\OrderAdminStatus;
use App\Mail\OrderStatusMail;
use App\Models\Order;
use App\Models\OrderTrack;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Yajra\DataTables\Facades\DataTables;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Order::select('orders.*')
                ->with('user:id,name')
                ->when(! empty($request->status), fn($query) => $query->where('status', $request->status))
                ->when(! empty($request->payment_status), fn($query) => $query->where('payment_status', $request->payment_status))
                ->when(! empty($request->user), fn($query) => $query->where('user_id', $request->user))
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
                    fn($query) => $query->orderByDesc('id')
                );

            return DataTables::eloquent($data)
                ->addIndexColumn()
                ->editColumn('created_at', function ($row) {
                    return $row->order_date_formatted;
                })
                ->editColumn('grand_total', function ($row) {
                    return '₹' . $row->grand_total;
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="' . route('orders.show', base64_encode($row->id)) . '" class="btn btn-primary btn-sm mr-1" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Show" style="margin-right: 3px;"><i class="fas fa-eye"></i></a>';

                    return $btn;
                })
                ->rawColumns(['action', 'status'])
                ->toJson();
        }

        return view('admin::order.index');
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $order = Order::with('orderItems.product', 'orderItems.productVariant.attributeValues', 'user', 'orderTracks.user', 'orderTracks.admin')->findOrFail(base64_decode($id));

        return view('admin::order.show', compact('order'));
    }

    public function changeStatus(Request $request)
    {
        DB::beginTransaction();
        try {
            $order = Order::with('orderItems.product', 'orderItems.productVariant')->find($request->order_id);
            if ($order->status == $request->status) {
                return response()->json(['status' => false, 'message' => 'Status already ' . $request->status]);
            }
            $order->status = $request->status;
            $order->save();

            OrderTrack::create([
                'order_id' => $order->id,
                'status' => $order->status,
                'admin_id' => Auth::guard('admin')->user()->id,
            ]);

            if ($request->status == 'cancelled') {
                $order->orderItems->each(function ($item) {
                    if ($item->product->is_manage_stock) {
                        $item->productVariant->increment('stock', $item->quantity);
                    }
                });
            }

            // status mail
            defer(function () use ($order) {
                if ($order->user_id != null) {
                    Mail::to($order->user->email)->send(new OrderStatusMail($order));
                }
                
                Mail::to(config('mail.to.admin'))
                    ->cc(config('mail.to.cc'))
                    ->send(new OrderAdminStatus($order));
            })->always();

            DB::commit();

            return response()->json(['status' => true, 'message' => 'Status updated successfully.']);
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json(['status' => false, 'message' => 'Something went wrong.', 'error' => $th->getMessage()]);
        }
    }
}
