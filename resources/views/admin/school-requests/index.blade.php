@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">School Registration Requests</h1>
            <p class="text-muted mb-0">Review schools submitted through the public Add My School form.</p>
        </div>

        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
            Back to Dashboard
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            @if ($requests->isEmpty())
                <div class="text-center py-5">
                    <h2 class="h5 mb-2">No school requests yet</h2>
                    <p class="text-muted mb-0">Requests submitted through the public form will appear here.</p>
                </div>
            @else
                <div class="text-muted mb-3">
                    Total requests: <strong>{{ $requests->count() }}</strong>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle mb-0">
                        <thead class="table-light">
                        <tr>
                            <th>School</th>
                            <th>Type</th>
                            <th>Town</th>
                            <th>Contact</th>
                            <th>Status</th>
                            <th>Submitted</th>
                            <th class="text-end">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($requests as $requestItem)
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $requestItem->school_name }}</div>
                                    @if ($requestItem->website_url)
                                        <div>
                                            <a href="{{ $requestItem->website_url }}" target="_blank" class="small text-decoration-none">
                                                Website
                                            </a>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                        <span class="badge {{ $requestItem->school_type === 'primary' ? 'text-bg-primary' : 'bg-dark' }}">
                                            {{ ucfirst($requestItem->school_type) }}
                                        </span>
                                </td>
                                <td>{{ $requestItem->town ?: '—' }}</td>
                                <td>
                                    <div>{{ $requestItem->contact_name }}</div>
                                    <div class="small text-muted">{{ $requestItem->contact_email }}</div>
                                </td>
                                <td>
                                        <span class="badge
                                            @if ($requestItem->request_status === 'pending') text-bg-warning
                                            @elseif ($requestItem->request_status === 'approved') text-bg-success
                                            @else text-bg-secondary
                                            @endif">
                                            {{ ucfirst($requestItem->request_status) }}
                                        </span>
                                </td>
                                <td>{{ $requestItem->created_at?->format('d M Y H:i') }}</td>
                                <td class="text-end">
                                    <a href="{{ route('admin.school-requests.show', $requestItem) }}" class="btn btn-sm btn-outline-primary">
                                        View
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection
