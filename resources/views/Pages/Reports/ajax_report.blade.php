@if($agent)
    <div class="alert alert-info text-center">
        <h4>تقرير لـ: {{ $agent->name }}</h4>
        <p>الكود: {{ $agent->code }}</p>
    </div>

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card p-3 shadow-sm bg-light">
                <strong>إجمالي الطلبات:</strong><br>
                <span class="h4">{{ $stats['total_requests'] }}</span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm bg-warning">
                <strong>قيد الانتظار:</strong><br>
                <span class="h4">{{ $stats['pending'] }}</span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm bg-success">
                <strong>مقبولة:</strong><br>
                <span class="h4">{{ $stats['confirmed'] }}</span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 shadow-sm bg-danger">
                <strong>مرفوضة:</strong><br>
                <span class="h4">{{ $stats['canceled'] }}</span>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card p-3 shadow-sm bg-info">
                <strong>إجمالي المبيعات:</strong><br>
                <span class="h4">{{ number_format($stats['total_credit'], 2) }} جنيه</span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-3 shadow-sm bg-success">
                <strong>المبالغ المدفوعة (منه):</strong><br>
                <span class="h4">{{ number_format($stats['total_debit'], 2) }} جنيه</span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-3 shadow-sm bg-danger">
                <strong>المبالغ المستحقة (لنا):</strong><br>
                <span class="h4">{{ number_format($stats['total_balance'], 2) }} جنيه</span>
            </div>
        </div>
    </div>

    <div class="alert alert-dark text-center">
        <strong>الرصيد النهائي:</strong> {{ number_format($stats['balance'], 2) }} جنيه
    </div>

    <hr>

    <h5 class="text-center mb-3">سجل المعاملات</h5>
    <table class="table table-bordered table-hover text-center">
        <thead class="bg-dark text-white">
        <tr>
            <th>#</th>
            <th>رقم الحجز</th>
            <th>رقم الإيصال</th>
            <th>اسم الفندق</th>
            <th>اسم الرحلة</th>
            <th>مزود الخدمة</th>
            <th>عدد الأفراد</th>
            <th>المبلغ</th>
            <th>المبلغ المدفوع</th>
            <th>الرصيد بعد العملية</th>
            <th>التاريخ</th>
        </tr>
        </thead>
        <tbody>
        @foreach($transactions as $transaction)
            @php
                $detail = $transaction->tripRequestDetail;
                $trip = $detail?->trip;
                $provider = $detail?->tripRequest?->provider;
                $request = $detail?->tripRequest;
            @endphp
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $request?->booking_number ?? '-' }}</td>
                <td>{{ $request?->receipt_number ?? '-' }}</td>
                <td>{{ $request?->hotel_name ?? '-' }}</td>
                <td>{{ $trip?->name ?? '---' }}</td>
                <td>{{ $provider?->name ?? '---' }}</td>
                <td>{{ $detail?->total_people ?? 0 }}</td>
                <td>{{ number_format($transaction->credit, 2) }} جنيه</td>
                <td>{{ number_format($transaction->debit, 2) }} جنيه</td>
                <td>{{ number_format($transaction->total_balance, 2) }} جنيه</td>
                <td>{{ $transaction->created_at->format('Y-m-d') }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {{ $transactions->links() }}
@else
    <div class="alert alert-warning text-center">كود المندوب غير متوفر</div>
@endif
