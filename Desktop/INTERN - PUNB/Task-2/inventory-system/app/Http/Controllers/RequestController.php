<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\InventoryRequest;
use App\Models\InventoryLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequestController extends Controller
{
    // Staff: view catalog & submit requests
    public function catalog()
    {
        $items = Item::where('stock_quantity', '>', 0)->get();
        $myRequests = InventoryRequest::where('user_id', Auth::id())
            ->with('item')
            ->latest()
            ->get();
        return view('requests.catalog', compact('items', 'myRequests'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_id' => 'required|exists:items,item_id',
            'quantity_requested' => 'required|integer|min:1',
        ]);

        $item = Item::find($validated['item_id']);
        if ($item->stock_quantity < $validated['quantity_requested']) {
            return back()->with('error', 'Requested quantity exceeds available stock!');
        }

        InventoryRequest::create([
            'user_id' => Auth::id(),
            'item_id' => $validated['item_id'],
            'quantity_requested' => $validated['quantity_requested'],
            'status' => 'Pending',
            'request_date' => now(),
        ]);

        return redirect()->route('requests.catalog')->with('success', 'Request submitted successfully!');
    }

    // Admin: manage all requests
    public function manage()
    {
        $pendingRequests = InventoryRequest::where('status', 'Pending')->with(['user', 'item'])->latest()->get();
        $history = InventoryRequest::whereIn('status', ['Approved', 'Rejected'])->with(['user', 'item'])->latest()->take(20)->get();
        return view('requests.manage', compact('pendingRequests', 'history'));
    }

    public function approve(Request $request, $id)
    {
        $req = InventoryRequest::findOrFail($id);
        $item = Item::find($req->item_id);

        if ($item->stock_quantity < $req->quantity_requested) {
            return back()->with('error', 'Not enough stock to approve this request!');
        }

        $req->update([
            'status' => 'Approved',
            'remarks' => $request->remarks ?? 'Approved by Admin',
            'action_date' => now(),
            'read_at' => null, // Reset so staff gets a NEW alert
        ]);

        // Deduct stock
        $item->decrement('stock_quantity', $req->quantity_requested);

        // Log the transaction
        $adminRemarks = $request->remarks ? " | Admin: " . $request->remarks : "";
        InventoryLog::create([
            'item_id' => $item->item_id,
            'action' => 'Out',
            'quantity' => $req->quantity_requested,
            'performed_by' => Auth::id(),
            'remarks' => $req->user->name . $adminRemarks,
        ]);

        return redirect()->route('requests.manage')->with('success', 'Request approved successfully!');
    }

    public function reject(Request $request, $id)
    {
        $req = InventoryRequest::findOrFail($id);
        $req->update([
            'status' => 'Rejected',
            'remarks' => $request->remarks ?? 'Rejected by Admin',
            'action_date' => now(),
            'read_at' => null, // Reset so staff gets a NEW alert
        ]);

        return redirect()->route('requests.manage')->with('success', 'Request rejected.');
    }

    // Mark notification as read (AJAX)
    public function markRead($id)
    {
        $req = InventoryRequest::findOrFail($id);
        // Only owner or admin can mark as read
        if ($req->user_id == Auth::id() || Auth::user()->isAdmin()) {
            $req->update(['read_at' => now()]);
        }
        return response()->json(['success' => true]);
    }
}
