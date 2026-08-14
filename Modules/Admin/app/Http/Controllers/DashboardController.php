<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (! auth('admin')->user()->can('view-dashboard-data')) {
            return view('admin::dashboard.no-permission');
        }

        $totalOrders = Order::count();
        $totalUsers = User::count();
        $delivered = Order::where('status', 'delivered')
            ->selectRaw('COUNT(*) as count, SUM(grand_total) as total')
            ->first();

        $latestOrders = Order::select('orders.*')
            ->with('user:id,name')->latest()->limit(10)->get();

        return view('admin::dashboard.index', compact('totalOrders', 'totalUsers', 'delivered', 'latestOrders'));
    }
}
