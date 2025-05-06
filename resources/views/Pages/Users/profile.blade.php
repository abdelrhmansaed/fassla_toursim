@extends('Dashboard.layouts.master')

@section('content')
    <style>
        :root {
            --primary-color: #4e73df;
            --primary-light: rgba(78, 115, 223, 0.1);
            --primary-dark: #2e59d9;
            --secondary-color: #858796;
            --success-color: #1cc88a;
            --success-light: rgba(28, 200, 138, 0.1);
            --info-color: #36b9cc;
            --info-light: rgba(54, 185, 204, 0.1);
            --warning-color: #f6c23e;
            --warning-light: rgba(246, 194, 62, 0.1);
            --danger-color: #e74a3b;
            --danger-light: rgba(231, 74, 59, 0.1);
            --light-color: #f8f9fc;
            --dark-color: #5a5c69;
            --gray-100: #f8f9fc;
            --gray-200: #eaecf4;
            --gray-300: #dddfeb;
            --gray-400: #d1d3e2;
            --gray-500: #b7b9cc;
            --gray-600: #858796;
            --gray-700: #6e707e;
            --gray-800: #5a5c69;
            --border-radius: 0.35rem;
            --border-radius-lg: 0.5rem;
            --box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            --box-shadow-sm: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            --transition: all 0.2s ease-in-out;
        }
        .profile-stats-container {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-top: 1.5rem;
        }

        /* تحسينات لعناصر حالة الرحلة */
        .trip-status-badge {
            padding: 0.35rem 0.75rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 80px;
        }

        /* ألوان الحالات */
        .trip-status-badge.confirmed {
            background-color: rgba(40, 167, 69, 0.1);
            color: #28a745;
        }

        .trip-status-badge.canceled {
            background-color: rgba(220, 53, 69, 0.1);
            color: #dc3545;
        }

        .trip-status-badge.waiting_payment {
            background-color: rgba(255, 193, 7, 0.1);
            color: #ffc107;
        }

        .trip-status-badge.pending {
            background-color: rgba(108, 117, 125, 0.1);
            color: #6c757d;
        }
        .stat-card {
            flex: 1;
            min-width: 120px;
            background-color: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(5px);
            border-radius: var(--border-radius);
            padding: 1rem;
            text-align: center;
            transition: var(--transition);
        }

        .stat-card:hover {
            background-color: rgba(255, 255, 255, 0.3);
            transform: translateY(-3px);
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }

        .stat-label {
            font-size: 0.85rem;
            opacity: 0.9;
        }
        /* تحسينات عامة */
        body {
            font-family: 'Tajawal', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--gray-100);
            color: var(--gray-800);
            line-height: 1.6;
        }

        .container-fluid {
            padding-left: 1.5rem;
            padding-right: 1.5rem;
        }

        /* كروت متميزة */
        .card {
            border: none;
            border-radius: var(--border-radius-lg);
            box-shadow: var(--box-shadow-sm);
            transition: var(--transition);
            background-color: white;
            overflow: hidden;
            margin-bottom: 1.5rem;
        }

        .card:hover {
            box-shadow: var(--box-shadow);
            transform: translateY(-3px);
        }

        .card-header {
            background-color: white;
            border-bottom: 1px solid var(--gray-200);
            padding: 1rem 1.25rem;
            font-weight: 600;
        }

        .card-header h5 {
            font-size: 1.1rem;
            margin-bottom: 0;
        }

        /* هيدر البروفايل */
        .profile-header {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            border-radius: var(--border-radius-lg);
            box-shadow: var(--box-shadow);
            overflow: hidden;
            position: relative;
            margin-bottom: 2rem;
            padding: 2rem;
            color: white;
        }

        .profile-header h2 {
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .profile-avatar {
            width: 120px;
            height: 120px;
            border: 4px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            transition: var(--transition);
            background-color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            font-weight: 700;
            color: var(--primary-color);
        }

        /* إحصائيات البروفايل */
        .stat-item {
            background-color: rgba(255, 255, 255, 0.15);
            border-radius: var(--border-radius);
            padding: 1rem;
            text-align: center;
            transition: var(--transition);
            margin-bottom: 1rem;
        }

        .stat-item h5 {
            font-weight: 700;
            margin-bottom: 0.25rem;
        }

        .stat-item small {
            opacity: 0.8;
        }

        /* تحسينات التبويبات */
        .nav-tabs {
            border-bottom: 1px solid var(--gray-200);
            margin-bottom: 1.5rem;
        }

        .nav-tabs .nav-link {
            border: none;
            color: var(--gray-600);
            font-weight: 600;
            padding: 0.75rem 1.25rem;
            transition: var(--transition);
            border-radius: 0;
            margin-bottom: -1px;
        }

        .nav-tabs .nav-link:hover {
            color: var(--primary-color);
            border-color: transparent;
        }

        .nav-tabs .nav-link.active {
            color: var(--primary-color);
            background: transparent;
            border-bottom: 2px solid var(--primary-color);
        }

        /* تحسينات الجداول */
        .table {
            --bs-table-bg: transparent;
            --bs-table-striped-bg: var(--gray-100);
            --bs-table-hover-bg: var(--primary-light);
            margin-bottom: 0;
        }

        .table thead th {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            color: var(--gray-600);
            border-bottom-width: 1px;
            padding: 0.75rem 1.25rem;
        }

        .table td {
            padding: 1rem 1.25rem;
            vertical-align: middle;
        }

        /* بطاقات المعاملات */
        .transaction-card {
            border-left: 3px solid;
            transition: var(--transition);
            border-radius: var(--border-radius);
            padding: 1rem;
            margin-bottom: 1rem;
            background-color: white;
            box-shadow: var(--box-shadow-sm);
        }

        .transaction-card:hover {
            transform: translateX(5px);
            box-shadow: var(--box-shadow);
        }

        /* أيقونات الرحلات */
        .trip-type-icon {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: var(--border-radius);
            background-color: var(--primary-light);
            color: var(--primary-color);
        }

        /* شريط الحالة */
        .badge {
            font-weight: 600;
            padding: 0.35rem 0.65rem;
            border-radius: 50px;
        }

        /* تأثيرات الأزرار */
        .btn {
            transition: var(--transition);
            font-weight: 600;
            padding: 0.5rem 1.25rem;
            border-radius: var(--border-radius);
        }

        .btn-sm {
            padding: 0.25rem 0.75rem;
            font-size: 0.875rem;
        }

        .btn-outline-primary {
            border-color: var(--primary-color);
            color: var(--primary-color);
        }

        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            color: white;
        }

        /* تأثيرات النص */
        .text-gradient {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .performance-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .performance-card:hover {
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        /* تنسيقات الرسم البياني */
        .chart-container {
            position: relative;
        }

        .chart-center-label {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            pointer-events: none;
        }

        /* تنسيقات شريط التقدم */
        .progress {
            border-radius: 10px;
            background-color: #f0f2f5;
        }

        .progress-bar {
            border-radius: 10px;
            transition: width 0.6s ease;
        }

        /* تنسيقات صناديق الإحصائيات */
        .stat-box {
            transition: all 0.3s ease;
            background-color: #f8f9fa;
        }

        .stat-box:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .stat-box.confirmed {
            border-top: 3px solid #28a745;
        }

        .stat-box.canceled {
            border-top: 3px solid #dc3545;
        }

        .stat-box.pending {
            border-top: 3px solid #ffc107;
        }

        /* ألوان الحالات */
        .bg-success-light {
            background-color: rgba(40, 167, 69, 0.1);
        }

        .bg-warning-light {
            background-color: rgba(255, 193, 7, 0.1);
        }

        .bg-danger-light {
            background-color: rgba(220, 53, 69, 0.1);
        }

        /* أيقونة البطاقة */
        .icon-shape {
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .bg-primary-light {
            background-color: rgba(67, 97, 238, 0.1);
        }
        /* تحسينات للهواتف */
        @media (max-width: 768px) {
            .container-fluid {
                padding-left: 1rem;
                padding-right: 1rem;
            }

            .profile-header {
                text-align: center;
                padding: 1.5rem;
            }

            .profile-avatar {
                width: 100px;
                height: 100px;
                font-size: 2.5rem;
                margin: 0 auto 1rem;
            }

            .nav-tabs .nav-link {
                padding: 0.5rem 0.75rem;
                font-size: 0.85rem;
            }
        }

        /* تأثيرات متقدمة */
        .hover-scale {
            transition: var(--transition);
        }

        .hover-scale:hover {
            transform: scale(1.03);
        }

        .icon-shape {
            width: 3rem;
            height: 3rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }

        /* تخصيص الخط الزمني */
        .timeline {
            position: relative;
            padding-left: 1rem;
        }

        .timeline::before {
            content: '';
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0.5rem;
            width: 2px;
            background-color: var(--gray-200);
        }

        .timeline-item {
            position: relative;
            padding-bottom: 1.5rem;
            padding-left: 2rem;
        }

        .timeline-icon {
            position: absolute;
            left: 0;
            top: 0;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: var(--primary-light);
            color: var(--primary-color);
        }

        /* تخصيص العناصر الداخلية */
        .list-group-item {
            border-color: var(--gray-200);
        }

        /* تخصيص العناصر النصية */
        .text-muted {
            color: var(--gray-600) !important;
        }

        /* تخصيص العناصر الرسومية */
        .chart-container {
            position: relative;
            height: 250px;
        }
    </style>
    <div class="container-fluid py-4">
        <!-- Profile Header -->
        <div class="profile-header p-4 text-white mb-4">
            <div class="row align-items-center">
                <div class="col-md-4 text-center text-md-start">
                    <div class="d-inline-block position-relative">
                        <div class="profile-avatar bg-white rounded-circle d-flex align-items-center justify-content-center mb-3">
                            <span class="text-primary fw-bold display-5">{{ substr($user->name, 0, 1) }}</span>
                        </div>
                        @if($user->role === 'agent')
                            <span class="position-absolute bottom-0 start-50 translate-x-50 bg-success rounded-pill px-3 py-1 small fw-bold">
                            <i class="fas fa-user-tie me-1"></i> مندوب
                        </span>
                        @else
                            <span class="position-absolute bottom-0 start-50 translate-x-50 bg-info rounded-pill px-3 py-1 small fw-bold">
                            <i class="fas fa-building me-1"></i> مزود خدمة
                        </span>
                        @endif
                    </div>
                </div>
                <div class="col-md-8 mt-4 mt-md-0">
                    <div class="d-flex flex-column h-100">
                        <div class="mb-auto">
                            <h2 class="fw-bold mb-1">{{ $user->name }}</h2>
                            <p class="mb-2 opacity-75">
                                <i class="fas fa-envelope me-2"></i> {{ $user->email }}
                            </p>
                            @if($user->phone)
                                <p class="mb-0 opacity-75">
                                    <i class="fas fa-phone me-2"></i> {{ $user->phone }}
                                </p>
                            @endif
                        </div>
                        <div class="profile-stats-container">
                            <div class="stat-card">
                                <div class="stat-value">{{ $stats['total'] }}</div>
                                <div class="stat-label">إجمالي الرحلات</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-value">{{ $stats['confirmed'] }}</div>
                                <div class="stat-label">مؤكدة</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-value">{{ $stats['canceled'] }}</div>
                                <div class="stat-label">ملغية</div>
                            </div>
                            @if($user->role === 'agent')
                                <div class="stat-card">
                                    <div class="stat-value">{{ number_format($stats['profit'], 2) }} ج.م</div>
                                    <div class="stat-label">إجمالي الربح</div>
                                </div>
                            @endif
                        </div>                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="row">
            <!-- Left Column -->
            <div class="col-lg-4">
                <!-- User Details Card -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom-0 pb-0">
                        <h5 class="fw-bold mb-0">
                            <i class="fas fa-info-circle me-2 text-primary"></i> معلومات أساسية
                        </h5>
                    </div>
                    <div class="card-body pt-0">
                        <div class="list-group list-group-flush">
                            <div class="list-group-item border-0 px-0 py-3 d-flex align-items-center">
                                <div class="icon icon-shape bg-light-primary text-primary rounded-circle me-3">
                                    <i class="fas fa-id-card"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">رقم الهوية</small>
                                    <p class="mb-0 fw-semibold">{{ $user->national_id ?? 'غير متوفر' }}</p>
                                </div>
                            </div>
                            <div class="list-group-item border-0 px-0 py-3 d-flex align-items-center">
                                <div class="icon icon-shape bg-light-primary text-primary rounded-circle me-3">
                                    <i class="fas fa-birthday-cake"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">العمر</small>
                                    <p class="mb-0 fw-semibold">{{ $user->age ?? 'غير محدد' }}</p>
                                </div>
                            </div>
                            <div class="list-group-item border-0 px-0 py-3 d-flex align-items-center">
                                <div class="icon icon-shape bg-light-primary text-primary rounded-circle me-3">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">تاريخ التسجيل</small>
                                    <p class="mb-0 fw-semibold">{{ $user->created_at->format('Y-m-d') }}</p>
                                </div>
                            </div>
                            @if($user->role === 'agent' && $user->commission_percent)
                                <div class="list-group-item border-0 px-0 py-3 d-flex align-items-center">
                                    <div class="icon icon-shape bg-light-primary text-primary rounded-circle me-3">
                                        <i class="fas fa-percent"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">نسبة العمولة</small>
                                        <p class="mb-0 fw-semibold">{{ $user->commission_percent }}%</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Performance Stats -->
                <div class="card performance-card mb-4">
                    <div class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center p-4 pb-2">
                        <div class="d-flex align-items-center">
                            <div class="icon-shape bg-primary-light rounded-3 p-3 me-3">
                                <i class="fas fa-chart-pie text-primary fs-4"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-0 text-dark">أداء الرحلات</h5>
                                <p class="text-muted small mb-0">تحليل إحصائي لأداء الرحلات</p>
                            </div>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="chartDropdown" data-bs-toggle="dropdown">
                                <i class="far fa-calendar-alt me-1"></i> آخر 30 يوم
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item active" href="#">آخر 30 يوم</a></li>
                                <li><a class="dropdown-item" href="#">آخر 90 يوم</a></li>
                                <li><a class="dropdown-item" href="#">هذا الشهر</a></li>
                                <li><a class="dropdown-item" href="#">هذا العام</a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="card-body p-4 pt-0">
                        <div class="chart-container position-relative" style="height: 250px;">
                            <canvas id="performanceChart"></canvas>
                            <div class="chart-center-label">
                                <h3 class="mb-0 fw-bold">{{ $stats['total'] }}</h3>
                                <small class="text-muted">إجمالي الرحلات</small>
                            </div>
                        </div>

                        <div class="performance-stats mt-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0 fw-bold">معدل النجاح</h6>
                                @php
                                    $successRate = $stats['total'] > 0
                                        ? round(($stats['confirmed'] / $stats['total']) * 100)
                                        : 0;
                                    $rateColor = $successRate > 70 ? 'success' : ($successRate > 40 ? 'warning' : 'danger');
                                @endphp
                                <span class="badge bg-{{ $rateColor }}-light text-{{ $rateColor }} fs-6">
                    {{ $successRate }}%
                </span>
                            </div>

                            <div class="progress mb-4" style="height: 8px;">
                                <div class="progress-bar bg-{{ $rateColor }}"
                                     role="progressbar"
                                     style="width: {{ $successRate }}%"
                                     aria-valuenow="{{ $successRate }}"
                                     aria-valuemin="0"
                                     aria-valuemax="100">
                                </div>
                            </div>

                            <div class="row text-center">
                                <div class="col-4">
                                    <div class="stat-box confirmed p-3 rounded-3">
                                        <h4 class="fw-bold mb-1">{{ $stats['confirmed'] }}</h4>
                                        <small class="text-muted">مؤكدة</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="stat-box canceled p-3 rounded-3">
                                        <h4 class="fw-bold mb-1">{{ $stats['canceled'] }}</h4>
                                        <small class="text-muted">ملغية</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="stat-box pending p-3 rounded-3">
                                        <h4 class="fw-bold mb-1">{{ $stats['total'] - $stats['confirmed'] - $stats['canceled'] }}</h4>
                                        <small class="text-muted">قيد الانتظار</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>



            </div>

            <!-- Right Column -->
            <div class="col-lg-8">
                <!-- Navigation Tabs -->
                <ul class="nav nav-tabs mb-4" id="profileTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="trips-tab" data-bs-toggle="tab" data-bs-target="#trips" type="button" role="tab">
                            <i class="fas fa-route me-2"></i> الرحلات
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="transactions-tab" data-bs-toggle="tab" data-bs-target="#transactions" type="button" role="tab">
                            <i class="fas fa-exchange-alt me-2"></i> المعاملات
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="activity-tab" data-bs-toggle="tab" data-bs-target="#activity" type="button" role="tab">
                            <i class="fas fa-history me-2"></i> النشاط
                        </button>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content" id="profileTabsContent">
                    <!-- Trips Tab -->
                    <div class="tab-pane fade show active" id="trips" role="tabpanel">
                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-white border-bottom-0 pb-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="fw-bold mb-0">سجل الرحلات</h5>
                                    <div class="d-flex">
                                        <div class="input-group input-group-sm me-2" style="width: 200px;">
                                            <span class="input-group-text bg-transparent"><i class="fas fa-calendar-alt"></i></span>
                                            <input type="text" class="form-control form-control-sm datepicker" placeholder="فلترة بالتاريخ">
                                        </div>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="statusFilter" data-bs-toggle="dropdown">
                                                <i class="fas fa-filter me-1"></i> الحالة
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><a class="dropdown-item" href="#">الكل</a></li>
                                                <li><a class="dropdown-item" href="#">مؤكدة</a></li>
                                                <li><a class="dropdown-item" href="#">قيد الانتظار</a></li>
                                                <li><a class="dropdown-item" href="#">ملغية</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="bg-light">
                                        <tr>
                                            <th class="ps-4">#</th>
                                            <th>نوع الرحلة</th>
                                            <th class="text-center">الحالة</th>
                                            <th class="text-center">التكلفة</th>
                                            <th class="text-center">العمولة</th>
                                            <th class="text-center">التاريخ</th>
                                            <th class="text-end pe-4">الإجراءات</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($trips as $index => $trip)
                                            <tr>
                                                <td class="ps-4 fw-semibold">{{ $index + 1 }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center">

                                                        <div>
                                                            <h6 class="mb-0">{{ $trip->tripType->type ?? '-' }}</h6>
                                                            <small class="text-muted">{{ $trip->subTripType->type ?? '' }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    @if($trip->status == 'confirmed')
                                                        <span class="trip-status-badge confirmed">
            <i class="fas fa-check-circle me-1"></i> مؤكدة
        </span>
                                                    @elseif($trip->status == 'canceled')
                                                        <span class="trip-status-badge canceled">
            <i class="fas fa-times-circle me-1"></i> ملغية
        </span>
                                                    @elseif($trip->status == 'waiting_payment')
                                                        <span class="trip-status-badge waiting_payment">
            <i class="fas fa-clock me-1"></i> انتظار الدفع
        </span>
                                                    @else
                                                        <span class="trip-status-badge pending">
            <i class="fas fa-clock me-1"></i> قيد الانتظار
        </span>
                                                    @endif
                                                </td>
                                                <td class="text-center fw-semibold">
                                                    {{ number_format($trip->total_price, 2) }}
                                                </td>
                                                <td class="text-center fw-semibold">
                                                    {{ number_format($trip->commission_value ?? 0, 2) }} ج.م
                                                </td>
                                                <td class="text-center">
                                                    <div class="d-flex flex-column">
                                                        <span class="fw-semibold">{{ $trip->created_at->format('Y-m-d') }}</span>
                                                        <small class="text-muted">{{ $trip->created_at->format('h:i A') }}</small>
                                                    </div>
                                                </td>
                                                <td class="text-end pe-4">
                                                    <button class="btn btn-sm btn-outline-primary rounded-circle" data-bs-toggle="tooltip" title="عرض التفاصيل">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center py-5 text-muted">
                                                    <i class="fas fa-exclamation-circle fa-2x mb-3"></i>
                                                    <p class="mb-0">لا توجد رحلات مسجلة حتى الآن</p>
                                                </td>
                                            </tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                @if($trips->hasPages())
                                    <div class="card-footer bg-white border-top-0 pt-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="text-muted small">
                                                عرض <span class="fw-semibold">{{ $trips->firstItem() }}</span> إلى
                                                <span class="fw-semibold">{{ $trips->lastItem() }}</span> من
                                                <span class="fw-semibold">{{ $trips->total() }}</span> رحلات
                                            </div>
                                            <div>
                                                {{ $trips->links('pagination::bootstrap-4') }}
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Transactions Tab -->
                    <div class="tab-pane fade" id="transactions" role="tabpanel">
                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-white border-bottom-0 pb-0">
                                <h5 class="fw-bold mb-0">سجل المعاملات المالية</h5>
                            </div>
                            <div class="card-body p-0">
                                @php
                                    $transactions = \App\Models\Transaction::where('agent_id', $user->id)
                                        ->with('tripRequestDetail.tripRequest')
                                        ->latest()
                                        ->take(10)
                                        ->get();
                                @endphp

                                @forelse($transactions as $transaction)
                                    <div class="transaction-card border-start border-{{ $transaction->credit ? 'success' : 'primary' }} mb-3 p-3 bg-light-{{ $transaction->credit ? 'success' : 'primary' }}">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-1 fw-bold">
                                                    {{ $transaction->tripRequestDetail->tripRequest->booking_number ?? 'N/A' }}
                                                </h6>
                                                <p class="mb-0 small text-muted">
                                                    <i class="far fa-clock me-1"></i>
                                                    {{ $transaction->created_at->format('Y-m-d h:i A') }}
                                                </p>
                                            </div>
                                            <div class="text-end">
                                                @if($transaction->credit)
                                                    <span class="badge bg-success bg-opacity-10 text-success mb-1">
                                                <i class="fas fa-arrow-down me-1"></i> دائن
                                            </span>
                                                    <h5 class="mb-0 fw-bold text-success">
                                                        +{{ number_format($transaction->credit, 2) }} ج.م
                                                    </h5>
                                                @else
                                                    <span class="badge bg-primary bg-opacity-10 text-primary mb-1">
                                                <i class="fas fa-arrow-up me-1"></i> مدين
                                            </span>
                                                    <h5 class="mb-0 fw-bold text-primary">
                                                        -{{ number_format($transaction->debit, 2) }} ج.م
                                                    </h5>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-5 text-muted">
                                        <i class="fas fa-exchange-alt fa-2x mb-3"></i>
                                        <p class="mb-0">لا توجد معاملات مالية مسجلة</p>
                                    </div>
                                @endforelse

                                @if($transactions->count() > 0)
                                    <div class="text-center mt-3">
                                        <a href="#" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-list me-1"></i> عرض جميع المعاملات
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Activity Tab -->
                    <div class="tab-pane fade" id="activity" role="tabpanel">
                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-white border-bottom-0 pb-0">
                                <h5 class="fw-bold mb-0">سجل النشاطات</h5>
                            </div>
                            <div class="card-body">
                                <div class="timeline">
                                    @for($i = 0; $i < 5; $i++)
                                        <div class="timeline-item">
                                            <div class="timeline-icon bg-light-primary text-primary">
                                                <i class="fas fa-route"></i>
                                            </div>
                                            <div class="timeline-content">
                                                <div class="d-flex justify-content-between">
                                                    <h6 class="mb-1 fw-bold">رحلة جديدة</h6>
                                                    <small class="text-muted">2 ساعة مضت</small>
                                                </div>
                                                <p class="mb-0 small text-muted">
                                                    قام {{ $user->name }} بإنشاء رحلة جديدة برقم حجز #TRP-{{ rand(1000, 9999) }}
                                                </p>
                                            </div>
                                        </div>
                                    @endfor
                                </div>

                                <div class="text-center mt-3">
                                    <a href="#" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-history me-1"></i> عرض المزيد
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Performance Chart
        const performanceCtx = document.getElementById('performanceChart').getContext('2d');
        const performanceChart = new Chart(performanceCtx, {
            type: 'doughnut',
            data: {
                labels: ['مؤكدة', 'ملغية', 'قيد الانتظار'],
                datasets: [{
                    data: [
                        {{ $stats['confirmed'] }},
                        {{ $stats['canceled'] }},
                        {{ $stats['total'] - $stats['confirmed'] - $stats['canceled'] }}
                    ],
                    backgroundColor: [
                        'rgba(40, 167, 69, 0.8)',
                        'rgba(220, 53, 69, 0.8)',
                        'rgba(255, 193, 7, 0.8)'
                    ],
                    borderColor: [
                        'rgba(40, 167, 69, 1)',
                        'rgba(220, 53, 69, 1)',
                        'rgba(255, 193, 7, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        rtl: true,
                        labels: {
                            usePointStyle: true,
                            padding: 20,
                            font: {
                                family: 'Tajawal, sans-serif'
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                let value = context.raw || 0;
                                let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                let percentage = Math.round((value / total) * 100);
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });

        // Initialize tooltips
        $(function () {
            $('[data-bs-toggle="tooltip"]').tooltip();
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('performanceChart').getContext('2d');
            const chart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['مؤكدة', 'ملغية', 'قيد الانتظار'],
                    datasets: [{
                        data: [
                            {{ $stats['confirmed'] }},
                            {{ $stats['canceled'] }},
                            {{ $stats['total'] - $stats['confirmed'] - $stats['canceled'] }}
                        ],
                        backgroundColor: [
                            'rgba(40, 167, 69, 0.8)',
                            'rgba(220, 53, 69, 0.8)',
                            'rgba(255, 193, 7, 0.8)'
                        ],
                        borderColor: [
                            'rgba(40, 167, 69, 1)',
                            'rgba(220, 53, 69, 1)',
                            'rgba(255, 193, 7, 1)'
                        ],
                        borderWidth: 1,
                        cutout: '75%'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            rtl: true,
                            labels: {
                                usePointStyle: true,
                                padding: 20,
                                font: {
                                    family: 'Tajawal, sans-serif'
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.label || '';
                                    let value = context.raw || 0;
                                    let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    let percentage = Math.round((value / total) * 100);
                                    return `${label}: ${value} (${percentage}%)`;
                                }
                            }
                        }
                    },
                    animation: {
                        animateScale: true,
                        animateRotate: true
                    }
                }
            });
        });
    </script>
@endpush
