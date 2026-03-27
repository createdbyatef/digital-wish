@extends('layouts.app')
@section('title', 'Activity Audit Logs')

@section('content')
<style>
    .filter-pills { display: flex; gap: 12px; margin-bottom: 25px; flex-wrap: wrap; }
    .pill {
        padding: 12px 24px; border-radius: 100px; background: #fff; border: 1.5px solid #f1f5f9;
        font-size: 13px; font-weight: 800; color: #64748b; text-decoration: none; transition: 0.3s;
        display: inline-flex; align-items: center; gap: 8px;
    }
    .pill:hover { border-color: var(--brand-teal); color: var(--brand-teal); background: var(--brand-teal-soft); }
    .pill.active { background: var(--brand-teal); color: #fff; border-color: var(--brand-teal); box-shadow: 0 10px 15px -3px rgba(0, 153, 167, 0.2); }
    .custom-date-trigger { color: var(--brand-teal); font-size: 13px; font-weight: 800; text-decoration: underline; cursor: pointer; margin-left: auto; }
</style>

<div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 25px; gap: 30px;">
    <div style="flex: 1;">
        <h2 style="font-size: 22px; font-weight: 800; color: #0f172a; margin-bottom: 5px;">Institutional Audit Records</h2>
        <p style="color: #94a3b8; font-size: 13px; font-weight: 600; margin-bottom: 20px;">Comprehensive ledger for all item inflows and outflows.</p>
        
        <div style="position: relative; max-width: 480px;">
            <svg style="position: absolute; left: 18px; top: 50%; transform: translateY(-50%); color: #94a3b8;" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            <input type="text" id="auditSearchInput" onkeyup="filterAudit()" placeholder="Search records by asset, beneficiary, or operator..." style="width: 100%; padding: 14px 25px 14px 52px; border-radius: 16px; border: 1.5px solid #f1f5f9; background: #fff; font-size: 14px; font-weight: 600; outline: none; transition: 0.3s; color: #1e293b; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);">
        </div>
    </div>
    <div style="text-align: right;">
        <span style="font-size: 12px; font-weight: 800; color: #94a3b8;">{{ $logs->total() }} Log Entries Found</span>
    </div>
</div>

<div class="filter-pills">
    <a href="{{ route('items.logs') }}" class="pill {{ !request('range') && !request('start_date') ? 'active' : '' }}">📦 Full History</a>
    <a href="{{ route('items.logs', ['range' => 'today']) }}" class="pill {{ request('range') == 'today' ? 'active' : '' }}">📅 Today</a>
    <a href="{{ route('items.logs', ['range' => 'yesterday']) }}" class="pill {{ request('range') == 'yesterday' ? 'active' : '' }}">⏳ Yesterday</a>
    <a href="{{ route('items.logs', ['range' => '7_days']) }}" class="pill {{ request('range') == '7_days' ? 'active' : '' }}">📆 7 Days</a>
    <a href="{{ route('items.logs', ['range' => 'this_month']) }}" class="pill {{ request('range') == 'this_month' ? 'active' : '' }}">🏢 This Month</a>
    <span class="custom-date-trigger" onclick="document.getElementById('customDateBar').style.display = 'flex'">Custom...</span>
</div>

<div id="customDateBar" class="card" style="display: {{ request('start_date') ? 'flex' : 'none' }}; margin-bottom: 25px; padding: 20px; align-items: flex-end; gap: 15px; border: 2px solid var(--brand-teal-soft);">
    <form action="{{ route('items.logs') }}" method="GET" style="display: flex; gap: 15px; flex: 1; align-items: flex-end;">
        <div style="flex:1;"><label style="font-size: 10px; font-weight: 800; color: #94a3b8;">START</label><input type="date" name="start_date" value="{{ request('start_date') }}" class="modal-input" style="margin-bottom:0; padding:12px;"></div>
        <div style="flex:1;"><label style="font-size: 10px; font-weight: 800; color: #94a3b8;">END</label><input type="date" name="end_date" value="{{ request('end_date') }}" class="modal-input" style="margin-bottom:0; padding:12px;"></div>
        <button type="submit" class="btn btn-teal btn-sm">Filter</button>
        <button type="button" onclick="window.location='/inventory/logs'" class="btn btn-secondary btn-sm">Reset</button>
    </form>
</div>

<div class="card" style="padding: 0; overflow: hidden; border-radius: 20px;">
    <div class="table-responsive">
        <table id="auditTable">
            <thead>
                <tr>
                    <th style="padding-left: 32px;">Timestamp</th>
                    <th>Asset Details</th>
                    <th>Action</th>
                    <th style="text-align: center;">Qty</th>
                    <th>Beneficiary / Notes</th>
                    <th>Operator</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr>
                    <td style="padding-left: 32px;">
                        <div style="font-weight: 800; color: #1e293b; font-size: 13px;">{{ $log->created_at->format('d M Y') }}</div>
                        <div style="font-size: 11px; color: #94a3b8; font-weight: 700;">{{ $log->created_at->format('h:i A') }}</div>
                    </td>
                    <td style="font-weight: 800; color: #1e293b;">{{ $log->item->item_name ?? 'DELETED' }}</td>
                    <td>
                        @if($log->action == 'In')
                            <span class="badge" style="background: #ecfdf5; color: #059669; font-size: 10px;">STOCK IN</span>
                        @else
                            <span class="badge" style="background: #fef2f2; color: #dc2626; font-size: 10px;">STOCK OUT</span>
                        @endif
                    </td>
                    <td style="text-align: center; font-weight: 800; color: var(--brand-teal);">{{ $log->quantity }}</td>
                    <td style="font-weight: 700; color: #64748b; font-size: 12px; max-width: 200px;">
                        {{ $log->remarks ?? 'Internal Distribution' }}
                    </td>
                    <td>
                        <div style="font-weight: 800; color: #1e293b; font-size: 13px;">{{ $log->performer->name ?? 'System' }}</div>
                        <div style="font-size: 10px; color: #94a3b8; font-weight: 700;">{{ strtoupper($log->performer->role ?? 'Admin') }}</div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" style="text-align:center; padding:100px; color:#94a3b8; font-weight:800;">NO LOG RECORDS FOUND.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="padding: 24px;">{{ $logs->appends(request()->input())->links() }}</div>
</div>

<script>
    function filterAudit() {
        const input = document.getElementById('auditSearchInput');
        const filter = input.value.toUpperCase();
        const table = document.getElementById('auditTable');
        const tr = table.getElementsByTagName('tr');

        for (let i = 1; i < tr.length; i++) {
            const tdAsset = tr[i].getElementsByTagName('td')[1];
            const tdNote = tr[i].getElementsByTagName('td')[4];
            const tdOperator = tr[i].getElementsByTagName('td')[5];
            
            if (tdAsset || tdNote || tdOperator) {
                const txtValue = (tdAsset.textContent || tdAsset.innerText) + ' ' + 
                                (tdNote.textContent || tdNote.innerText) + ' ' + 
                                (tdOperator.textContent || tdOperator.innerText);
                                
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }
</script>
@endsection
