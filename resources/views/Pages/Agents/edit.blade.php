@extends('Dashboard.layouts.master')

@section('css')
@endsection

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">المناديب</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0"><h4 class="content-title mb-0 my-auto">تعديل</h4></span>
            </div>
        </div>

    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12 mb-30">
            <div class="card card-statistics h-100">
                <div class="card-body">



                    <form method="post" action="{{route('agents.update', $agent->id)}}" autocomplete="off">
                        @method('PUT')
                        @csrf

                        <div class="form-group col">
                            <label for="name">الاسم</label>
                            <input type="text"  value="{{$agent->name}}" name="name" class="form-control">
                        </div>

                        <div class="form-group col">
                            <label for="email">الايميل</label>
                            <input type="email" value="{{$agent->email}}"  name="email" class="form-control">
                        </div>

                        <div class="form-group col">
                            <label for="password">كلمة السر</label>
                            <input type="password"  value="{{$agent->password}}" name="password" class="form-control">
                        </div>


                        <div class="form-group col">
                            <label for="age">السن</label>
                            <input type="number" value="{{$agent->age}}" name="age" class="form-control">
                        </div>

                        <div class="form-group col">
                            <label for="id_number">رقم الهوية</label>
                            <input type="text" value="{{$agent->national_id}}" name="national_id" class="form-control">
                        </div>
                        <div class="form-group col">
                            <label for="id_number">كود المندوب</label>
                            <input type="text" value="{{$agent->code}}" name="code" class="form-control">
                        </div>
                        <br>
                        <button type="submit" class="btn btn-primary">تأكيد</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
@endsection
