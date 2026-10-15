<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — Kendaraan App</title>
    <meta name="description" content="Sistem Pemesanan & Monitoring Kendaraan Perusahaan Tambang Nikel">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <!-- DataTables -->
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">

    <style>
        :root {
            --primary: #1e3a5f;
            --primary-light: #2d5490;
            --secondary: #0d9488;
            --accent: #f59e0b;
            --danger: #ef4444;
            --success: #10b981;
            --sidebar-width: 260px;
            --sidebar-bg: #0f2440;
            --header-height: 64px;
        }

        * { box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: #f1f5f9;
            color: #1e293b;
            margin: 0;
        }

        /* ── Sidebar ── */
        #sidebar {
            position: fixed;
            top: 0; left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--sidebar-bg);
            overflow-y: auto;
            z-index: 1000;
            transition: transform .3s ease;
        }

        #sidebar .sidebar-brand {
            display: flex; align-items: center; gap: 12px;
            padding: 20px 24px;
            border-bottom: 1px solid rgba(255,255,255,.08);
        }
        #sidebar .sidebar-brand .brand-icon {
            width: 40px; height: 40px;
            background: linear-gradient(135deg, var(--secondary), #0f766e);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px; color: #fff;
        }
        #sidebar .brand-text { color: #fff; font-weight: 700; font-size: 15px; line-height: 1.2; }
        #sidebar .brand-text small { font-weight: 400; font-size: 11px; color: #94a3b8; }

        #sidebar .nav-section-label {
            padding: 16px 24px 6px;
            font-size: 10px; font-weight: 600;
            text-transform: uppercase; letter-spacing: .8px;
            color: #64748b;
        }

        #sidebar .nav-link {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 24px;
            color: #94a3b8;
            text-decoration: none;
            font-size: 14px; font-weight: 500;
            border-radius: 0;
            transition: all .2s;
            position: relative;
        }
        #sidebar .nav-link i { font-size: 18px; min-width: 20px; }
        #sidebar .nav-link:hover { color: #fff; background: rgba(255,255,255,.06); }
        #sidebar .nav-link.active {
            color: #fff;
            background: rgba(13,148,136,.15);
        }
        #sidebar .nav-link.active::before {
            content: '';
            position: absolute; left: 0; top: 0; bottom: 0;
            width: 3px; background: var(--secondary);
            border-radius: 0 3px 3px 0;
        }
        #sidebar .nav-link .badge { margin-left: auto; font-size: 11px; }

        /* ── Main ── */
        #main {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex; flex-direction: column;
        }

        /* ── Topbar ── */
        #topbar {
            position: sticky; top: 0; z-index: 900;
            height: var(--header-height);
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            padding: 0 28px;
            display: flex; align-items: center; justify-content: space-between;
            box-shadow: 0 1px 3px rgba(0,0,0,.06);
        }
        #topbar .page-title { font-size: 18px; font-weight: 600; color: var(--primary); }

        .avatar {
            width: 36px; height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-light), var(--secondary));
            color: #fff; font-weight: 600; font-size: 14px;
            display: flex; align-items: center; justify-content: center;
        }

        /* ── Content ── */
        .content-area { padding: 28px; flex: 1; }

        /* ── Cards ── */
        .stat-card {
            background: #fff;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0,0,0,.06);
            border: 1px solid #e2e8f0;
            transition: transform .2s, box-shadow .2s;
        }
        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,.1); }
        .stat-card .icon-box {
            width: 52px; height: 52px; border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 24px; margin-bottom: 16px;
        }
        .stat-card .stat-value { font-size: 32px; font-weight: 700; color: var(--primary); }
        .stat-card .stat-label { font-size: 13px; color: #64748b; font-weight: 500; }

        /* ── Table Card ── */
        .card {
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            box-shadow: 0 1px 3px rgba(0,0,0,.06);
        }
        .card-header {
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            border-radius: 16px 16px 0 0 !important;
            padding: 16px 24px;
            font-weight: 600; font-size: 15px;
        }

        /* ── Badges ── */
        .badge { font-weight: 500; font-size: 12px; padding: 4px 10px; border-radius: 6px; }

        /* ── Buttons ── */
        .btn-primary { background: var(--primary); border-color: var(--primary); }
        .btn-primary:hover { background: var(--primary-light); border-color: var(--primary-light); }
        .btn-teal { background: var(--secondary); border-color: var(--secondary); color: #fff; }
        .btn-teal:hover { background: #0f766e; color: #fff; }

        /* ── Responsive ── */
        @media (max-width: 768px) {
            #sidebar { transform: translateX(-100%); }
            #sidebar.show { transform: translateX(0); }
            #main { margin-left: 0; }
        }

        /* ── Alerts ── */
        .alert { border-radius: 10px; border: none; font-size: 14px; }

        /* ── Form ── */
        .form-control, .form-select {
            border-radius: 8px;
            border-color: #e2e8f0;
            font-size: 14px;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--secondary);
            box-shadow: 0 0 0 3px rgba(13,148,136,.15);
        }
        .form-label { font-weight: 500; font-size: 13px; color: #374151; margin-bottom: 6px; }

        /* ── DataTables override ── */
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter {
            padding: 14px 16px 10px;
            font-size: 13px;
        }
        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_paginate {
            padding: 10px 16px 14px;
            font-size: 13px;
            border-top: 1px solid #f1f5f9;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            border-radius: 6px !important;
            font-size: 13px !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: #1e3a5f !important;
            color: #fff !important;
            border-color: #1e3a5f !important;
        }
        table.dataTable thead th { white-space: nowrap; }
        table.dataTable tbody td { vertical-align: middle; }

        /* ── Icon-box: prevent icon overflow ── */
        .stat-card .icon-box { overflow: hidden; flex-shrink: 0; }
        .stat-card .icon-box i {
            font-size: 24px !important;
            line-height: 1;
            display: block;
            width: 24px; height: 24px;
            text-align: center;
        }

        /* ── Badge no-wrap ── */
        .badge { white-space: nowrap; }

        /* ── Status Timeline ── */
        .timeline { position: relative; }
        .timeline-item { display: flex; gap: 16px; margin-bottom: 20px; }
        .timeline-dot {
            width: 32px; height: 32px; min-width: 32px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 14px;
        }
        .timeline-content { flex: 1; }
    </style>

    @yield('styles')
</head>
<body>

<!-- Sidebar -->
<nav id="sidebar">
    <div class="sidebar-brand">
        <div class="brand-icon"><i class="bi bi-truck"></i></div>
        <div class="brand-text">
            Kendaraan App<br>
            <small>Sistem Pemesanan</small>
        </div>
    </div>

    <div class="nav-section-label">Menu Utama</div>
    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <i class="bi bi-grid-1x2-fill"></i> Dashboard
    </a>
    <a href="{{ route('bookings.index') }}" class="nav-link {{ request()->routeIs('bookings.*') ? 'active' : '' }}">
        <i class="bi bi-calendar-check-fill"></i> Pemesanan Kendaraan
        @php $pendingCount = auth()->user()->approvals()->where('status','pending')->count(); @endphp
        @if($pendingCount > 0)
            <span class="badge bg-danger">{{ $pendingCount }}</span>
        @endif
    </a>
    <a href="{{ route('reports.index') }}" class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
        <i class="bi bi-file-earmark-bar-graph-fill"></i> Laporan
    </a>

    @role('admin')
    <div class="nav-section-label">Master Data</div>
    <a href="{{ route('vehicles.index') }}" class="nav-link {{ request()->routeIs('vehicles.*') ? 'active' : '' }}">
        <i class="bi bi-truck-front-fill"></i> Kendaraan
    </a>
    <a href="{{ route('drivers.index') }}" class="nav-link {{ request()->routeIs('drivers.*') ? 'active' : '' }}">
        <i class="bi bi-person-badge-fill"></i> Driver
    </a>
    <a href="{{ route('locations.index') }}" class="nav-link {{ request()->routeIs('locations.*') ? 'active' : '' }}">
        <i class="bi bi-geo-alt-fill"></i> Lokasi
    </a>

    <div class="nav-section-label">Administrasi</div>
    <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
        <i class="bi bi-people-fill"></i> Pengguna
    </a>
    <a href="{{ route('activity-logs.index') }}" class="nav-link {{ request()->routeIs('activity-logs.*') ? 'active' : '' }}">
        <i class="bi bi-journal-text"></i> Activity Log
    </a>
    @endrole

    <div style="padding: 24px; margin-top: auto;">
        <div style="background: rgba(13,148,136,.1); border-radius: 12px; padding: 14px 16px;">
            <div style="font-size: 12px; color: #94a3b8;">Logged in as</div>
            <div style="font-size: 13px; font-weight: 600; color: #e2e8f0; margin-top: 2px;">{{ auth()->user()->name }}</div>
            <div style="font-size: 11px; color: #64748b; margin-top: 2px;">{{ auth()->user()->getRoleNames()->first() }}</div>
        </div>
    </div>
</nav>

<!-- Main -->
<div id="main">
    <!-- Topbar -->
    <div id="topbar">
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-sm btn-light d-md-none" id="sidebarToggle">
                <i class="bi bi-list" style="font-size: 20px;"></i>
            </button>
            <div class="page-title">@yield('page-title', 'Dashboard')</div>
        </div>

        <div class="d-flex align-items-center gap-3">
            <span class="text-muted d-none d-md-block" style="font-size: 13px;">
                <i class="bi bi-calendar3 me-1"></i>{{ now()->locale('id')->isoFormat('dddd, D MMMM Y') }}
            </span>
            <div class="dropdown">
                <button class="btn btn-light btn-sm d-flex align-items-center gap-2" data-bs-toggle="dropdown">
                    <div class="avatar">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
                    <span class="d-none d-md-block" style="font-size: 13px;">{{ auth()->user()->name }}</span>
                    <i class="bi bi-chevron-down" style="font-size: 11px;"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0" style="border-radius: 12px; min-width: 180px;">
                    <li><h6 class="dropdown-header">{{ auth()->user()->email }}</h6></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="dropdown-item text-danger" type="submit">
                                <i class="bi bi-box-arrow-right me-2"></i>Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="content-area">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <i class="bi bi-exclamation-circle-fill me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>
</div>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<!-- DataTables -->
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

<script>
    // Sidebar toggle mobile
    document.getElementById('sidebarToggle')?.addEventListener('click', function() {
        document.getElementById('sidebar').classList.toggle('show');
    });

    // Init DataTables
    document.querySelectorAll('.datatable').forEach(function(el) {
        $(el).DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
            },
            responsive: true,
            pageLength: 15,
        });
    });
</script>

@yield('scripts')
</body>
</html>
