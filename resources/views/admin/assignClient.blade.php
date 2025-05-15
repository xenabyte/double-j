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
                            <th>Engage/Disengage</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($employees as $emp)
                        <tr>
                            <td>{{ $emp->title . ' ' . $emp->last_name . ' ' . $emp->othernames}}</td>
                            <td>{{ $emp->jobPosting->title ?? 'Not Set' }}</td>
                            <td>{{ $emp->client->company_name ?? 'Not Assigned' }}</td>
                            <td>
                                <span class="badge bg-{{ $emp->deleted_at ? 'danger' : 'success' }}">
                                    {{ $emp->deleted_at ? 'Disengaged' : 'Engaged' }}
                                </span>
                            </td>
                            <td>
                                <form action="{{ url('/admin/assignClientToJob') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="employee_id" value="{{ $emp->id }}">
                                    <select name="client_id" class="form-select d-inline-block w-auto" required>
                                        <option value="">Choose Client</option>
                                        @foreach($clients as $client)
                                            <option value="{{ $client->id }}">{{ $client->company_name }}</option>
                                        @endforeach
                                    </select>
                                    <select name="job_id" class="form-select d-inline-block w-auto" required>
                                        <option value="">Choose Job</option>
                                        @foreach($jobPostings as $job)
                                            <option value="{{ $job->id }}">{{ $job->title }}</option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="btn btn-sm btn-primary">Assign</button>
                                </form>
                            </td>
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
                                        <button class="btn btn-danger btn-sm">Disengage</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
