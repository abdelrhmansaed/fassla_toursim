@extends('Dashboard.layouts.master')
@section('content')
    <div class="container py-4">
        <div class="card shadow-lg">
            <div class="card-header bg-success text-white text-center">
                <h2>الرحلات المقبولة</h2>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover text-center">
                        <thead class="bg-dark text-white">
                        <tr>
                            <th>تاريخ الحجز</th>
                            <th>رقم الطلب</th>
                            <th>اسم الفندق</th>
                            <th>الرحلة</th>
                            <th>المزود</th>
                            <th>عدد الأشخاص</th>
                            <th>اخمالي سعر البيع بالدولار</th>
                            <th>اخمالي سعر البيع باليورو</th>
                            <th>اخمالي سعر البيع بالمصري</th>
                            <th>إجمالي السعر</th>
                            <th>حالة الدفع</th>

                        </tr>
                        </thead>
                        <tbody>
                        @foreach($trips as $trip)
                            @foreach($trip->details as $detail)

                                <tr>
                                    <td> {{ \Carbon\Carbon::parse($trip->booking_datetime)->format('d/m/Y g:i A') ?? 'غير متاح' }}</td>

                                    <td>{{ optional($trip)->receipt_number ?? 'غير متاح' }}</td>

                                    <td>{{ optional($trip)->hotel_name ?? 'غير متاح' }}</td>
                                <td>
                                    {{ optional($detail->tripType)->type ?? 'غير متاح' }}
                                    @if($detail->subTripType)
                                        - {{ optional($detail->subTripType)->type ?? 'غير متاح'}}
                                    @endif
                                </td>
                                    <td>{{ optional($detail->provider)->name ?? 'غير متاح' }}</td>
                                <td>{{ $detail->total_people }}</td>
                                    <td>{{ number_format($detail->total_price_usd, 2) }} </td>
                                    <td>{{ number_format($detail->total_price_eur, 2) }} </td>
                                    <td>{{ number_format($detail->total_price_egp, 2) }} </td>
                                <td class="fw-bold text-success">{{ number_format($detail->total_price, 2) }} جنيه</td>
                                    @if(auth()->user()->role == 'agent' && $detail->status == 'confirmed' ) <!-- الزر يظهر فقط للمندوب -->

                                    <td>
                                        <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#paymentModal{{ $detail->id }}">
                                            دفع
                                        </button>

                                        <!-- موديول الدفع -->
                                        <div class="modal fade" id="paymentModal{{ $detail->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <form method="POST" action="{{ route('trips.pay', $detail->id) }}">
                                                    @csrf
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">إتمام الدفع للرحلة رقم {{ $detail->id }}</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            @foreach($detail->transactions as $transaction)
                                                                <p><strong>إجمالي السعر  :</strong> {{ number_format($transaction->credit ?? 0, 2) }} جنيه</p>
                                                                <p><strong>إجمالي السعر المدفوع  :</strong> {{ number_format($transaction->debit ?? 0, 2) }} جنيه</p>
                                                                <p><strong>:الرصيد المتبقي</strong> {{ number_format($transaction->total_balance ?? 0, 2) }} جنيه</p>
                                                            @endforeach

                                                            <div class="mb-3">
                                                                <label for="amount" class="form-label">المبلغ المدفوع</label>
                                                                <input type="number" class="form-control" name="amount" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="discount" class="form-label">الخصم (اختياري)</label>
                                                                <input type="number" class="form-control" name="discount">
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-primary">إتمام الدفع</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                    @endif
                            </tr>
                            @endforeach
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
