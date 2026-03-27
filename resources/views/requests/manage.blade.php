@extends('layouts.app')
@section('title', 'Requests Processing Center')

@section('content')
<div class="card" style="padding: 0; overflow: hidden; border-radius: 20px;">
    <div style="padding: 30px; border-bottom: 1px solid #f1f5f9;">
        <h3 style="font-size: 18px; font-weight: 800; color: #0f172a;">Pending Approvals Ledger</h3>
    </div>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th style="padding-left: 32px;">Submission Reference</th>
                    <th>Requester Details</th>
                    <th>Asset Details</th>
                    <th>Volume</th>
                    <th style="text-align: right; padding-right: 32px;">Action Control</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pendingRequests as $req)
                <tr>
                    <td style="padding-left: 32px; font-family: monospace; font-weight: 800; color: #94a3b8;">RFQ-0{{ $req->request_id }}</td>
                    <td>
                        <div style="font-weight: 800; color: #1e293b;">{{ $req->user->name }}</div>
                        <div style="font-size: 11px; color: #94a3b8; font-weight: 700; text-transform: uppercase;">{{ $req->user->department ?? 'Technology' }}</div>
                    </td>
                    <td>
                        <div style="font-weight: 800; color: var(--brand-teal);">{{ $req->item->item_name }}</div>
                        <div style="font-size: 11px; color: #94a3b8; font-weight: 700;">Stock: {{ $req->item->stock_quantity }} avail.</div>
                    </td>
                    <td style="font-weight: 800; font-size: 16px;">{{ $req->quantity_requested }}</td>
                    <td style="text-align: right; padding-right: 32px;">
                        <button onclick="openDecisionModal('{{ $req->request_id }}', '{{ $req->user->name }}', '{{ $req->item->item_name }}', '{{ $req->quantity_requested }}')" class="btn btn-teal btn-sm">Process Request</button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" style="text-align:center; padding:100px;">NO PENDING REQUISITIONS FOUND</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- MODAL: DECISION CENTER -->
<div class="modal-overlay" id="decisionModal">
    <div class="modal-card">
        <h3 style="font-size: 24px; font-weight: 800; margin-bottom: 30px; letter-spacing: -1px;">Review Requisition</h3>
        
        <div style="background: #f8fafc; border-radius: 20px; padding: 25px; margin-bottom: 30px; border: 1px solid #f1f5f9;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div><label style="font-size: 10px; font-weight: 800; color: #94a3b8;">EMPLOYEE</label><p id="decUser" style="font-weight: 800; color: #1e293b;">-</p></div>
                <div><label style="font-size: 10px; font-weight: 800; color: #94a3b8;">VOLUME</label><p id="decQty" style="font-weight: 800; color: var(--brand-teal); font-size: 18px;">-</p></div>
            </div>
            <div style="margin-top: 20px;"><label style="font-size: 10px; font-weight: 800; color: #94a3b8;">ASSET NOMENCLATURE</label><p id="decItem" style="font-weight: 800; color: #1e293b;">-</p></div>
        </div>

        <div style="margin-bottom: 30px;">
            <label style="font-size: 10px; font-weight: 800; color: #94a3b8;">DECISION REMARKS (OPTIONAL)</label>
            <input type="text" id="finalRemarks" class="modal-input" placeholder="e.g. Stock insufficient at the moment" style="margin-bottom: 0;">
        </div>

        <div style="display: flex; flex-direction: column; gap: 12px;">
            <div style="display: flex; gap: 12px;">
                <form id="approveForm" method="POST" style="flex:1">
                    @csrf
                    <input type="hidden" name="remarks" class="hidden-remarks">
                    <button type="submit" onclick="copyRemarks()" class="btn btn-success" style="width: 100%;">APPROVE</button>
                </form>
                <form id="rejectForm" method="POST" style="flex:1">
                    @csrf
                    <input type="hidden" name="remarks" class="hidden-remarks">
                    <button type="submit" onclick="copyRemarks()" class="btn btn-danger" style="width: 100%;">REJECT</button>
                </form>
            </div>
            <button type="button" onclick="document.getElementById('decisionModal').classList.remove('show')" class="btn btn-secondary">Discard & Close</button>
        </div>
    </div>
</div>

<script>
    function openDecisionModal(id, user, item, qty) {
        document.getElementById('decUser').innerText = user;
        document.getElementById('decItem').innerText = item;
        document.getElementById('decQty').innerText = qty + ' Units';
        document.getElementById('approveForm').action = "/requests/" + id + "/approve";
        document.getElementById('rejectForm').action = "/requests/" + id + "/reject";
        document.getElementById('decisionModal').classList.add('show');
    }

    function copyRemarks() {
        const val = document.getElementById('finalRemarks').value;
        const hiddens = document.querySelectorAll('.hidden-remarks');
        hiddens.forEach(h => h.value = val);
    }
</script>
@endsection
