<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\InventoryLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::latest()->get();
        $categories = Item::distinct()->pluck('category');
        return view('items.index', compact('items', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_name' => 'required|string|max:100',
            'category' => 'required|string|max:50',
            'stock_quantity' => 'required|integer|min:0',
            'unit_price' => 'required|numeric|min:0',
            'min_threshold' => 'required|integer|min:1',
        ]);

        $item = Item::create($validated);

        // Log the initial stock as "In"
        if ($item->stock_quantity > 0) {
            InventoryLog::create([
                'item_id' => $item->item_id,
                'action' => 'In',
                'quantity' => $item->stock_quantity,
                'performed_by' => Auth::id(),
                'remarks' => 'Institutional Stock Initialization',
            ]);
        }

        return redirect()->route('items.index')->with('success', 'Item added successfully!');
    }

    public function update(Request $request, $id)
    {
        $item = Item::findOrFail($id);

        $validated = $request->validate([
            'item_name' => 'required|string|max:100',
            'category' => 'required|string|max:50',
            'unit_price' => 'required|numeric|min:0',
            'min_threshold' => 'required|integer|min:1',
        ]);

        $item->update($validated);
        return redirect()->route('items.index')->with('success', 'Item updated successfully!');
    }

    public function destroy($id)
    {
        Item::findOrFail($id)->delete();
        return redirect()->route('items.index')->with('success', 'Item deleted successfully!');
    }

    public function stockIn(Request $request, $id)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);
        $item = Item::findOrFail($id);
        $item->increment('stock_quantity', $request->quantity);

        InventoryLog::create([
            'item_id' => $item->item_id,
            'action' => 'In',
            'quantity' => $request->quantity,
            'performed_by' => Auth::id(),
        ]);

        return redirect()->route('items.index')->with('success', "Stock In: +{$request->quantity} {$item->item_name}");
    }

    public function stockOut(Request $request, $id)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);
        $item = Item::findOrFail($id);

        if ($item->stock_quantity < $request->quantity) {
            return back()->with('error', 'Not enough stock available!');
        }

        $item->decrement('stock_quantity', $request->quantity);

        InventoryLog::create([
            'item_id' => $item->item_id,
            'action' => 'Out',
            'quantity' => $request->quantity,
            'performed_by' => Auth::id(),
        ]);

        return redirect()->route('items.index')->with('success', "Stock Out: -{$request->quantity} {$item->item_name}");
    }

    public function logs(Request $request)
    {
        $query = InventoryLog::with(['item', 'performer']);
        $now = \Carbon\Carbon::now();

        if ($request->filled('range')) {
            switch ($request->range) {
                case 'today':
                    $query->whereDate('created_at', $now->toDateString());
                    break;
                case 'yesterday':
                    $query->whereDate('created_at', $now->subDay()->toDateString());
                    break;
                case '7_days':
                    $query->where('created_at', '>=', $now->subDays(7));
                    break;
                case 'this_month':
                    $query->whereMonth('created_at', $now->month)
                          ->whereYear('created_at', $now->year);
                    break;
            }
        } elseif ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59']);
        }

        $logs = $query->latest()->paginate(50);
        return view('items.logs', compact('logs'));
    }
}
