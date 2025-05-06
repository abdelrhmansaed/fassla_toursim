@extends('Dashboard.layouts.master')

@section('content')
    <div class="container">
        <h2 class="mb-4">إدارة الأدوار والصلاحيات</h2>
        <a href="{{ route('roles.create') }}" class="btn btn-success mb-3">➕ إضافة دور جديد</a>

        @foreach($roles as $role)
            <div class="card mb-3">
                <div class="card-header">
                    <strong>{{ $role->name }}</strong>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('roles.update', $role->id) }}">
                        @csrf

                        <div class="row">
                            @foreach($permissions as $permission)
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                            {{ $role->permissions->pluck('name')->contains($permission->name) ? 'checked' : '' }}>
                                        <label class="form-check-label">
                                            {{ $permission->name }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <button type="submit" class="btn btn-primary mt-3">تحديث الصلاحيات</button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
@endsection
