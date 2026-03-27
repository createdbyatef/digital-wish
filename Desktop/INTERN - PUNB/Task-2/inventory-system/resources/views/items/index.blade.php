@extends('layouts.app')
@section('title', 'Institutional Asset Register')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 35px; gap: 30px;">
    <div style="flex: 1;">
        <h2 style="font-size: 24px; font-weight: 800; color: #0f172a; letter-spacing: -1px;">Master Inventory Index</h2>
        <p style="color: #94a3b8; font-size: 14px; font-weight: 600; margin-bottom: 25px;">Centralized registry for all departmental physical resources.</p>
        
        <div style="position: relative; max-width: 450px;">
            <svg style="position: absolute; left: 18px; top: 50%; transform: translateY(-50%); color: #94a3b8;" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            <input type="text" id="assetSearchInput" onkeyup="filterAssets()" placeholder="Search assets by name or ID..." style="width: 100%; padding: 14px 25px 14px 52px; border-radius: 16px; border: 1.5px solid #f1f5f9; background: #fff; font-size: 14px; font-weight: 600; outline: none; transition: 0.3s; color: #1e293b; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);">
        </div>
    </div>
    @if(Auth::user()->isAdmin())
    <button onclick="document.getElementById('addAssetModal').classList.add('show')" class="btn btn-teal" style="margin-bottom: 5px;">
        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
        Register New Asset
    </button>
    @endif
</div>

<div class="card" style="padding: 0; overflow: hidden; border-radius: 20px;">
    <div class="table-responsive">
        <table id="assetTable">
            <thead>
                <tr>
                    <th style="padding-left: 32px;">Reference</th>
                    <th>Asset Name</th>
                    <th>Category</th>
                    <th>Balance</th>
                    <th>Stock Status</th>
                    @if(Auth::user()->isAdmin())
                    <th style="text-align: right; padding-right: 32px;">Inventory Actions</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                <tr>
                    <td style="padding-left: 32px; font-family: monospace; font-weight: 800; color: #94a3b8;">#00{{ $item->item_id }}</td>
                    <td>
                        <div style="font-weight: 800; color: #1e293b; font-size: 15px;">{{ $item->item_name }}</div>
                        <div style="font-size: 11px; color: #94a3b8; font-weight: 700;">Updated: {{ $item->updated_at->format('d/m/Y') }}</div>
                    </td>
                    <td><span class="badge badge-teal">{{ $item->category }}</span></td>
                    <td><div style="font-size: 18px; font-weight: 800; color: var(--brand-teal);">{{ $item->stock_quantity }} <span style="font-size: 10px; color: #94a3b8;">UNITS</span></div></td>
                    <td>
                        @if($item->isLowStock())
                            <span class="badge badge-danger">RESTOCK NEEDED</span>
                        @else
                            <span class="badge badge-success">Sufficient</span>
                        @endif
                    </td>
                    @if(Auth::user()->isAdmin())
                    <td style="padding-right: 32px;">
                        <div style="display: flex; gap: 8px; justify-content: flex-end; align-items: center;">
                            <button onclick="openAdjustModal('{{ $item->item_id }}', '{{ $item->item_name }}', '{{ route('items.stockIn', $item->item_id) }}', 'IN')" class="btn btn-success btn-sm" style="padding: 10px 14px;">+</button>
                            <button onclick="openAdjustModal('{{ $item->item_id }}', '{{ $item->item_name }}', '{{ route('items.stockOut', $item->item_id) }}', 'OUT')" class="btn btn-danger btn-sm" style="padding: 10px 14px;">-</button>
                            
                            <form action="{{ route('items.destroy', $item->item_id) }}" method="POST" onsubmit="return confirm('WARNING: Are you sure you want to PERMANENTLY delete this asset from the register? This action cannot be undone.')" style="margin-left: 8px;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-secondary btn-sm" style="padding: 10px; background: #fff1f1; border-color: #fee2e2; color: #dc2626;">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                    @endif
                </tr>
                @empty
                <tr><td colspan="6" style="text-align:center; padding:100px; color:#94a3b8; font-weight:800;">NO ASSETS REGISTERED YET.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- MODAL: ADD ASSET -->
