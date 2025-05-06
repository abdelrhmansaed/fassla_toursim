@extends('Dashboard.layouts.master')

@section('content')
    <div class="container mt-4">
        <div class="card shadow-lg border-0">
            <div class="card-header bg-primary text-white text-center">
                <h3 class="mb-0">👤 بروفايل البروفايدر: {{ $provider->name }}</h3>
            </div>
            <div class="card-body">
                <p><strong>📅 العمر:</strong> {{ $provider->age }}</p>
                <p><strong>📧 البريد الإلكتروني:</strong> {{ $provider->email }}</p>
                <p><strong>🆔 رقم الهوية:</strong> {{ $provider->national_id }}</p>

                <hr>

                <h4>📌 الرحلات التي وافق عليها أو رفضها</h4>
                <table class="table table-hover table-bordered text-center">
                    <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>📌 اسم الرحلة</th>
                        <th>📅 تاريخ الرحلة</th>
                        <th>👥 عدد الأفراد</th>
                        <th>💰 السعر</th>
                        <th>⚙️ الحالة</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($tripRequests as $tripRequest)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $tripRequest->trip->name }}</td>
                            <td>{{ $tripRequest->trip->date }}</td>
                            <td>{{ optional($tripRequest->detail)->total_people }}</td>
                            <td>{{ optional($tripRequest->detail)->price }} جنيه</td>
                            <td>
                                @if($tripRequest->status == 'pending')
                                    <span class="badge bg-warning">قيد الانتظار</span>
                                @elseif($tripRequest->status == 'waiting_payment')
                                    <span class="badge bg-info">في انتظار الدفع</span>
                                @elseif($tripRequest->status == 'waiting_confirmation')
                                    <span class="badge bg-primary">في انتظار التأكيد</span>
                                @elseif($tripRequest->status == 'confirmed')
                                    <span class="badge bg-success">مؤكدة</span>
                                @elseif($tripRequest->status == 'canceled')
                                    <span class="badge bg-danger">ملغاة</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
