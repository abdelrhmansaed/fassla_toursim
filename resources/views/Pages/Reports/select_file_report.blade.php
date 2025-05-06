@extends('Dashboard.layouts.master')

@section('content')
    <div class="container py-4">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4>اختر رقم الملف لعرض التقرير</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('reports.file') }}" method="GET" class="mb-4">
                    <div class="row">
                        <div class="col-md-5">
                            <label>رقم الملف</label>
                            <select name="file_code" class="form-control">
                                <option value="">-- اختر رقم الملف --</option>
                                @foreach($files as $file)
                                    <option value="{{ $file->file_code }}" {{ request('file_code') == $file->file_code ? 'selected' : '' }}>
                                        {{ $file->file_code }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-5">
                            <label>المندوب</label>
                            <select name="agent_id" class="form-control">
                                <option value="">-- اختر المندوب --</option>
                                @foreach($agents as $agent)
                                    <option value="{{ $agent->id }}" {{ request('agent_id') == $agent->id ? 'selected' : '' }}>
                                        {{ $agent->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary btn-block">عرض التقرير</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
