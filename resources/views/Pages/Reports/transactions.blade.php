@extends('Dashboard.layouts.master')
<style>
    .currency-stat {
        background-color: #f8f9fa;
        border-radius: 5px;
        padding: 10px;
        height: 100%;
    }
    .currency-stat .badge {
        font-size: 1rem;
        padding: 5px 10px;
    }
</style>
@section('content')
    <div class="container-fluid py-5">
        <div class="card shadow-lg border-0 rounded-lg">
            <div class="card-header bg-gradient-primary-to-secondary text-white text-center py-4">
                <h2 class="m-0 font-weight-bold">تقرير المندوب المالي</h2>
            </div>
            <div class="card-body px-5 pb-5">
                <form id="agent-report-form" class="mb-5">
                    <div class="row justify-content-center">
                        <div class="col-md-6 col-lg-4">
                            <div class="form-group">
                                <label class="h5 text-dark">كود المندوب (مثال: 1/23)</label>
                                <div class="input-group">
                                    <input type="text" id="agent_code_input" name="agent_code" class="form-control form-control-lg border-right-0" placeholder="أدخل كود المندوب">
                                    <div class="input-group-append">
                                        <span class="input-group-text bg-white border-left-0"><i class="fas fa-user-tag"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-3">
                        <button type="submit" class="btn btn-primary btn-lg px-5">
                            <i class="fas fa-search mr-2"></i> بحث
                        </button>
                    </div>
                </form>

                <div id="report-result">
                    @if($agent)
                        <div class="agent-header bg-light rounded-lg p-4 mb-5 text-center shadow-sm">
                            <div class="agent-badge bg-white p-3 rounded-circle d-inline-flex align-items-center justify-content-center shadow" style="width: 80px; height: 80px;">
                                <i class="fas fa-user-tie fa-2x text-primary"></i>
                            </div>
                            <h3 class="mt-3 text-primary font-weight-bold">{{ $agent->name }}</h3>
                            <div class="agent-code-badge badge badge-pill badge-dark px-3 py-2 mb-3">
                                <i class="fas fa-id-card mr-2"></i>الكود: {{ $agent->code }}
                            </div>
                        </div>

                        <div class="row mb-5">
                            <div class="col-md-3 mb-4">
                                <div class="card stat-card h-100 border-0 shadow-sm hover-effect">
                                    <div class="card-body text-center py-4">
                                        <div class="stat-icon bg-soft-primary rounded-circle p-3 mb-3 d-inline-block">
                                            <i class="fas fa-clipboard-list fa-2x text-primary"></i>
                                        </div>
                                        <h5 class="text-muted mb-2">الطلبات</h5>
                                        <h2 class="font-weight-bold">{{ $stats['total_requests'] }}</h2>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-4">
                                <div class="card stat-card h-100 border-0 shadow-sm hover-effect">
                                    <div class="card-body text-center py-4">
                                        <div class="stat-icon bg-soft-warning rounded-circle p-3 mb-3 d-inline-block">
                                            <i class="fas fa-clock fa-2x text-warning"></i>
                                        </div>
                                        <h5 class="text-muted mb-2">قيد الانتظار</h5>
                                        <h2 class="font-weight-bold">{{ $stats['pending'] }}</h2>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-4">
                                <div class="card stat-card h-100 border-0 shadow-sm hover-effect">
                                    <div class="card-body text-center py-4">
                                        <div class="stat-icon bg-soft-success rounded-circle p-3 mb-3 d-inline-block">
                                            <i class="fas fa-check-circle fa-2x text-success"></i>
                                        </div>
                                        <h5 class="text-muted mb-2">مقبولة</h5>
                                        <h2 class="font-weight-bold">{{ $stats['confirmed'] }}</h2>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-4">
                                <div class="card stat-card h-100 border-0 shadow-sm hover-effect">
                                    <div class="card-body text-center py-4">
                                        <div class="stat-icon bg-soft-danger rounded-circle p-3 mb-3 d-inline-block">
                                            <i class="fas fa-times-circle fa-2x text-danger"></i>
                                        </div>
                                        <h5 class="text-muted mb-2">مرفوضة</h5>
                                        <h2 class="font-weight-bold">{{ $stats['canceled'] }}</h2>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-5">
                            <div class="col-md-4 mb-4">
                                <div class="card stat-card h-100 border-0 shadow-sm hover-effect">
                                    <div class="card-body text-center py-4">
                                        <div class="stat-icon bg-soft-info rounded-circle p-3 mb-3 d-inline-block">
                                            <i class="fas fa-chart-line fa-2x text-info"></i>
                                        </div>
                                        <h5 class="text-muted mb-2">إجمالي المبيعات</h5>
                                        <h2 class="font-weight-bold">{{ number_format($stats['total_debit'], 2) }} <small class="text-muted">جنيه</small></h2>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <div class="card stat-card h-100 border-0 shadow-sm hover-effect">
                                    <div class="card-body text-center py-4">
                                        <div class="stat-icon bg-soft-success rounded-circle p-3 mb-3 d-inline-block">
                                            <i class="fas fa-money-bill-wave fa-2x text-success"></i>
                                        </div>
                                        <h5 class="text-muted mb-2">المبالغ المدفوعة</h5>
                                        <h2 class="font-weight-bold">{{ number_format($stats['total_credit'], 2) }} <small class="text-muted">جنيه</small></h2>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <div class="card stat-card h-100 border-0 shadow-sm hover-effect">
                                    <div class="card-body text-center py-4">
                                        <div class="stat-icon bg-soft-danger rounded-circle p-3 mb-3 d-inline-block">
                                            <i class="fas fa-hand-holding-usd fa-2x text-danger"></i>
                                        </div>
                                        <h5 class="text-muted mb-2">المبالغ المستحقة</h5>
                                        <h2 class="font-weight-bold">{{ number_format($stats['balance'], 2) }} <small class="text-muted">جنيه</small></h2>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="final-balance-card bg-gradient-dark text-white rounded-lg p-4 mb-5 text-center shadow">
                            <h4 class="mb-3"><i class="fas fa-coins mr-2"></i>الرصيد النهائي</h4>
                            <h1 class="font-weight-bold">{{ number_format($stats['balance'], 2) }} <small>جنيه</small></h1>
                        </div>

                        <hr class="my-5 border-light">

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4 class="m-0 text-dark font-weight-bold">
                                <i class="fas fa-history mr-2 text-primary"></i>سجل المعاملات
                            </h4>
                            <button class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-download mr-2"></i>تصدير كـ PDF
                            </button>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="card border-0 shadow-sm mb-4">
                                    <div class="card-body py-3">
                                        <div class="row align-items-center">
                                            <div class="col-md-3">
                                                <div class="form-group mb-0">
                                                    <label class="font-weight-bold">فلترة حسب العملة</label>
                                                    <select id="currency-filter" class="form-control">
                                                        <option value="all">كل العملات</option>
                                                        <option value="egp" {{ request('currency') == 'egp' ? 'selected' : '' }}>EGP</option>
                                                        <option value="usd" {{ request('currency') == 'usd' ? 'selected' : '' }}>USD</option>
                                                        <option value="eur" {{ request('currency') == 'eur' ? 'selected' : '' }}>EUR</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-9">
                                                <div class="row text-center">
                                                    <div class="col-md-4">
                                                        <div class="currency-stat">
                                                            <span class="badge badge-primary">EGP</span>
                                                            <div class="mt-2">
                                                                <small>المبيعات: {{ number_format($currencyStats['egp']['total_sales'], 2) }}</small><br>
                                                                <small>المدفوعات: {{ number_format($currencyStats['egp']['total_payments'], 2) }}</small><br>
                                                                <strong>الرصيد: {{ number_format($currencyStats['egp']['current_balance'], 2) }}</strong>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="currency-stat">
                                                            <span class="badge badge-success">USD</span>
                                                            <div class="mt-2">
                                                                <small>المبيعات: {{ number_format($currencyStats['usd']['total_sales'], 2) }}</small><br>
                                                                <small>المدفوعات: {{ number_format($currencyStats['usd']['total_payments'], 2) }}</small><br>
                                                                <strong>الرصيد: {{ number_format($currencyStats['usd']['current_balance'], 2) }}</strong>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="currency-stat">
                                                            <span class="badge badge-info">EUR</span>
                                                            <div class="mt-2">
                                                                <small>المبيعات: {{ number_format($currencyStats['eur']['total_sales'], 2) }}</small><br>
                                                                <small>المدفوعات: {{ number_format($currencyStats['eur']['total_payments'], 2) }}</small><br>
                                                                <strong>الرصيد: {{ number_format($currencyStats['eur']['current_balance'], 2) }}</strong>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                <thead class="bg-gradient-primary-to-secondary text-white">
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">رقم الحجز</th>
                                    <th class="text-center">نوع العملية</th>
                                    <th class="text-center">اسم الفندق</th>
                                    <th class="text-center">اسم الرحلة</th>
                                    <th class="text-center">المندوب</th>
                                    <th class="text-center">مزود الخدمة</th>
                                    <th class="text-center">عدد الأفراد</th>
                                    <th class="text-center">المبلغ</th>
                                    <th class="text-center">المبلغ المدفوع</th>
                                    <th class="text-center">الرصيد</th>
                                    <th class="text-center">التاريخ</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $counter = ($transactions->currentPage() - 1) * $transactions->perPage() + 1;
                                @endphp

                                @foreach($transactions as $transaction)
                                    @php
                                        $detail = $transaction->tripRequestDetail;
                                        $request = $detail?->tripRequest;
                                        $provider = $request?->provider;
                                    @endphp

                                    @if($transaction->type == 'invoice')
                                        <tr class="transaction-invoice">
                                            <td class="text-center">{{ $counter++ }}</td>
                                            <td class="text-center">
                                                <span class="badge badge-primary">{{ $request?->booking_number ?? '-' }}</span>
                                            </td>
                                            <td class="text-center">
                            <span class="transaction-type type-invoice">
                                <i class="fas fa-file-invoice mr-1"></i> فاتورة
                            </span>
                                            </td>
                                            <td class="text-center">{{ $request?->hotel_name ?? '-' }}</td>
                                            <td class="text-center">
                                                {{ optional($detail->tripType)->type }}
                                                @if($detail->subTripType)
                                                    - {{ optional($detail->subTripType)->type }}
                                                @endif
                                            </td>
                                            <td class="text-center">{{ $request->agent->name ?? 'غير متاح' }}</td>

                                            <td class="text-center">{{ $detail->provider->name ?? 'غير متاح' }}</td>
                                            <td class="text-center">{{ $detail?->total_people ?? 0 }}</td>
                                            <td class="text-center amount amount-debit">
                                                {{ number_format($transaction->debit, 2) }} ج.م
                                            </td>
                                            <td class="text-center amount amount-credit">
                                                {{ number_format($transaction->credit, 2) }} ج.م
                                            </td>
                                            <td class="text-center amount amount-balance">
                                                {{ number_format($transaction->total_balance, 2) }} ج.م
                                            </td>
                                            <td class="text-center">
                                                {{ $transaction->created_at->format('Y-m-d') }}
                                            </td>


                                        </tr>
                                    @endif
                                    @if($transaction->type == 'discount')
                                        <tr class="summary-row">
                                            <td colspan="12" class="p-0">
                                                <div class="container-fluid">
                                                    <div class="row align-items-center py-2">
                                                        <div class="col text-right pr-4">
                    <span class="transaction-type type-discount">
                        <i class="fas fa-percentage mr-1"></i> خصم
                    </span>
                                                        </div>
                                                        <div class="col text-center px-0">
                    <span class="summary-value">
                        {{ number_format($transaction->credit, 2) }}
                        {{ strtoupper($transaction->tripRequestDetail->currency) }}
                    </span>
                                                        </div>
                                                        <div class="col"></div>
                                                    </div>
                                                    <div class="row align-items-center py-2">
                                                        <div class="col text-right pr-4">
                                                            <span class="summary-label">الرصيد بعد الخصم: </span>
                                                        </div>
                                                        <div class="col text-left pl-0">
                    <span class="summary-value">
                        {{ number_format($transaction->total_balance, 2) }} ج.م
                    </span>
                                                        </div>
                                                        <div class="col"></div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif

                                    @if($transaction->type == 'commission')
                                        <tr class="summary-row">
                                            <td colspan="12" class="p-0">
                                                <div class="container-fluid">
                                                    <div class="row align-items-center py-2">
                                                        <div class="col text-right pr-4">
                    <span class="transaction-type type-commission">
                        <i class="fas fa-hand-holding-usd mr-1"></i> عمولة
                    </span>
                                                        </div>
                                                        <div class="col text-center px-0">
                    <span class="summary-value">
                        {{ number_format($transaction->credit, 2) }} ج.م
                    </span>
                                                        </div>
                                                        <div class="col"></div>
                                                    </div>
                                                    <div class="row align-items-center py-2">
                                                        <div class="col text-right pr-4">
                                                            <span class="summary-label">الرصيد بعد العمولة: </span>
                                                        </div>
                                                        <div class="col text-left pl-0">
                    <span class="summary-value">
                        {{ number_format($transaction->total_balance, 2) }} ج.م
                    </span>
                                                        </div>
                                                        <div class="col"></div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif

                                    @if($transaction->type == 'payment')
                                        <tr class="summary-row">
                                            <td colspan="12" class="p-0">
                                                <div class="container-fluid">

                                                    {{-- سطر 1: المبلغ المدفوع --}}
                                                    <div class="row align-items-center py-2">
                                                        <div class="col text-right pr-4">
                        <span class="transaction-type type-payment">
                            <i class="fas fa-money-bill-wave mr-1"></i> دفع
                        </span>
                                                        </div>
                                                        <div class="col text-center px-0">
                        <span class="summary-value">
                            {{ number_format($transaction->credit, 2) }} ج.م
                        </span>
                                                        </div>
                                                        <div class="col"></div>
                                                    </div>

                                                    {{-- سطر 2: الرصيد بعد الدفع --}}
                                                    <div class="row align-items-center py-2">
                                                        <div class="col text-right pr-4">
                                                            <span class="summary-label">الرصيد بعد الدفع:</span>
                                                        </div>
                                                        <div class="col text-left pl-0">
                        <span class="summary-value">
                            {{ number_format($transaction->total_balance, 2) }} ج.م
                        </span>
                                                        </div>
                                                        <div class="col"></div>
                                                    </div>

                                                    {{-- سطر 3: إثبات الدفع إن وجد --}}
                                                    @if($transaction->image || $transaction->note)
                                                        <div class="row align-items-center py-2 bg-light border-top">
                                                            <div class="col text-right pr-4">
                                                                <strong>إثبات الدفع:</strong>
                                                            </div>
                                                            <div class="col text-left pl-0">
                                                                @if($transaction->image)
                                                                    <a href="{{ asset('storage/' . $transaction->image) }}" target="_blank">
                                                                        <i class="fas fa-file-image text-primary mr-1"></i> عرض الإثبات
                                                                    </a>
                                                                @else
                                                                    <span class="text-muted">
                                    <i class="fas fa-times-circle text-danger mr-1"></i> غير متاح
                                </span>
                                                                @endif
                                                            </div>
                                                            <div class="col"></div>
                                                        </div>

                                                        @if($transaction->note)
                                                            <div class="row align-items-center py-2">
                                                                <div class="col text-right pr-4">
                                                                    <strong>بيان الدفع:</strong>
                                                                </div>
                                                                <div class="col text-left pl-0 text-muted">
                                                                    {{ $transaction->note }}
                                                                </div>
                                                                <div class="col"></div>
                                                            </div>
                                                        @endif
                                                    @endif

                                                </div>
                                            </td>
                                        </tr>
                                    @endif

                                @endforeach
                                </tbody>
                            </table>
                            <div class="d-flex justify-content-center mt-3">
                                {{ $transactions->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    @elseif(request()->has('agent_code') && !$agent)
                        <div class="alert alert-warning text-center p-4 rounded-lg shadow-sm">
                            <i class="fas fa-exclamation-triangle fa-2x mb-3"></i>
                            <h4 class="alert-heading">كود المندوب غير متوفر</h4>
                            <p>الرجاء التأكد من صحة كود المندوب والمحاولة مرة أخرى</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
{{-- آخر سطر قبل نهاية الصفحة --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const currencyFilter = document.getElementById('currency-filter');
        if (currencyFilter) {
            currencyFilter.addEventListener('change', function () {
                let selectedCurrency = this.value;
                let url = new URL(window.location.href);
                url.searchParams.set('currency', selectedCurrency);
                window.location.href = url.toString();
            });
        }
    });
</script>

    <style>
        .hover-effect {
            transition: all 0.3s ease;
        }
        .hover-effect:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
        }
        .stat-icon {
            transition: all 0.3s ease;
        }
        .stat-card:hover .stat-icon {
            transform: scale(1.1);
        }
        .bg-gradient-primary-to-secondary {
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        }
        .bg-gradient-dark {
            background: linear-gradient(135deg, #5a5c69 0%, #373840 100%);
        }
        .agent-header {
            position: relative;
            overflow: hidden;
        }
        .agent-header::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" fill="rgba(255,255,255,0.7)"><circle cx="25" cy="25" r="5"/><circle cx="75" cy="75" r="5"/><circle cx="75" cy="25" r="5"/><circle cx="25" cy="75" r="5"/></svg>');
            opacity: 0.1;
        }
        .final-balance-card {
            position: relative;
            overflow: hidden;
        }
        .final-balance-card::before {
            content: "";
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        }

    </style>
<style>
    /* تحسينات عامة للجدول */
    .table-responsive {
        border-radius: 0.5rem;
        overflow: hidden;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    }

    .table {
        margin-bottom: 0;
        font-size: 0.9rem;
    }

    .table thead th {
        border-bottom-width: 1px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 1.25rem 0.75rem;
    }

    .table tbody td {
        padding: 1rem 0.75rem;
        vertical-align: middle;
    }

    /* تنسيقات رأس الجدول */
    .bg-gradient-primary-to-secondary {
        background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
    }

    /* ألوان مختلفة لأنواع المعاملات */
    .transaction-invoice td {
        background-color: rgba(78, 115, 223, 0.05);
        border-left: 4px solid #4e73df;
    }

    .transaction-discount td {
        background-color: rgba(255, 193, 7, 0.05);
        border-left: 4px solid #ffc107;
    }

    .transaction-commission td {
        background-color: rgba(23, 162, 184, 0.05);
        border-left: 4px solid #17a2b8;
    }

    .transaction-payment td {
        background-color: rgba(40, 167, 69, 0.05);
        border-left: 4px solid #28a745;
    }

    /* تنسيقات خاصة بكل نوع معاملة */
    .transaction-type {
        display: inline-block;
        padding: 0.35rem 0.75rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.75rem;
        text-align: center;
    }

    .type-invoice {
        background-color: #e0e6ff;
        color: #4e73df;
    }

    .type-discount {
        background-color: #fff8e1;
        color: #ffc107;
    }

    .type-commission {
        background-color: #e1f7fa;
        color: #17a2b8;
    }

    .type-payment {
        background-color: #e6f7ed;
        color: #28a745;
    }

    /* تنسيقات المبالغ */
    .amount {
        font-weight: 700;
        font-family: 'Tahoma', sans-serif;
    }

    .amount-debit {
        color: #e74a3b;
    }

    .amount-credit {
        color: #1cc88a;
    }

    .amount-balance {
        color: #5a5c69;
    }

    /* تنسيقات إثبات الدفع */
    .payment-proof {
        display: inline-block;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .proof-available {
        background-color: #e6f7ed;
        color: #28a745;
    }

    .proof-not-available {
        background-color: #f8f9fa;
        color: #6c757d;
    }

    /* تنسيقات الصفوف المدمجة */
    .summary-row {
        background-color: #f8f9fa !important;
    }

    .summary-content {
        padding: 1rem;
        border-radius: 0.5rem;
        background-color: white;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }

    .summary-label {
        font-weight: 600;
        color: #5a5c69;
    }

    .summary-value {
        font-weight: 700;
        color: #4e73df;
    }

    /* تأثيرات حركية */
    .table-hover tbody tr {
        transition: all 0.2s ease;
    }

    .table-hover tbody tr:hover {
        transform: translateX(5px);
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }

    /* تنسيقات الترقيم الصفحي */
    .pagination {
        border-radius: 0.5rem;
        padding: 1rem;
        background-color: white;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
    }

    .page-item.active .page-link {
        background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        border-color: #4e73df;
    }

    .page-link {
        color: #4e73df;
        border: 1px solid #ddd;
        margin: 0 2px;
        min-width: 38px;
        text-align: center;
        border-radius: 0.25rem !important;
    }

    .page-link:hover {
        color: #224abe;
        background-color: #f8f9fa;
    }
</style>

<style>
    .summary-row {
        background-color: #f8f9fa;
        border-left: 4px solid #e74a3b;
    }
    .summary-row.type-commission {
        border-left-color: #f6c23e;
    }
    .summary-row.type-payment {
        border-left-color: #1cc88a;
    }
    .transaction-type {
        font-weight: bold;
        white-space: nowrap;
    }
    .type-discount {
        color: #e74a3b;
    }
    .type-commission {
        color: #f6c23e;
    }
    .type-payment {
        color: #1cc88a;
    }
    .summary-label {
        color: #6c757d;
        margin-left: 5px;
    }
    .summary-value {
        font-weight: bold;
        white-space: nowrap;
    }
    /* لضبط عرض الجدول */
    .table {
        table-layout: fixed;
        width: 100% !important;
    }
    .table td, .table th {
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .text-left {
        text-align: left !important;
    }
    .text-right {
        text-align: right !important;
    }
    .text-center {
        text-align: center !important;
    }
</style>
