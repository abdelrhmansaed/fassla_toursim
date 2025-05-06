
@extends('Dashboard.layouts.master')
@section('css')
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">{{trans('dashboard/main_trans.agents')}}</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0"></span>
            </div>
        </div>

    </div>


@endsection
@section('content')


    <div class="row">
        <div class="col-md-12 mb-30">
            <div class="card card-statistics h-100">
                <div class="card-body">
                    <div class="col-xl-12 mb-30">
                        <div class="card card-statistics h-100">
                            <div class="card-body">

                                <div class="table-responsive">
                                    <table id="datatable" class="table  table-hover table-sm table-bordered p-0"
                                           data-page-length="50" style="text-align: center">
                                        <thead>
                                        <tr>
                                            <th>اسم الرحلة</th>
                                            <th>المندوب</th>
                                            <th>عدد الاشخاص</th>
                                            <th>عدد الذكور</th>
                                            <th>عدد الاناث</th>
                                            <th>السعر</th>
                                            <th>حالة الرحلة</th>
                                            <th>ايصال الدفع</th>
                                            <th>الاجرات</th>

                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($requests as $request)
                                            @foreach($request->details as $detail)
                                                <tr>
                                                    <td>{{ optional($detail->trip)->name ?? 'غير متاح' }}</td>
                                                    <td>{{ optional($request->agent)->name ?? 'غير متاح' }}</td>
                                                    <td>{{ optional($detail)->total_people ?? 'غير متاح' }}</td>
                                                    <td>{{ optional($detail)->adult_count ?? 'غير متاح' }}</td>
                                                    <td>{{ optional($detail)->children_count ?? 'غير متاح' }}</td>
                                                    <td>{{ optional($detail)->total_price ?? 'غير متاح' }}</td>
                                                    <td>
                                                        @if($detail->status == 'waiting_confirmation')
                                                            <span class="badge bg-danger"> غي انتظار الموافقة </span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if(!empty($detail->image))
                                                            <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#paymentModal-{{ $detail->id }}">
                                                                إيصال الدفع
                                                            </button>

                                                            <!-- نافذة منبثقة لعرض الصورة -->
                                                            <div class="modal fade" id="paymentModal-{{ $detail->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                <div class="modal-dialog">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title">إيصال الدفع</h5>
                                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                        </div>
                                                                        <div class="modal-body text-center">
                                                                            <img src="{{ asset('storage/' . $detail->image) }}" class="img-fluid" alt="إيصال الدفع">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @else
                                                            <span class="text-danger">لم يتم رفع إيصال</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <form action="{{ route('provider.approveRequest', $detail->id) }}" method="POST"  style="display:inline;">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="btn btn-success">قبول الطلب</button>
                                                        </form>

                                                        <form action="{{ route('provider.rejectRequest', $detail->id) }}" method="POST" style="display:inline;" >
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="btn btn-danger">رفض الطلب</button>
                                                        </form>
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
            </div>
        </div>
    </div>

    </div>
    <!-- Container closed -->
    </div>
    <!-- main-content closed -->
@endsection
@section('js')
@endsection

