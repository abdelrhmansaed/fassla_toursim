@extends('Dashboard.layouts.master')
    @section('content')
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">

                        </div>
                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <form method="GET" action="{{ route('transactions.pay.form') }}">
                                        <div class="form-group d-flex align-items-center">
                                            <label for="currency" class="me-2">عرض الرصيد حسب العملة:</label>
                                            <select name="currency" id="currency" class="form-select me-2" onchange="this.form.submit()">
                                                <option value="all" {{ $currency == 'all' ? 'selected' : '' }}>كل العملات</option>
                                                <option value="egp" {{ $currency == 'egp' ? 'selected' : '' }}>جنيه</option>
                                                <option value="usd" {{ $currency == 'usd' ? 'selected' : '' }}>دولار</option>
                                                <option value="eur" {{ $currency == 'eur' ? 'selected' : '' }}>يورو</option>
                                            </select>
                                        </div>
                                    </form>

                                    <div class="card border-left-info shadow h-100 py-2 mt-2">
                                        <div class="card-body">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">الرصيد المتبقي</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                {{ number_format($totalBalance ?? 0, 2) }}
                                                @switch($currency)
                                                    @case('usd') دولار @break
                                                    @case('eur') يورو @break
                                                    @default جنيه
                                                @endswitch
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            <form action="{{ route('transactions.pay') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="amount">المبلغ المدفوع</label>
                                            <input type="number" class="form-control" id="amount" name="amount" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label"> العملة</label>

                                        <select id="discount_currency" class="form-select"
                                                style="max-width: 400px; height: 40px; font-size: 1rem; font-weight: 500; border-top-left-radius: 0; border-bottom-left-radius: 0;" name="currency">
                                            <option value="egp">💴 جنيه</option>
                                            <option value="usd">💵 دولار</option>
                                            <option value="eur">💶 يورو</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="payment_date">تاريخ الدفع</label>
                                            <input type="date" class="form-control" id="payment_date" name="payment_date" required value="{{ now()->format('Y-m-d') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="payment_proof">صورة إثبات الدفع (اختياري)</label>
                                            <input type="file" class="form-control-file" id="payment_proof" name="payment_proof">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="payment_notes">ملاحظات الدفع</label>
                                    <textarea class="form-control" id="payment_notes" name="payment_notes" rows="3"></textarea>
                                </div>

                                <button type="submit" class="btn btn-primary">تسجيل الدفع</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection
