@extends('layouts.app')
@section('title', 'Staff Dashboard')

@section('content')
<style>
    .stats-row { display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px; margin-bottom: 40px; }
    .stat-card {
        background: #fff; border-radius: 24px; padding: 32px;
        border: 1px solid rgba(0,0,0,0.02); display: flex; align-items: center; gap: 20px;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); transition: all 0.3s;
    }
    .stat-card:hover { transform: translateY(-5px); box-shadow: 0 20px 25px -5px rgba(0, 153, 167, 0.1); }
    
    .stat-icon {
        width: 60px; height: 60px; border-radius: 18px;
        display: flex; align-items: center; justify-content: center; font-size: 24px;
    }
    .teal-theme { background: #e0f2f1; color: var(--brand-teal); }
    .gold-theme { background: #fffdf2; color: #b45309; }
    .magenta-theme { background: #fdf2f8; color: var(--punb-magenta); }
    .red-theme { background: #fef2f2; color: #dc2626; }

    .stat-info h3 { font-size: 28px; font-weight: 800; color: #1e293b; letter-spacing: -1px; }
    .stat-info p { font-size: 11px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px; }

    /* FIXED HERO BANNER - Professional Contrast */
    .hero-banner {
        background: linear-gradient(135deg, var(--brand-teal), #006D77);
        border-radius: 30px; padding: 60px; text-align: center; color: #fff;
        margin-bottom: 40px; position: relative; overflow: hidden;
        box-shadow: 0 30px 60px -12px rgba(0, 153, 167, 0.25);
    }
    .hero-banner::after { content: 'STAFF'; position: absolute; font-size: 180px; font-weight: 900; opacity: 0.05; top: -50px; right: -20px; pointer-events: none; }
    .hero-banner h3 { font-size: 36px; font-weight: 800; margin-bottom: 15px; letter-spacing: -1.5px; }
    .hero-banner p { color: rgba(255,255,255,0.85); font-size: 18px; margin-bottom: 35px; font-weight: 500; }
    
    .cta-button { 
        background: #ffffff; color: var(--brand-teal); padding: 18px 40px; border-radius: 16px; 
        font-weight: 800; text-decoration: none; display: inline-flex; align-items: center; gap: 12px; 
        transition: 0.3s; box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    .cta-button:hover { transform: translateY(-3px) scale(1.05); box-shadow: 0 20px 40px rgba(0,0,0,0.15); background: #f8fafc; }
</style>

<div class="stats-row">
    <div class="stat-card">
        <div class="stat-icon teal-theme">📦</div>
        <div class="stat-info">
            <p>Asset Classes</p>
            <h3>{{ $availableItems }}</h3>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon gold-theme">⏳</div>
        <div class="stat-info">
            <p>Pending Review</p>
            <h3>{{ $pendingCount }}</h3>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon teal-theme">✅</div>
        <div class="stat-info">
            <p>Processed</p>
            <h3>{{ $approvedCount }}</h3>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon red-theme">❌</div>
        <div class="stat-info">
            <p>Declined</p>
            <h3>{{ $rejectedCount }}</h3>
        </div>
    </div>
</div>

<div class="hero-banner">
    <h3>Institutional Resource Requisition</h3>
    <p>Seamlessly request stationery and operational resources through our centralized portal.</p>
    <a href="{{ route('requests.catalog') }}" class="cta-button">Browse Official Catalog <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg></a>
</div>

<div class="card" style="padding: 0; overflow: hidden; border-radius: 24px;">
    <div style="padding: 24px 40px; border-bottom: 1px solid #f1f5f9;">
        <h2 style="font-size: 18px; font-weight: 800; color: #1e293b;">Submission History Ledger</h2>
    </div>
    <div class="table-responsive">
        <table style="width: 100%;">
            <thead>
                <tr>
                    <th>Resource Nomenclature</th>
                    <th>Quantity Requested</th>
                    <th>Processing Status</th>
                    <th>Admin Feedback</th>
                    <th>Timestamp</th>
                </tr>
            </thead>
            <tbody>
                @forelse($myRequests->take(10) as $req)
                <tr>
                    <td style="padding-left: 40px; font-weight: 800; color: #1e293b;">{{ $req->item->item_name ?? 'N/A' }}</td>
                    <td style="font-weight: 800; color: var(--brand-teal);">{{ $req->quantity_requested }} units</td>
                    <td style="font-weight: 800; font-size: 13px;">
                        @if($req->status == 'Pending')
                            <span class="badge" style="background: #fffbeb; color: #b45309; border: 1px solid #fef3c7;">PENDING REVIEW</span>
                        @elseif($req->status == 'Approved')
                            <span class="badge" style="background: #ecfdf5; color: #059669; border: 1px solid #d1fae5;">APPROVED</span>
                        @else
                            <span class="badge" style="background: #fef2f2; color: #dc2626; border: 1px solid #fee2e2;">REJECTED</span>
                        @endif
                    </td>
                    <td style="font-weight: 700; color: #64748b; font-size: 13px;">
                        {{ $req->remarks ?? '---' }}
                    </td>
                    <td style="color: #94a3b8; font-size: 12px; font-weight: 700;">{{ $req->created_at->format('d/m/Y • H:i A') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align: center; padding: 60px; color: #94a3b8; font-weight: 800;">NO REQUISITION RECORDS FOUND</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
