@extends('Dashboard.layouts.master')

@section('content')
    <div class="container">
        <h2 class="mb-4">إضافة مستخدم جديد</h2>

        <form method="POST" action="{{ route('users.store') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">الاسم</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">البريد الإلكتروني</label>
                <input type="email" name="email" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">كلمة المرور</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">اختر الدور</label>
                <select name="role" class="form-select" required>
                    <option value="">-- اختر دور --</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}">{{ $role->name }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-success mt-3">إنشاء المستخدم</button>
        </form>
    </div>
@endsection
