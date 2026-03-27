@extends('layouts.app')
@section('title', 'System Dashboard')

@section('content')
<style>
    .stats-row { display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px; margin-bottom: 40px; }
    .stat-card {
        background: #fff; border-radius: 24px; padding: 24px;
        border: 1px solid #f1f5f9; position: relative; overflow: hidden;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);
    }
    .stat-card:hover { transform: translateY(-5px); box-shadow: 0 15px 30px -10px rgba(15, 23, 42, 0.08); border-color: var(--brand-teal); }
    
    .stat-icon-box {
        width: 52px; height: 52px; border-radius: 16px;
        display: flex; align-items: center; justify-content: center; font-size: 22px;
        margin-bottom: 16px; transition: 0.3s;
    }
    
    .teal-grad { background: linear-gradient(135deg, #0099A7 0%, #006D77 100%); color: #fff; box-shadow: 0 8px 15px -4px rgba(0, 153, 167, 0.3); }
    .gold-grad { background: linear-gradient(135deg, #f59e0b 0%, #b45309 100%); color: #fff; box-shadow: 0 8px 15px -4px rgba(245, 158, 11, 0.3); }
    .red-grad { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: #fff; box-shadow: 0 8px 15px -4px rgba(239, 68, 68, 0.3); }

    .stat-card h3 { font-size: 30px; font-weight: 800; color: #0f172a; letter-spacing: -1.5px; line-height: 1; margin-bottom: 4px; }
    .stat-card p { font-size: 10px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 1.2px; }

    .grid-2 { display: grid; grid-template-columns: 2fr 1.2fr; gap: 35px; align-items: stretch; }
    .grid-2 > div { display: flex; flex-direction: column; }
    .grid-2 .card { flex: 1; display: flex; flex-direction: column; box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05); }
    .section-title { font-size: 20px; font-weight: 800; color: #0f172a; margin-bottom: 30px; display: flex; align-items: center; gap: 12px; letter-spacing: -0.5px; }
    .section-title::before { content: ''; width: 6px; height: 26px; background: var(--brand-teal); border-radius: 10px; }
</style>

<div class="stats-row">
    <div class="stat-card">
        <div class="stat-icon-box teal-grad">📦</div>
        <p>Asset Classes</p>
        <h3>{{ $totalItems }}</h3>
    </div>
    <div class="stat-card">
        <div class="stat-icon-box teal-grad">📈</div>
        <p>Stock Balance</p>
        <h3>{{ number_format($totalStock) }}</h3>
    </div>
    <div class="stat-card">
        <div class="stat-icon-box gold-grad">⏳</div>
        <p>Pending Review</p>
        <h3>{{ $pendingRequests }}</h3>
    </div>
    <div class="stat-card">
        <div class="stat-icon-box red-grad">🚨</div>
        <p>Low Alerts</p>
        <h3>{{ $lowStockItems->count() }}</h3>
    </div>
</div>

<div class="grid-2">
    <div>
        <h2 class="section-title">Current Stock Status</h2>
        <div class="card" style="padding: 0; overflow: hidden; border-radius: 20px;">
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th style="padding-left: 32px;">Asset Name</th>
                            <th>Category</th>
                            <th>Balance</th>
                            <th>Health Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items->take(5) as $item)
                        <tr>
                            <td style="padding-left: 32px;">
                                <div style="font-weight: 800; color: #1e293b;">{{ $item->item_name }}</div>
                                <div style="font-size: 10px; color: #94a3b8; font-weight: 700;">ID-{{ $item->item_id }}</div>
                            </td>
                            <td style="font-weight: 700; color: #64748b; font-size: 13px;">{{ $item->category }}</td>
                            <td style="font-weight: 800; font-size: 18px; color: var(--brand-teal);">{{ $item->stock_quantity }}</td>
                            <td>
                                @if($item->isLowStock())
                                    <span class="badge badge-danger">CRITICAL</span>
                                @else
                                    <span class="badge badge-success">Sufficient</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div style="padding: 20px; text-align: center; border-top: 1px solid #f8fafc; background: #fbfcfd;">
                <a href="{{ route('items.index') }}" style="color: var(--brand-teal); font-size: 13px; font-weight: 800; text-decoration: none; display: flex; align-items: center; justify-content: center; gap: 8px;">
                    View All Stock Index 
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </a>
            </div>
        </div>
    </div>

    <div>
        <h2 class="section-title">Recent Activity Log</h2>
        <div class="card" style="padding: 24px; border-radius: 20px;">
            <div style="flex: 1; overflow-y: auto;">
                @forelse($recentLogs->take(7) as $log)
                <div style="display: flex; gap: 16px; margin-bottom: 24px;">
                    <div style="width: 40px; height: 40px; border-radius: 12px; background: #f8fafc; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 18px;">
                        {{ $log->action === 'In' ? '📥' : '📤' }}
                    </div>
                    <div>
                        <h4 style="font-size: 13px; font-weight: 800; color: #1e293b;">{{ $log->action === 'In' ? 'Stock In' : 'Stock Out' }} ({{ $log->quantity }})</h4>
                        <p style="font-size: 11px; font-weight: 700; color: #94a3b8; margin-top: 2px;">{{ $log->item->item_name ?? 'Product' }} • {{ $log->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                @empty
                <p style="text-align: center; color: #94a3b8; padding: 40px; font-weight: 700;">No recent movements.</p>
                @endforelse
            </div>
            <div style="padding-top: 20px; text-align: center; border-top: 1px solid #f8fafc; margin-top: auto;">
                <a href="{{ route('items.logs') }}" style="color: var(--brand-teal); font-size: 13px; font-weight: 800; text-decoration: none; display: flex; align-items: center; justify-content: center; gap: 8px;">
                    View All History 
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
