@extends('admin.layout.dashboard')

@section('content')

<!-- Page Header -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Client Assign</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active">Client Assign</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Tabs for Filtering -->
<ul class="nav nav-tabs mb-3" role="tablist">
    <li class="nav-item">
        <a class="nav-link {{ request('status') == null ? 'active' : '' }}" href="{{ url()->current() }}">All</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request('status') == 'engaged' ? 'active' : '' }}" href="{{ url()->current() . '?status=engaged' }}">Engaged</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ request('status') == 'released' ? 'active' : '' }}" href="{{ url()->current() . '?status=released' }}">Released</a>
    </li>
</ul>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h4 class="card-title mb-0">Assign Employees to Clients</h4>
            </div>
            <div class="card-body">
                <table id="datatable" class="table table-bordered dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Job Title</th>
                            <th>Assigned Client</th>
                            <th>Status</th>
                            <th>Assign</th>
                            <th>Unassign</th>
                            <th>Engage/Release</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($employees as $emp)
                        @php
                            $status = $emp->deleted_at ? 'released' : 'engaged';
                            $filter = request('status');
                        @endphp

                        @if(!$filter || $filter == $status)
                        <tr>
                            <td>{{ $emp->title . ' ' . $emp->last_name . ' ' . $emp->othernames }}</td>
                            <td>{{ $emp->jobPosting->title ?? 'Not Set' }}</td>
                            <td>{{ $emp->client->company_name ?? 'Not Assigned' }}</td>
                            <td>
                                <span class="badge bg-{{ $emp->deleted_at ? 'danger' : 'success' }}">
                                    {{ $emp->deleted_at ? 'Released' : 'Engaged' }}
                                </span>
                            </td>
                            <!-- Modal Trigger -->
                            <td>
                                @if(!$emp->client_id)
                                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#assignModal{{ $emp->id }}">
                                        Assign
                                    </button>

                                    <!-- Modal -->
                                    <div class="modal fade" id="assignModal{{ $emp->id }}" tabindex="-1" aria-labelledby="assignModalLabel{{ $emp->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="assignModalLabel{{ $emp->id }}">Assign to Client</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ url('/admin/assignClientToJob') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="employee_id" value="{{ $emp->id }}">
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label>Client</label>
                                                            <select name="client_id" class="form-select" required>
                                                                <option value="">Choose Client</option>
                                                                @foreach($clients as $client)
                                                                    <option value="{{ $client->id }}">{{ $client->company_name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label>Job</label>
                                                            <select name="job_id" class="form-select" required>
                                                                <option value="">Choose Job</option>
                                                                @foreach($jobPostings as $job)
                                                                    <option value="{{ $job->id }}">{{ $job->title }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-primary">Assign</button>
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>


                            <!-- Unassign -->
                            <td>
                                @if($emp->job_posting_id)
                                    <form method="POST" action="{{ url('/admin/unassignJob') }}">
                                        @csrf
                                        <input type="hidden" name="employee_id" value="{{ $emp->id }}">
                                        <button class="btn btn-warning btn-sm">Unassign</button>
                                    </form>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>

                            <!-- Engage / Release -->
                            <td>
                                @if($emp->deleted_at)
                                    <form method="POST" action="{{ url('/admin/engageEmployee') }}">
                                        @csrf
                                        <input type="hidden" name="employee_id" value="{{ $emp->id }}">
                                        <button class="btn btn-success btn-sm">Engage</button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ url('/admin/disengageEmployee') }}">
                                        @csrf
                                        <input type="hidden" name="employee_id" value="{{ $emp->id }}">
                                        <button class="btn btn-danger btn-sm">Release</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                        @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
