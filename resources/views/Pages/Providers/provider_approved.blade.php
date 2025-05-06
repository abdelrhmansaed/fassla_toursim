@extends('Dashboard.layouts.master')

@section('content')
    <div class="container py-4">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white text-center">
                <h2>الرحلات التي وافق عليها البروفايدر</h2>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <div class="table-responsive">
                        <table  class="table table-hover table-sm table-bordered p-0"
                                data-page-length="50" style="text-align: center">
                            <thead>
                            <tr>
                                <th>اسم الرحلة</th>
                                <th>المندوب</th>
                                <th>عدد الأشخاص</th>
                                <th>عدد الذكور</th>
                                <th>عدد الإناث</th>
                                <th>السعر</th>
                                <th>حالة الرحلة</th>
                            </tr>
                            </thead>
                            <tbody id="requestsTableBody">
                            @foreach($trips as $request)
                                @foreach($request->details as $detail)
                                    <tr>
                                        <td>{{ optional($detail->trip)->name ?? 'غير متاح' }}</td>
                                        <td>{{ optional($request->agent)->name ?? 'غير متاح' }}</td>
                                        <td>{{ optional($detail)->total_people ?? 'غير متاح' }}</td>
                                        <td>{{ optional($detail)->adult_count ?? 'غير متاح' }}</td>
                                        <td>{{ optional($detail)->children_count ?? 'غير متاح' }}</td>
                                        <td>{{ optional($detail)->total_price ?? 'غير متاح' }}</td>
                                        <td>
                                            @if($detail->status == 'pending')
                                                <span class="badge bg-warning">قيد الانتظار</span>
                                            @else($detail->status == 'waiting_payment')
                                                <span class="badge bg-warning"> في انتظار الدفع</span>
                                            @endif


                                        </td>


                                    </tr>
                                @endforeach
                            @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('input[type=file]').forEach(input => {
            input.addEventListener('change', function() {
                let tripId = this.id.replace('file-input-', '');
                let confirmButton = document.getElementById('confirm-btn-' + tripId);
                confirmButton.disabled = !this.files.length;
            });
        });
    </script>
@endsection
