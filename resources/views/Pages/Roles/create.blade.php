@extends('Dashboard.layouts.master')

@section('content')
    <div class="container">
        <h2 class="mb-4">إضافة دور جديد</h2>

        <form method="POST" action="{{ route('roles.store') }}">
            @csrf
            <select name="role" class="form-control" required>
                @foreach($roles as $role)
                    <option value="{{ $role->name }}">{{ $role->name }}</option>
                @endforeach
            </select>

            <div class="mb-3">
                <label class="form-label">اسم الدور</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <h5 class="mt-4">الصلاحيات:</h5>
            <div class="row">
                @foreach($permissions as $permission)
                    <div class="col-md-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission->name }}">
                            <label class="form-check-label">
                                {{ $permission->name }}
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>

            <button type="submit" class="btn btn-success mt-4">إنشاء الدور</button>
        </form>
    </div>
@endsection
