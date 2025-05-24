@extends('admin.layout.dashboard')

@section('content')

<!-- Page Header -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Applications</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active">Applications</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Applications Table -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">All Applications</h4>
            </div>
            <div class="card-body">
                <table id="datatable" class="table table-bordered dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>Applicant</th>
                            <th>Job Title</th>
                            <th>Status</th>
                            <th>Submitted At</th>
                            <th>Change Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($applications as $app)
                            <tr>
                                <td>{{ $app->applicant->title . ' ' . $app->applicant->last_name .' '. $app->applicant->othernames ?? 'N/A' }}</td>
                                <td>{{ $app->jobPosting->title ?? 'N/A' }}</td>
                                <td>
                                    <span class="btn btn-sm m-1 
                                        {{ 
                                            $app->status === 'pending' ? 'btn-warning' : 
                                            ($app->status === 'reviewed' ? 'btn-info' :
                                            ($app->status === 'accepted' ? 'btn-success' : 'btn-danger'))
                                        }} disabled">
                                        {{ ucfirst($app->status) }}
                                    </span>
                                </td>
                                <td>{{ $app->created_at->format('M d, Y') }}</td>
                                <td>
                                    <form action="{{ url('admin/setApplicationStatus') }}" method="POST" class="d-flex flex-wrap gap-1">
                                        @csrf
                                        <input type="hidden" name="application_id" value="{{ $app->id }}">
                                        @foreach(['pending', 'reviewed', 'accepted', 'rejected'] as $status)
                                            <button 
                                                type="submit" 
                                                name="status" 
                                                value="{{ $status }}" 
                                                class="btn btn-sm 
                                                    {{ $app->status === $status 
                                                        ? ($status === 'accepted' ? 'btn-success' : ($status === 'rejected' ? 'btn-danger' : ($status === 'reviewed' ? 'btn-info' : 'btn-warning')))
                                                        : 'btn-outline-secondary'
                                                    }}">
                                                {{ ucfirst($status) }}
                                            </button>
                                        @endforeach
                                    </form>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-primary m-1" data-bs-toggle="modal" data-bs-target="#viewAppModal{{ $app->id }}">
                                        <i class="mdi mdi-eye"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                
                {{-- @foreach($applications as $app)
                    <div class="modal fade" id="viewAppModal{{ $app->id }}" tabindex="-1" aria-labelledby="viewAppModalLabel{{ $app->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title d-flex align-items-center gap-2" id="viewAppModalLabel{{ $app->id }}">
                                        <span>{{ $app->jobPosting->title ?? 'N/A' }}</span>
                                        <span class="badge 
                                            {{ 
                                                $app->status === 'pending' ? 'bg-warning' : 
                                                ($app->status === 'reviewed' ? 'bg-info' :
                                                ($app->status === 'accepted' ? 'bg-success' : 'bg-danger')) 
                                            }}">
                                            {{ ucfirst($app->status) }}
                                        </span>
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>

                                <div class="modal-body">
                                    <p><strong>Job Title:</strong> <br> {!! $app->jobPosting->title ?? 'N/A' !!}</p>
                                    <p><strong>Description:</strong><br>{!! $app->jobPosting->description ?? 'N/A' !!}</p>
                                    <p><strong>Requirements:</strong><br>{!! $app->jobPosting->requirements ?? 'N/A' !!}</p>
                                    <p><strong>Applied On:</strong> {{ $app->created_at->format('F j, Y') }}</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach --}}

                @foreach($applications as $app)
                    <div class="modal fade" id="viewAppModal{{ $app->id }}" tabindex="-1" aria-labelledby="viewAppModalLabel{{ $app->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-xl modal-dialog-scrollable modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title d-flex align-items-center gap-2" id="viewAppModalLabel{{ $app->id }}">
                                        Application for {{ $app->jobPosting->title ?? 'N/A' }}
                                        <span class="badge 
                                            {{ 
                                                $app->status === 'pending' ? 'bg-warning' : 
                                                ($app->status === 'reviewed' ? 'bg-info' :
                                                ($app->status === 'accepted' ? 'bg-success' : 'bg-danger')) 
                                            }}">
                                            {{ ucfirst($app->status) }}
                                        </span>
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>

                                <div class="modal-body">
                                    <div class="row">
                                        <!-- Sidebar -->
                                        <div class="col-md-4">
                                            <div class="text-center mb-3">
                                                <img src="{{ $app->applicant->image ? asset($app->applicant->image) : asset('assets/images/users/avatar-1.jpg') }}" class="img-thumbnail" width="120" alt="Applicant Image">
                                                <h5 class="mt-2">{{ $app->applicant->title . ' ' . $app->applicant->last_name . ' ' . $app->applicant->othernames }}</h5>
                                                <p class="text-muted mb-0">Applicant</p>
                                            </div>

                                            <h6 class="text-muted mt-4">Personal Info</h6>
                                            <table class="table table-sm w-100">
                                                <tr><th>Phone</th><td>{{ $app->applicant->phone }}</td></tr>
                                                <tr><th>Email</th><td>{{ $app->applicant->email }}</td></tr>
                                                <tr><th>Gender</th><td>{{ $app->applicant->gender }}</td></tr>
                                                <tr><th>DOB</th><td>{{ \Carbon\Carbon::parse($app->applicant->dob)->format('d M, Y') }}</td></tr>
                                                <tr><th>City</th><td>{{ $app->applicant->city }}</td></tr>
                                                <tr><th>State</th><td>{{ $app->applicant->state }}</td></tr>
                                                {{-- <tr>
                                                    <th>Resume</th>
                                                    <td>
                                                        @if($app->cv)
                                                            <a href="{{ asset($app->applicant->cv) }}" target="_blank">View CV</a>
                                                        @else
                                                            <span class="text-muted">Not uploaded</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Cover Letter</th>
                                                    <td>
                                                        @if($app->cover_letter)
                                                            <a href="{{ asset($app->applicant->cover_letter) }}" target="_blank">View Cover Letter</a>
                                                        @else
                                                            <span class="text-muted">Not uploaded</span>
                                                        @endif
                                                    </td>
                                                </tr> --}}

                                                <tr>
                                                    <th>Resume</th>
                                                    <td>
                                                        @if($app->applicant && $app->applicant->cv)
                                                            <a href="{{ asset($app->applicant->cv) }}" target="_blank">View CV</a>
                                                        @else
                                                            <span class="text-muted">Not uploaded</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Cover Letter</th>
                                                    <td>
                                                        @if($app->applicant && $app->applicant->cover_letter)
                                                            <a href="{{ asset($app->applicant->cover_letter) }}" target="_blank">View Cover Letter</a>
                                                        @else
                                                            <span class="text-muted">Not uploaded</span>
                                                        @endif
                                                    </td>
                                                </tr>

                                            </table>
                                        </div>

                                        <!-- Job Posting Info -->
                                        <div class="col-md-8">
                                            <h6 class="text-muted">Job Details</h6>
                                            <table class="table table-bordered table-sm">
                                                <tr>
                                                    <th>Job Title</th>
                                                    <td>{{ $app->jobPosting->title ?? 'N/A' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Description</th>
                                                    <td>{!! $app->jobPosting->description ?? 'N/A' !!}</td>
                                                </tr>
                                                <tr>
                                                    <th>Requirements</th>
                                                    <td>{!! $app->jobPosting->requirements ?? 'N/A' !!}</td>
                                                </tr>
                                                <tr>
                                                    <th>Applied On</th>
                                                    <td>{{ $app->created_at->format('F j, Y') }}</td>
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
