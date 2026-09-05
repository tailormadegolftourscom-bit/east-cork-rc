@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-body">
            <h1 class="h3 mb-3">Admin Dashboard</h1>

            <p class="mb-3">You are logged in as an administrator.</p>

            <div class="d-flex gap-2 flex-wrap">
                <a href="/admin/schools" class="btn btn-primary">Manage Schools</a>
                <a href="/admin/schools/create" class="btn btn-outline-primary">Add School</a>
                <a href="{{ route('admin.school-requests.index') }}" class="btn btn-outline-primary">School Requests</a>
                <a href="{{ route('admin.committees.index') }}" class="btn btn-outline-primary">Committees</a>
                <a href="{{ route('admin.supporters.index') }}" class="btn btn-outline-primary">Supporters &amp; Children</a>
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary">Users</a>
            </div>
        </div>
    </div>
@endsection