<div class="modal-overlay" id="addAssetModal">
    <div class="modal-card" style="width: 550px;">
        <h3 style="font-size: 24px; font-weight: 800; margin-bottom: 30px; letter-spacing: -1px;">Register New Asset</h3>
        <form action="{{ route('items.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div style="margin-bottom: 20px;">
                    <label style="font-size: 11px; font-weight: 800; color: #94a3b8; text-transform: uppercase;">Asset Name (Nomenclature)</label>
                    <input type="text" name="item_name" class="modal-input" placeholder="e.g. A4 Paper (Ream)" required>
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="font-size: 11px; font-weight: 800; color: #94a3b8; text-transform: uppercase;">Category Classification</label>
                    <select id="catSelector" name="category" class="modal-input" required onchange="checkNewCat(this)">
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}">{{ $cat }}</option>
                        @endforeach
                        <option value="NEW_CATEGORY">+ Add New Category...</option>
                    </select>
                    <div id="newCatWrapper" style="display: none; margin-top: -10px;">
                        <input type="text" id="newCatInput" class="modal-input" placeholder="Name new category" style="border-color: var(--brand-teal);">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                    <div><label style="font-size: 11px; font-weight: 800; color: #94a3b8;">OPENING STOCK</label><input type="number" name="stock_quantity" class="modal-input" value="0" required></div>
                    <div><label style="font-size: 11px; font-weight: 800; color: #94a3b8;">UNIT PRICE (RM)</label><input type="number" step="0.01" name="unit_price" class="modal-input" value="0.00" required></div>
                </div>

                <div style="margin-bottom: 20px;"><label style="font-size: 11px; font-weight: 800; color: #94a3b8;">THRESHOLD ALERT</label><input type="number" name="min_threshold" class="modal-input" value="5" required></div>
            </div>
            <div style="display: flex; gap: 12px; margin-top: 10px;">
                <button type="button" onclick="document.getElementById('addAssetModal').classList.remove('show')" class="btn btn-secondary" style="flex:1">Cancel</button>
                <button type="submit" class="btn btn-teal" style="flex:2">Register Asset</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL: ADJUST STOCK -->
<div class="modal-overlay" id="adjustModal">
    <div class="modal-card">
        <h3 id="adjTitle" style="font-size: 24px; font-weight: 800; margin-bottom: 30px; letter-spacing: -1px;">Stock Adjustment</h3>
        <form id="adjForm" method="POST">
            @csrf
            <div class="modal-body">
                <p id="adjSub" style="font-weight:700; color:#94a3b8; margin-bottom:20px;">-</p>
                <label id="adjQtyLabel">Quantity</label>
                <input type="number" name="quantity" class="modal-input" value="1" min="1" required>
                <label>Remarks</label>
                <input type="text" name="remarks" class="modal-input" placeholder="Ref/Remarks">
            </div>
            <div style="display: flex; gap: 12px; margin-top: 10px;">
                <button type="button" onclick="document.getElementById('adjustModal').classList.remove('show')" class="btn btn-secondary" style="flex:1">Cancel</button>
                <button type="submit" id="adjBtn" class="btn btn-teal" style="flex:2">Confirm</button>
            </div>
        </form>
    </div>
</div>

<script>
    function filterAssets() {
        const input = document.getElementById('assetSearchInput');
        const filter = input.value.toUpperCase();
        const table = document.getElementById('assetTable');
        const tr = table.getElementsByTagName('tr');

        for (let i = 1; i < tr.length; i++) {
            const tdName = tr[i].getElementsByTagName('td')[1];
            const tdRef = tr[i].getElementsByTagName('td')[0];
            if (tdName || tdRef) {
                const txtValue = (tdName.textContent || tdName.innerText) + ' ' + (tdRef.textContent || tdRef.innerText);
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }

    function checkNewCat(sel) {
        const wrapper = document.getElementById('newCatWrapper');
        const input = document.getElementById('newCatInput');
        if(sel.value === 'NEW_CATEGORY') { wrapper.style.display = 'block'; input.required = true; input.name = 'category'; sel.removeAttribute('name'); input.focus(); } 
        else { wrapper.style.display = 'none'; input.required = false; input.removeAttribute('name'); sel.name = 'category'; }
    }

    function openAdjustModal(id, name, url, type) {
        document.getElementById('adjForm').action = url;
        document.getElementById('adjTitle').innerText = (type === 'IN' ? 'Stock Enrichment' : 'Stock Disbursement');
        document.getElementById('adjSub').innerText = 'Target Asset: ' + name;
        document.getElementById('adjQtyLabel').innerText = (type === 'IN' ? 'Inward Volume' : 'Outward Volume');
        const btn = document.getElementById('adjBtn');
        btn.className = (type === 'IN' ? 'btn btn-success' : 'btn btn-danger');
        btn.innerText = (type === 'IN' ? 'Process Receipt' : 'Process Disbursement');
        document.getElementById('adjustModal').classList.add('show');
    }
</script>
@endsection
