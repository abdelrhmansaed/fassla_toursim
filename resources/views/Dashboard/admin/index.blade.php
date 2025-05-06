@extends('Dashboard.layouts.master')
<style>
    :root {
        --primary-color: #3498db;
        --secondary-color: #2c3e50;
        --success-color: #2ecc71;
        --warning-color: #f39c12;
        --danger-color: #e74c3c;
        --info-color: #1abc9c;
        --light-color: #ecf0f1;
        --dark-color: #34495e;
    }

    body {
        font-family: 'Cairo', sans-serif;
        background-color: #f8fafc;
    }

    .dashboard-header {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        border-radius: 0.5rem;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .card {
        border: none;
        border-radius: 0.75rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    .card-header {
        background-color: white;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        font-weight: 600;
        padding: 1rem 1.5rem;
    }

    .card-body {
        padding: 1.5rem;
    }

    .stat-card {
        text-align: center;
        border-left: 4px solid;
    }

    .stat-card h5 {
        font-size: 1rem;
        color: #718096;
        margin-bottom: 0.5rem;
    }

    .stat-card h3 {
        font-size: 1.75rem;
        font-weight: 700;
        margin-bottom: 0;
    }

    .stat-card.total-trips {
        border-left-color: var(--primary-color);
    }

    .stat-card.confirmed-trips {
        border-left-color: var(--success-color);
    }

    .stat-card.agents {
        border-left-color: var(--warning-color);
    }

    .stat-card.providers {
        border-left-color: var(--info-color);
    }

    .stat-card.main-types {
        border-left-color: var(--danger-color);
    }

    .stat-card.sub-types {
        border-left-color: var(--secondary-color);
    }

    .filter-card {
        background-color: #f8fafc;
        border-radius: 0.5rem;
        padding: 1rem;
        margin-bottom: 1rem;
    }

    .list-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 1rem;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        transition: background-color 0.2s;
    }

    .list-item:hover {
        background-color: #f8fafc;
    }

    .scrollable-list {
        max-height: 400px;
        overflow-y: auto;
        scrollbar-width: thin;
    }

    .scrollable-list::-webkit-scrollbar {
        width: 6px;
    }

    .scrollable-list::-webkit-scrollbar-thumb {
        background-color: #cbd5e0;
        border-radius: 3px;
    }

    .btn-view {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
    }

    .chart-container {
        position: relative;
        height: 400px;
        width: 100%;
    }
</style>

@section('content')

    <div class="container-fluid py-4">
        <!-- Header Section -->
        <div class="dashboard-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-0"><i class="fas fa-tachometer-alt me-2"></i>لوحة التحكم الإدارية</h2>
                    <p class="mb-0">نظرة عامة على أداء النظام</p>
                </div>
                <div class="text-end">
                    <span class="badge bg-light text-dark">
                        <i class="far fa-calendar-alt me-1"></i>
                        {{ now()->format('d F Y') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row">
            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="card stat-card total-trips">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-center mb-2">
                            <div class="icon icon-shape bg-gradient-primary text-white rounded-circle shadow">
                                <i class="fas fa-route"></i>
                            </div>
                        </div>
                        <h5>إجمالي الرحلات</h5>
                        <h3>{{ $totalTrips }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="card stat-card confirmed-trips">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-center mb-2">
                            <div class="icon icon-shape bg-gradient-success text-white rounded-circle shadow">
                                <i class="fas fa-check-circle"></i>
                            </div>
                        </div>
                        <h5>الرحلات المؤكدة</h5>
                        <h3>{{ $confirmedTrips }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="card stat-card agents">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-center mb-2">
                            <div class="icon icon-shape bg-gradient-warning text-white rounded-circle shadow">
                                <i class="fas fa-user-tie"></i>
                            </div>
                        </div>
                        <h5>عدد المندوبين</h5>
                        <h3>{{ $agentsCount }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="card stat-card providers">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-center mb-2">
                            <div class="icon icon-shape bg-gradient-info text-white rounded-circle shadow">
                                <i class="fas fa-building"></i>
                            </div>
                        </div>
                        <h5>عدد المزودين</h5>
                        <h3>{{ $providersCount }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="card stat-card main-types">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-center mb-2">
                            <div class="icon icon-shape bg-gradient-danger text-white rounded-circle shadow">
                                <i class="fas fa-tags"></i>
                            </div>
                        </div>
                        <h5>الأنواع الرئيسية</h5>
                        <h3>{{ $mainTypesCount }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-xl-2 col-md-4 col-sm-6">
                <div class="card stat-card sub-types">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-center mb-2">
                            <div class="icon icon-shape bg-gradient-dark text-white rounded-circle shadow">
                                <i class="fas fa-tag"></i>
                            </div>
                        </div>
                        <h5>الأنواع الفرعية</h5>
                        <h3>{{ $subTypesCount }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row mt-4">
            <!-- Trip Status Chart -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">نسبة حالات الرحلات</h5>
                        <form method="GET" id="tripStatusFilterForm" class="mb-0">
                            <select name="status_range" id="status_range" class="form-select form-select-sm"
                                    style="width: 120px;" onchange="document.getElementById('tripStatusFilterForm').submit()">
                                <option value="monthly" {{ request('status_range', 'monthly') == 'monthly' ? 'selected' : '' }}>شهري</option>
                                <option value="weekly" {{ request('status_range') == 'weekly' ? 'selected' : '' }}>أسبوعي</option>
                                <option value="yearly" {{ request('status_range') == 'yearly' ? 'selected' : '' }}>سنوي</option>
                            </select>
                        </form>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <div id="tripStatusPie" style="height: 100%;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Financial Chart -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            الملخص المالي (حسب {{ $filter == 'weekly' ? 'الأسبوع' : ($filter == 'yearly' ? 'السنة' : 'الشهر') }})
                        </h5>
                        <form method="GET" id="financeFilterForm" class="mb-0">
                            <select name="range" id="range" class="form-select form-select-sm"
                                    style="width: 120px;" onchange="document.getElementById('financeFilterForm').submit()">
                                <option value="monthly" {{ request('range', 'monthly') == 'monthly' ? 'selected' : '' }}>شهري</option>
                                <option value="weekly" {{ request('range') == 'weekly' ? 'selected' : '' }}>أسبوعي</option>
                                <option value="yearly" {{ request('range') == 'yearly' ? 'selected' : '' }}>سنوي</option>
                            </select>
                        </form>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <div id="monthlyFinanceChart" style="height: 100%;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lists Row -->
        <div class="row mt-4">
            <!-- Agents List -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">المناديب <span class="badge bg-primary">{{ $agents->count() }}</span></h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="scrollable-list">
                            @foreach($agents as $agent)
                                <div class="list-item">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar me-3">
                                            <span class="avatar-initial rounded-circle bg-light-primary text-primary">
                                                {{ substr($agent->name, 0, 1) }}
                                            </span>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $agent->name }}</h6>
                                            <small class="text-muted">{{ $agent->email }}</small>
                                        </div>
                                    </div>
                                    <a href="{{ route('admin.agents.profile', $agent->id) }}"
                                       class="btn-view btn btn-sm btn-outline-primary"
                                       title="عرض التفاصيل">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Providers List -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">المزودين <span class="badge bg-success">{{ $providers->count() }}</span></h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="scrollable-list">
                            @foreach($providers as $provider)
                                <div class="list-item">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar me-3">
                                            <span class="avatar-initial rounded-circle bg-light-success text-success">
                                                {{ substr($provider->name, 0, 1) }}
                                            </span>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $provider->name }}</h6>
                                            <small class="text-muted">{{ $provider->email }}</small>
                                        </div>
                                    </div>
                                    <a href="{{ route('admin.providers.profile', $provider->id) }}"
                                       class="btn-view btn btn-sm btn-outline-success"
                                       title="عرض التفاصيل">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Pie Chart لحالات الرحلات
            var pieOptions = {
                chart: {
                    type: 'pie',
                    height: '100%',
                    parentHeightOffset: 0,
                    toolbar: { show: true }
                },
                series: [
                    {{ $tripStatusData['confirmed'] }},
                    {{ $tripStatusData['pending'] }},
                    {{ $tripStatusData['canceled'] }},
                    {{ $tripStatusData['waiting_payment'] }},
                ],
                labels: ['مؤكد', 'قيد الانتظار', 'ملغي', 'في انتظار الدفع'],
                colors: ['#2ecc71', '#f39c12', '#e74c3c', '#3498db'],
                legend: {
                    position: 'bottom',
                    horizontalAlign: 'center',
                    fontSize: '14px',
                    markers: { radius: 2 }
                },
                tooltip: {
                    y: {
                        formatter: val => `${val} رحلة`
                    }
                },
                dataLabels: {
                    enabled: true,
                    formatter: function (val, opts) {
                        return opts.w.config.series[opts.seriesIndex] + ' (' + val.toFixed(1) + '%)'
                    },
                    style: {
                        fontSize: '12px',
                        fontFamily: 'Cairo, sans-serif'
                    }
                },
                responsive: [{
                    breakpoint: 768,
                    options: {
                        chart: { height: 300 },
                        legend: { position: 'bottom' }
                    }
                }]
            };

            new ApexCharts(document.querySelector("#tripStatusPie"), pieOptions).render();

            // Financial Chart
            var financeChartOptions = {
                chart: {
                    type: 'bar',
                    height: '100%',
                    stacked: true,
                    parentHeightOffset: 0,
                    toolbar: { show: true }
                },
                series: [
                    {
                        name: 'سعر البيع',
                        data: {!! json_encode(array_column($reportData, 'sale')) !!}
                    },
                    {
                        name: 'التكلفة',
                        data: {!! json_encode(array_column($reportData, 'cost')) !!}
                    },
                    {
                        name: 'العمولة',
                        data: {!! json_encode(array_column($reportData, 'commission')) !!}
                    },
                    {
                        name: 'الخصم',
                        data: {!! json_encode(array_column($reportData, 'discount')) !!}
                    },
                    {
                        name: 'الربح',
                        data: {!! json_encode(array_column($reportData, 'profit')) !!}
                    },
                ],
                xaxis: {
                    categories: {!! json_encode(array_map(function($period) use ($filter) {
                        return match($filter) {
                            'weekly' => \Carbon\Carbon::parse($period)->translatedFormat('d M'),
                            'yearly' => $period,
                            default => \Carbon\Carbon::createFromFormat('Y-m', $period)->translatedFormat('F Y'),
                        };
                    }, array_column($reportData, 'period'))) !!},
                    labels: {
                        rotate: -45,
                        style: { fontSize: '12px' }
                    }
                },
                yaxis: {
                    labels: {
                        formatter: function(val) {
                            return val.toFixed(0) + " ج.س";
                        }
                    }
                },
                tooltip: {
                    y: {
                        formatter: val => val.toFixed(2) + " جنيه",
                        title: {
                            formatter: function(seriesName) {
                                return seriesName;
                            }
                        }
                    }
                },
                colors: ['#3498db', '#f39c12', '#9b59b6', '#e74c3c', '#2ecc71'],
                legend: {
                    position: 'top',
                    horizontalAlign: 'center',
                    fontSize: '14px',
                    markers: { radius: 2 }
                },
                dataLabels: { enabled: false },
                plotOptions: {
                    bar: {
                        borderRadius: 4,
                        columnWidth: '60%',
                    }
                },
                responsive: [{
                    breakpoint: 768,
                    options: {
                        chart: { height: 300 },
                        xaxis: { labels: { rotate: -45 } }
                    }
                }]
            };

            var financeChart = new ApexCharts(document.querySelector("#monthlyFinanceChart"), financeChartOptions);
            financeChart.render();
        });
    </script>

    <!--Internal  Chart.bundle js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Moment js -->
    <script src="{{URL::asset('Dashboard/plugins/raphael/raphael.min.js')}}"></script>
    <!--Internal  Flot js-->
    <script src="{{URL::asset('Dashboard/plugins/jquery.flot/jquery.flot.js')}}"></script>
    <script src="{{URL::asset('Dashboard/plugins/jquery.flot/jquery.flot.pie.js')}}"></script>
    <script src="{{URL::asset('Dashboard/plugins/jquery.flot/jquery.flot.resize.js')}}"></script>
    <script src="{{URL::asset('Dashboard/plugins/jquery.flot/jquery.flot.categories.js')}}"></script>
    <script src="{{URL::asset('Dashboard/js/dashboard.sampledata.js')}}"></script>
    <script src="{{URL::asset('Dashboard/js/chart.flot.sampledata.js')}}"></script>
    <!--Internal Apexchart js-->
    <script src="{{URL::asset('Dashboard/js/apexcharts.js')}}"></script>
    <!-- Internal Map -->
    <script src="{{URL::asset('Dashboard/plugins/jqvmap/jquery.vmap.min.js')}}"></script>
    <script src="{{URL::asset('Dashboard/plugins/jqvmap/maps/jquery.vmap.usa.js')}}"></script>
    <script src="{{URL::asset('Dashboard/js/modal-popup.js')}}"></script>
    <!--Internal  index js -->
    <script src="{{URL::asset('Dashboard/js/index.js')}}"></script>
    <script src="{{URL::asset('Dashboard/js/jquery.vmap.sampledata.js')}}"></script>
@endsection

