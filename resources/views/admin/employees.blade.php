@extends('admin.layout.dashboard')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Employees</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active">Assigned Employees</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Employees Table -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header"><h4 class="card-title mb-0">All Employees</h4></div>
            <div class="card-body">
                <table id="datatable" class="table table-bordered dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Job Title</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($employees as $emp)
                        <tr>
                            <td>
                                <img src="{{ $emp->image ? asset($emp->image) : asset('assets/images/users/avatar-1.jpg') }}" alt="avatar" width="40" height="40" class="rounded-circle">
                            </td>
                            <td>{{ $emp->last_name . ' ' . $emp->othernames }}</td>
                            <td>{{ $emp->email }}</td>
                            <td>{{ $emp->phone }}</td>
                            <td>{{ $emp->jobPosting->title ?? 'N/A' }}</td>
                            <td>
                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#viewEmployeeModal{{ $emp->id }}">
                                    <i class="mdi mdi-eye"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- Employee Detail Modals --}}
                @foreach($employees as $emp)
                    <div class="modal fade" id="viewEmployeeModal{{ $emp->id }}" tabindex="-1" aria-labelledby="modalLabel{{ $emp->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-xl modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalLabel{{ $emp->id }}">
                                        {{ $emp->last_name . ' ' . $emp->othernames }}'s Profile
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>

                                <div class="modal-body">
                                    <div class="row">
                                        <!-- Sidebar -->
                                        <div class="col-md-4">
                                            <div class="text-center mb-3">
                                                <img src="{{ $emp->image ? asset($emp->image) : asset('assets/images/users/avatar-1.jpg') }}" alt="Profile" class="img-thumbnail" width="120">
                                                <h5 class="mt-2">{{ $emp->title . ' ' .$emp->last_name . ' ' . $emp->othernames }}</h5>
                                                <p class="text-muted mb-0">Employee</p>
                                            </div>

                                            <h6 class="text-muted mt-4">Personal Info</h6>
                                            <table class="table table-sm dt-responsive nowrap w-100">
                                                <tr><th>Mobile</th><td>{{ $emp->phone }}</td></tr>
                                                <tr><th>Email</th><td>{{ $emp->email }}</td></tr>
                                                <tr><th>Gender</th><td>{{ $emp->gender }}</td></tr>
                                                <tr><th>DOB</th><td>{{ \Carbon\Carbon::parse($emp->dob)->format('d M, Y') }}</td></tr>
                                                <tr><th>Address</th><td>{{ $emp->address }}</td></tr>
                                                <tr><th>City</th><td>{{ $emp->city }}</td></tr>
                                                <tr><th>State</th><td>{{ $emp->state }}</td></tr>
                                                <tr>
                                                    <th>Resume (CV)</th>
                                                    <td>
                                                        @if($emp->cv)
                                                            <a href="{{ asset($emp->cv) }}" target="_blank">View CV</a>
                                                        @else
                                                            <span class="text-muted">Not uploaded</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Cover Letter</th>
                                                    <td>
                                                        @if($emp->cover_letter)
                                                            <a href="{{ asset($emp->cover_letter) }}" target="_blank">View Cover Letter</a>
                                                        @else
                                                            <span class="text-muted">Not uploaded</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>

                                        <!-- Detailed Info -->
                                        <div class="col-md-8">
                                            <h6 class="text-muted">Additional Details</h6>
                                            <table class="table table-sm table-bordered">
                                                <tr>
                                                    <th>Job Title</th>
                                                    <td>{{ $emp->jobPosting->title ?? 'N/A' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Job Description</th>
                                                    <td>{!! $emp->jobPosting->description ?? 'N/A' !!}</td>
                                                </tr>
                                                <tr>
                                                    <th>Requirements</th>
                                                    <td>{!! $emp->jobPosting->requirements ?? 'N/A' !!}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>
</div>

@endsection
