<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=1280">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') | PUNB Inventory Management System</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --brand-teal: #0099A7;
            --brand-teal-soft: #f0fdfa;
            --bg-body: #f8fafc;
            --sidebar-width: 280px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background-color: var(--bg-body); color: #0f172a; overflow-x: hidden; }

        .app-container { display: flex; min-height: 100vh; min-width: 1200px; }

        /* SIDEBAR */
        .sidebar {
            width: var(--sidebar-width); background: #fff; border-right: 1px solid #f1f5f9; padding: 45px 30px;
            display: flex; flex-direction: column; position: fixed; height: 100vh; z-index: 1000;
        }

        .main-content { 
            flex: 1; margin-left: var(--sidebar-width); padding: 50px 70px; 
            width: calc(100% - var(--sidebar-width)); 
        }

        .logo-section { margin-bottom: 60px; text-align: center; }
        .logo-section img { width: 160px; height: auto; object-fit: contain; }

        .nav-label { font-size: 11px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 25px; padding-left: 15px; }
        .nav-link { 
            display: flex; align-items: center; gap: 15px; padding: 18px 22px; 
            border-radius: 16px; text-decoration: none; color: #64748b; font-weight: 700; transition: 0.25s; margin-bottom: 12px;
        }
        .nav-link:hover { background: #fbfcfd; color: var(--brand-teal); }
        .nav-link.active { background: var(--brand-teal); color: #fff; box-shadow: 0 10px 20px -10px rgba(0, 153, 167, 0.4); }

        /* TOPBAR & NOTI (MASTER DESIGN) */
        .topbar { display: flex; justify-content: flex-end; align-items: center; gap: 20px; margin-bottom: 40px; position: relative; z-index: 1001; }
        .noti-wrapper { position: relative; }
        .btn-noti { background: #fff; border: 1.5px solid #f1f5f9; width: 50px; height: 50px; border-radius: 14px; color: #64748b; cursor: pointer; transition: 0.2s; position: relative; display: flex; align-items: center; justify-content: center; }
        .btn-noti:hover { border-color: var(--brand-teal); color: var(--brand-teal); background: #fbfcfd; }
        
        .noti-badge { position: absolute; top: -5px; right: -5px; height: 22px; width: 22px; background: #ef4444; color: #fff; border-radius: 50%; border: 3px solid #fff; font-size: 11px; font-weight: 900; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 10px rgba(239, 68, 68, 0.3); }
        
        .noti-dropdown { position: absolute; top: 65px; right: 0; width: 380px; background: #fff; border-radius: 28px; box-shadow: 0 30px 60px -12px rgba(15, 23, 42, 0.2); border: 1px solid #f1f5f9; display: none; overflow: hidden; z-index: 2000; }
        .noti-dropdown.show { display: block; }

        .noti-section-title { font-size: 10px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 1.2px; padding: 15px 28px; background: #fbfcfd; border-bottom: 1px solid #f1f5f9; }
        .noti-item { padding: 18px 28px; border-bottom: 1px solid #f8fafc; cursor: pointer; transition: 0.2s; display: block; text-decoration: none; position: relative; }
        .noti-item:hover { background: #f1f5f9; }
        .noti-read { opacity: 0.5; filter: grayscale(1); border-left: 3px solid #cbd5e1; }

        /* UI ELEMENTS */
        .card { background: #fff; border-radius: 35px; border: 1px solid #f1f5f9; padding: 40px; margin-bottom: 35px; box-shadow: 0 12px 20px -5px rgba(15, 23, 42, 0.02); }
        .table-responsive { overflow-x: auto; border-radius: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; padding: 25px; font-size: 11px; font-weight: 800; color: #94a3b8; text-transform: uppercase; border-bottom: 2.5px solid #f8fafc; }
        td { padding: 25px; border-bottom: 1px solid #f8fafc; font-size: 15px; vertical-align: middle; }

        .btn { padding: 16px 32px; border-radius: 18px; font-weight: 800; border: none; cursor: pointer; transition: 0.25s; display: inline-flex; align-items: center; gap: 12px; font-size: 14px; text-decoration: none; }
        .btn-teal { background: var(--brand-teal); color: #fff; box-shadow: 0 4px 6px -1px rgba(0, 153, 167, 0.2); }
        .btn-teal:hover { transform: translateY(-3px); box-shadow: 0 12px 25px -5px rgba(0, 153, 167, 0.4); }
        .btn-success { background: #059669; color: #fff; box-shadow: 0 4px 6px -1px rgba(5, 150, 105, 0.2); }
        .btn-success:hover { background: #047857; transform: translateY(-3px); box-shadow: 0 12px 25px -5px rgba(5, 150, 105, 0.4); }
        .btn-danger { background: #dc2626; color: #fff; box-shadow: 0 4px 6px -1px rgba(220, 38, 38, 0.2); }
        .btn-danger:hover { background: #b91c1c; transform: translateY(-3px); box-shadow: 0 12px 25px -5px rgba(220, 38, 38, 0.4); }
        .btn-secondary { background: #f8fafc; color: #64748b; border: 1.5px solid #f1f5f9; }

        .badge { padding: 8px 16px; border-radius: 12px; font-size: 10px; font-weight: 800; text-transform: uppercase; display: inline-flex; align-items: center; justify-content: center; border: 1px solid transparent; }
        .badge-teal { background: var(--brand-teal-soft); color: var(--brand-teal); border-color: #ccfbf1; }
        .badge-success { background: #ecfdf5; color: #059669; border-color: #bbf7d0; }
        .badge-danger { background: #fef2f2; color: #dc2626; border-color: #fecaca; }

        .modal-overlay { position: fixed; inset: 0; background: rgba(15, 23, 42, 0.45); backdrop-filter: blur(15px); display: none; align-items: center; justify-content: center; z-index: 2000; padding: 25px; }
        .modal-overlay.show { display: flex; }
        .modal-card { background: #fff; width: 100%; max-width: 580px; border-radius: 40px; padding: 50px; box-shadow: 0 40px 60px -15px rgba(0,0,0,0.2); }
        .modal-input { width: 100%; padding: 20px; border-radius: 18px; border: 2.5px solid #f1f5f9; margin-bottom: 30px; outline: none; transition: 0.25s; font-size: 17px; font-weight: 600; background: #fbfcfd; display: block; }
        .modal-input:focus { border-color: var(--brand-teal); background: #fff; box-shadow: 0 0 0 6px var(--brand-teal-soft); }
    </style>
</head>
<body>

    <div class="app-container">
        <!-- SIDEBAR -->
        <aside class="sidebar">
            <div class="logo-section">
                <img src="https://www.punb.com.my/wp-content/uploads/2024/11/punb-logo.jpg" alt="PUNB LOGO">
            </div>

            <nav style="flex: 1;">
                <p class="nav-label">Core Operations</p>
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard*') ? 'active' : '' }}">
                    <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    Dashboard Overview
                </a>

                @if(Auth::user()->isAdmin())
                    <p class="nav-label" style="margin-top: 45px;">Institutional Admin</p>
                    <a href="{{ route('items.index') }}" class="nav-link {{ request()->routeIs('items.index') ? 'active' : '' }}">
                        <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-3m-9 0H4m11 0a2 2 0 101.414 1.414A2 2 0 0015 13zM9 13a2 2 0 101.414 1.414A2 2 0 009 13z"></path></svg>
                        Inventory Register
                    </a>
                    <a href="{{ route('requests.manage') }}" class="nav-link {{ request()->routeIs('requests.manage') ? 'active' : '' }}">
                        <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        Requests Center Center
                    </a>
                    <a href="{{ route('items.logs') }}" class="nav-link {{ request()->routeIs('items.logs') ? 'active' : '' }}">
                        <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Activity Audit Center
                    </a>
                @else
                    <p class="nav-label" style="margin-top: 45px;">Staff Services</p>
                    <a href="{{ route('requests.catalog') }}" class="nav-link {{ request()->routeIs('requests.catalog') ? 'active' : '' }}">
                        <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        Requests Center
                    </a>
                @endif
            </nav>

            <div style="margin-top: 45px;">
                <div style="padding: 24px; background: #fbfcfd; border-radius: 20px; border: 1px solid #f1f5f9; display: flex; align-items: center; gap: 15px; margin-bottom: 22px;">
                    <div style="width: 50px; height: 50px; background: var(--brand-teal); color: #fff; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 20px;">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div>
                        <div style="font-weight: 800; color: #0f172a; font-size: 14px;">{{ Auth::user()->name }}</div>
                        <div style="font-size: 10px; font-weight: 800; color: #94a3b8; text-transform: uppercase;">{{ Auth::user()->role }} Account Account</div>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-secondary" style="width: 100%; border-color: #fee2e2; color: #dc2626; background: #fff1f1;">Sign Out</button>
                </form>
            </div>
        </aside>

        <!-- MAIN -->
        <main class="main-content">
            <div class="topbar">
                <div style="flex: 1;">
                    <div style="font-size: 11px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 2.5px; margin-bottom: 4px;">PUNB Institutional Suite</div>
                    <div style="font-size: 26px; font-weight: 800; color: #0f172a; letter-spacing: -1.5px;">{{ request()->routeIs('dashboard') ? 'Dashboard Overview' : (request()->routeIs('items.*') ? 'Inventory Registry' : 'Requests Center') }}</div>
                </div>

                <div class="noti-wrapper">
                    @php 
                        if(Auth::user()->isAdmin()) {
                            // Fetch exactly what will be shown in the loop
                            $lowStockItems = \App\Models\Item::all()->filter(fn($i) => $i->isLowStock());
                            $pendingAll = \App\Models\InventoryRequest::where('status', 'Pending')->get();
                            
                            // Calculate count BASED on what IS VISUALLY SHOWN in the loop
                            $unreadItemsCount = $lowStockItems->count() + $pendingAll->whereNull('read_at')->count();
                        } else {
                            $staffAlerts = \App\Models\InventoryRequest::where('user_id', Auth::id())
                                ->where('status', '!=', 'Pending')
                                ->whereNull('read_at')
                                ->get();
                            $unreadItemsCount = $staffAlerts->count();
                        }
                    @endphp

                    <button onclick="toggleNoti()" class="btn-noti">
                        <svg width="26" height="26" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                        @if($unreadItemsCount > 0)
                            <span class="noti-badge" id="notiCountBadge">{{ $unreadItemsCount }}</span>
                        @endif
                    </button>

                    <div id="notiDropdown" class="noti-dropdown">
                        <div style="padding: 22px 28px; border-bottom: 1px solid #f1f5f9; background: #fff;">
                            <span style="font-weight: 800; color: #0f172a; font-size: 16px;">System Intelligence Center</span>
                        </div>
                        <div style="max-height: 480px; overflow-y: auto;">
                            
                            @if(Auth::user()->isAdmin())
                                <div class="noti-section-title">🔥 Urgent Action Center Center</div>
                                <!-- Low Stock -->
                                @foreach($lowStockItems as $item)
                                    <div class="noti-item" onclick="window.location='{{ route('items.index') }}'">
                                        <div style="font-weight: 800; color: #dc2626; font-size: 13px;">[URGENT] LOW STOCK STOCK</div>
                                        <div style="font-weight: 700; color: #1e293b; font-size: 14px;">{{ $item->item_name }}</div>
                                        <div style="font-size: 10px; color: #94a3b8; font-weight: 800;">Remaining: {{ $item->stock_quantity }} units</div>
                                    </div>
                                @endforeach

                                <!-- Unified Pending List -->
                                @foreach($pendingAll as $r)
                                    <div class="noti-item {{ $r->read_at ? 'noti-read' : '' }}" onclick="markReadAndGo({{ $r->request_id }}, '{{ route('requests.manage') }}')">
                                        <div style="font-weight: 800; color: var(--brand-teal); font-size: 11px;">{{ $r->read_at ? 'ACKNOWLEDGED' : 'NEW REQUISITION' }}</div>
                                        <div style="font-weight: 800; color: #1e293b; font-size: 14px;">{{ $r->user?->name }}</div>
                                        <div style="color: #64748b; font-size: 12px; font-weight: 700;">{{ $r->item?->item_name }} ({{ $r->quantity_requested }}x)</div>
                                    </div>
                                @endforeach

                                <div class="noti-section-title">⌛ History Archive</div>
                                @php $history = \App\Models\InventoryRequest::whereIn('status', ['Approved', 'Rejected'])->with(['user','item'])->latest()->take(5)->get(); @endphp
                                @foreach($history as $h)
                                    <div class="noti-item noti-read">
                                        <div style="display: flex; justify-content: space-between;">
                                            <div style="font-weight: 800; color: #94a3b8; font-size: 13px;">{{ $h->user?->name }}</div>
                                            <span class="badge {{ $h->status == 'Approved' ? 'badge-success' : 'badge-danger' }}" style="font-size: 8px; padding: 2px 6px;">{{ $h->status }}</span>
                                        </div>
                                        <div style="color: #cbd5e1; font-size: 12px;">{{ $h->item?->item_name }} ({{ $h->quantity_requested }}x)</div>
                                    </div>
                                @endforeach
                            @else
                                <!-- STAFF VIEW -->
                                <div class="noti-section-title">✨ Recent Status Updates</div>
                                @forelse($staffAlerts as $ns)
                                    <div class="noti-item" onclick="markReadAndGo({{ $ns->request_id }})">
                                        <div style="font-weight: 800; color: #059669; font-size: 11px;">REQUEST {{ strtoupper($ns->status) }}</div>
                                        <div style="font-weight: 800; color: #1e293b; font-size: 14px;">{{ $ns->item?->item_name }} ({{ $ns->quantity_requested }}x)</div>
                                        <div style="font-size: 10px; color: #94a3b8; margin-top: 4px;">{{ $ns->updated_at?->diffForHumans() }}</div>
                                    </div>
                                @empty
                                    <div style="padding: 20px; text-align: center; color: #94a3b8; font-size: 12px; font-weight: 700;">No new status updates</div>
                                @endforelse

                                <div class="noti-section-title">⌛ Acknowledged Updates</div>
                                @php $seenStaff = \App\Models\InventoryRequest::where('user_id', Auth::id())->whereNotNull('read_at')->latest()->take(5)->get(); @endphp
                                @foreach($seenStaff as $ss)
                                    <div class="noti-item noti-read">
                                        <div style="font-weight: 800; color: #94a3b8; font-size: 14px;">{{ $ss->item?->item_name }} ({{ $ss->quantity_requested }}x)</div>
                                        <div style="color: #cbd5e1; font-size: 11px;">Status: {{ $ss->status }}</div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            @yield('content')
        </main>
    </div>

    <script>
        function toggleNoti() {
            const dd = document.getElementById('notiDropdown');
            dd.classList.toggle('show');
        }

        async function markReadAndGo(id, redirectUrl = null) {
            try {
                let formData = new FormData();
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
                
                await fetch(`/notifications/${id}/read`, {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });

                if (redirectUrl) {
                    window.location.href = redirectUrl;
                } else {
                    location.reload();
                }
            } catch (err) {
                console.error("Click Failed:", err);
                if (redirectUrl) window.location.href = redirectUrl;
            }
        }

        window.onclick = function(e) {
            if (!e.target.closest('.noti-wrapper')) {
                document.getElementById('notiDropdown').classList.remove('show');
            }
        }
    </script>
</body>
</html>
