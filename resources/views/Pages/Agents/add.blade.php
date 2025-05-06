@extends('Dashboard.layouts.master')

@section('css')
    <style>
        /* أنماط عامة */
        body {
            background-color: #f8fafc;
            font-family: 'Cairo', sans-serif;
        }

        /* تحسينات للبطاقة */
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .card-header {
            background-color: #fff;
            border-bottom: 1px solid #f1f5f9;
            padding: 1.25rem 1.5rem;
        }

        .card-body {
            padding: 1.5rem;
        }

        /* تحسينات للنموذج */
        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #1e293b;
        }

        .form-control {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            transition: all 0.3s;
            background-color: #f8fafc;
        }

        .form-control:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
            background-color: #fff;
        }

        /* تحسينات للأزرار */
        .btn-primary {
            background-color: #4f46e5;
            border-color: #4f46e5;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s;
        }

        .btn-primary:hover {
            background-color: #4338ca;
            border-color: #4338ca;
            transform: translateY(-2px);
        }

        /* تحسينات للتنبيهات */
        .alert-danger {
            background-color: #fee2e2;
            border-color: #fecaca;
            color: #b91c1c;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }

        /* تحسينات للعناوين */
        .content-title {
            font-weight: 700;
            color: #1e293b;
        }

        /* تحسينات للهواتف */
        @media (max-width: 768px) {
            .card-body {
                padding: 1rem;
            }

            .form-control {
                padding: 0.65rem 0.9rem;
            }
        }
    </style>
@endsection

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between bg-white p-4 mb-4 rounded-lg shadow-sm">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                <h4 class="content-title mb-0 my-auto text-primary">
                    <i class="fas fa-user-plus ml-2"></i> إضافة مندوب جديد
                </h4>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0">معلومات المندوب</h5>
                </div>

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <h6 class="font-weight-bold">يوجد أخطاء في البيانات:</h6>
                            <ul class="mb-0 pl-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="post" action="{{route('agents.store')}}" autocomplete="off" class="needs-validation" novalidate>
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">الاسم الكامل</label>
                                    <input type="text" name="name" class="form-control" required>
                                    <div class="invalid-feedback">يرجى إدخال الاسم</div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">البريد الإلكتروني</label>
                                    <input type="email" name="email" class="form-control" required>
                                    <div class="invalid-feedback">يرجى إدخال بريد إلكتروني صحيح</div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password">كلمة المرور</label>
                                    <input type="password" name="password" class="form-control" required>
                                    <div class="invalid-feedback">يرجى إدخال كلمة مرور</div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="age">السن</label>
                                    <input type="number" name="age" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="id_number">رقم الهوية</label>
                                    <input type="text" name="national_id" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="code">كود المندوب</label>
                                    <input type="text" name="code" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="form-group text-left mt-4">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-save ml-2"></i> حفظ البيانات
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        // تفعيل التحقق من النموذج
        (function() {
            'use strict';
            window.addEventListener('load', function() {
                var forms = document.getElementsByClassName('needs-validation');
                var validation = Array.prototype.filter.call(forms, function(form) {
                    form.addEventListener('submit', function(event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();
    </script>
@endsection
