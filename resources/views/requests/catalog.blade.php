@extends('layouts.app')
@section('title', 'Institutional Asset Index')

@section('content')
<style>
    /* CATALOG CUSTOM STYLE */
    .catalog-table { width: 100%; border-collapse: collapse; }
    .catalog-table th { background: #fff; position: sticky; top: 0; z-index: 10; border-bottom: 2.5px solid #f8fafc; }
    .sku-badge { font-family: 'JetBrains Mono', monospace; font-size: 11px; font-weight: 800; color: #94a3b8; background: #f8fafc; padding: 6px 12px; border-radius: 10px; }
    
    .item-card { 
        transition: 0.3s; 
    }
    .item-card:hover { background: #fbfcfd !important; cursor: pointer; }
    
    /* QUANTITY BOX */
    .qty-display { display: flex; align-items: center; gap: 8px; font-weight: 800; color: var(--brand-teal); font-size: 18px; }
    .qty-display span { font-size: 10px; color: #94a3b8; letter-spacing: 0.5px; }
</style>

<div class="card" style="padding: 0; overflow: hidden; border-radius: 35px; border: 1px solid #f1f5f9; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);">
    <div class="table-responsive">
        <table class="catalog-table">
            <thead>
                <tr>
                    <th style="padding-left: 40px;">NOMENCLATURE</th>
                    <th>DETAIL INFO</th>
                    <th>CATEGORY</th>
                    <th>BALANCE</th>
                    <th style="text-align: right; padding-right: 40px;">ACTION</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                <tr class="item-card">
                    <td style="padding-left: 40px;">
                        <span class="sku-badge">SKU-0{{ $item->item_id }}</span>
                    </td>
                    <td>
                        <div style="font-weight: 800; color: #0f172a; font-size: 16px;">{{ $item->item_name }}</div>
                        <div style="font-size: 11px; color: #94a3b8; font-weight: 700; text-transform: uppercase;">Institutional Grade A Stock</div>
                    </td>
                    <td><span class="badge badge-teal">{{ $item->category }}</span></td>
                    <td>
                        <div class="qty-display">{{ $item->stock_quantity }} <span>UNITS</span></div>
                    </td>
                    <td style="padding-right: 40px; text-align: right;">
                        @if($item->stock_quantity > 0)
                            <button onclick="openRequestModal({{ $item->item_id }}, '{{ $item->item_name }}', {{ $item->stock_quantity }})" class="btn btn-teal" style="padding: 12px 24px; font-size: 13px; border-radius: 14px;">Request Asset</button>
                        @else
                            <span class="badge" style="background: #fef2f2; color: #ef4444; border: 1.5px solid #fee2e2;">Out of Stock</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" style="text-align:center; padding:120px; font-weight: 800; color: #94a3b8;">NO RECORDED ASSETS FOUND IN REPOSITORY</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- ELITE MODAL: STAFF REQUISITION -->
<div class="modal-overlay" id="reqModal">
    <div class="modal-card">
        <div style="border-bottom: 2px solid #f8fafc; margin-bottom: 35px; padding-bottom: 25px;">
            <h3 style="font-size: 26px; font-weight: 800; color: #0f172a; letter-spacing: -0.5px; margin-bottom: 8px;">Formal Requisition</h3>
            <p style="font-size: 12px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px;">PUNB Stationery Management System</p>
        </div>

        <form action="{{ route('requests.store') }}" method="POST">
            @csrf
            <input type="hidden" name="item_id" id="modalItemId">
            
            <div style="margin-bottom: 30px;">
                <label style="font-size: 11px; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 15px; display: block; padding-left: 5px;">Nominated Asset</label>
                <input type="text" id="modalItemName" class="modal-input" readonly style="opacity: 0.7; cursor: not-allowed; border-color: #f1f5f9; background: #fbfcfd;">
            </div>

            <div style="background: #fbfcfd; padding: 35px; border-radius: 28px; border: 2px dashed #f1f5f9; margin-bottom: 40px;">
                <label style="font-size: 11px; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 15px; display: block;">Volume Requested</label>
                <div style="display: flex; gap: 20px; align-items: center;">
                    <input type="number" name="quantity_requested" id="modalQty" class="modal-input" value="1" min="1" max="100" required style="margin-bottom: 0; flex: 1;">
                    <div style="text-align: right;">
                        <div style="font-size: 10px; font-weight: 800; color: #94a3b8; text-transform: uppercase;">Available</div>
                        <div style="font-size: 24px; font-weight: 800; color: var(--brand-teal);" id="modalMax">0</div>
                    </div>
                </div>
            </div>

            <div style="display: flex; gap: 15px;">
                <button type="button" onclick="document.getElementById('reqModal').classList.remove('show')" class="btn btn-secondary" style="flex: 1; border-radius: 22px;">Cancel</button>
                <button type="submit" class="btn btn-teal" style="flex: 2; border-radius: 22px; font-size: 16px;">Submit Requisition</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openRequestModal(id, name, max) {
        document.getElementById('modalItemId').value = id;
        document.getElementById('modalItemName').value = name;
        document.getElementById('modalMax').innerText = max;
        document.getElementById('modalQty').max = max;
        
        // Fix for quantity being too high
        document.getElementById('modalQty').oninput = function() {
            if (this.value > max) this.value = max;
        };

        const modal = document.getElementById('reqModal');
        modal.classList.add('show');
    }

    // Close modal on click outside card
    window.onclick = function(e) {
        const modal = document.getElementById('reqModal');
        if (e.target == modal) {
            modal.classList.remove('show');
        }
    }
</script>
@endsection
