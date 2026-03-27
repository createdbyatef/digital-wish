<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\InventoryRequest;
use App\Models\InventoryLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            // ADMIN: Full overview
            $totalItems = Item::count();
            $totalStock = Item::sum('stock_quantity');
            $lowStockItems = Item::whereColumn('stock_quantity', '<=', 'min_threshold')->get();
            $pendingRequests = InventoryRequest::where('status', 'Pending')->count();
            $recentLogs = InventoryLog::with(['item', 'performer'])->latest()->take(10)->get();
            $items = Item::all();

            return view('dashboard', compact(
                'totalItems', 'totalStock', 'lowStockItems',
                'pendingRequests', 'recentLogs', 'items'
            ));
        } else {
            // STAFF: Personal overview
            $myRequests = InventoryRequest::where('user_id', $user->id)
                ->with('item')
                ->latest()
                ->get();
            $pendingCount = $myRequests->where('status', 'Pending')->count();
            $approvedCount = $myRequests->where('status', 'Approved')->count();
            $rejectedCount = $myRequests->where('status', 'Rejected')->count();
            $availableItems = Item::where('stock_quantity', '>', 0)->count();

            return view('dashboard-staff', compact(
                'myRequests', 'pendingCount', 'approvedCount',
                'rejectedCount', 'availableItems'
            ));
        }
    }
}
