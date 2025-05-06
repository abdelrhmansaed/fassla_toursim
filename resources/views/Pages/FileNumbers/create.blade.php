@extends('Dashboard.layouts.master')

@section('content')
    <div class="container py-5">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4 class="text-center">إضافة رقم ملف جديد</h4>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success text-center">{{ session('success') }}</div>
                @endif

                <form action="{{ route('file_numbers.store') }}" method="POST">
                    @csrf

                    <div class="form-group mb-3">
                        <label>كود الملف</label>
                        <input type="text" name="file_code" class="form-control" required>
                    </div>

                    <div class="form-group mb-3">
                        <label>الحد الأقصى للبالغين</label>
                        <input type="number" name="adult_limit" class="form-control" required min="0">
                    </div>

                    <div class="form-group mb-3">
                        <label>الحد الأقصى للأطفال</label>
                        <input type="number" name="child_limit" class="form-control" required min="0">
                    </div>

                    <div class="text-center">
                        <button class="btn btn-success">حفظ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
