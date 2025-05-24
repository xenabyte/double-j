@extends('admin.layout.dashboard')

@section('content')

<!-- Page Header -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Job Requests</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active">Job Requests</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Job Requests Table -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h4 class="card-title mb-0">All Job Requests</h4>
            </div>
            <div class="card-body">
                <table id="datatable" class="table table-bordered dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Client</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Set Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($jobRequests as $job)
                            <tr>
                                <td>{{ $job->job_title }}</td>
                                <td>{{ $job->client->company_name ?? 'N/A' }}</td>
                                <td>
                                    <span class="btn btn-sm 
                                        @if($job->status === 'approved') btn-success 
                                        @elseif($job->status === 'rejected') btn-danger 
                                        @else btn-warning 
                                        @endif disabled">
                                        {{ ucfirst($job->status) }}
                                    </span>
                                </td>
                                <td>{{ $job->created_at->format('M d, Y') }}</td>
                                <td>
                                    <form action="{{ url('admin/setJobRequestStatus') }}" method="POST" class="d-flex flex-wrap gap-1">
                                        @csrf
                                        <input type="hidden" name="job_id" value="{{ $job->id }}">
                                        @foreach(['pending', 'approved', 'rejected'] as $status)
                                            <button 
                                                type="submit" 
                                                name="status" 
                                                value="{{ $status }}" 
                                                class="btn btn-sm 
                                                    {{ $job->status === $status 
                                                        ? ($status === 'approved' ? 'btn-success' : ($status === 'rejected' ? 'btn-danger' : 'btn-warning')) 
                                                        : 'btn-outline-secondary' 
                                                    }}">
                                                {{ ucfirst($status) }}
                                            </button>
                                        @endforeach
                                    </form>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-primary m-1" data-bs-toggle="modal" data-bs-target="#viewJobRequest{{ $job->id }}">
                                        <i class="mdi mdi-eye"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger m-1" data-bs-toggle="modal" data-bs-target="#deleteJobRequest{{ $job->id }}">
                                        <i class="mdi mdi-delete"></i>
                                    </button>

                                    @if($job->status === 'approved')
                                        <button type="button" 
                                                class="btn btn-success m-1" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#convertJobRequest{{ $job->id }}"
                                                data-bs-toggle="tooltip" 
                                                data-bs-placement="top" 
                                                title="Convert to Posting">
                                            <i class="mdi mdi-briefcase-check"></i>
                                        </button>
                                    @endif

                                </td>
                            </tr>

                            <!-- Convert to Posting Modal -->
                            <div class="modal fade" id="convertJobRequest{{ $job->id }}" tabindex="-1" aria-labelledby="convertJobRequestLabel{{ $job->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <form action="{{ url('/admin/jobRequestToPosting') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="job_id" value="{{ $job->id }}">
                                        <div class="modal-content">
                                            <div class="modal-body text-center p-5">
                                                <button type="button" class="btn-close float-end" data-bs-dismiss="modal" aria-label="Close"></button>
                                                <div class="mt-2">
                                                    <h4 class="mb-3 mt-4">Convert to Job Posting?</h4>
                                                    <p>This will turn <strong>{{ $job->job_title }}</strong> into an active job posting.</p>
                                                    <button type="submit" class="btn btn-success w-100">Yes, Convert</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>


                            <!-- View Modal -->
                            <div class="modal fade" id="viewJobRequest{{ $job->id }}" tabindex="-1" aria-labelledby="viewJobRequestLabel{{ $job->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-xl">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Job Request Details</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>

                                        <div class="modal-body">
                                            <div class="row mb-4 align-items-center">
                                                @if ($job->image)
                                                <div class="col-md-4 text-center">
                                                    <img src="{{ asset($job->image) }}" alt="Job Poster" class="img-fluid rounded" style="max-height: 250px; object-fit: cover;">
                                                </div>
                                                @endif

                                                <div class="col-md-8">
                                                    <h4 class="fw-bold mb-3">{{ $job->job_title }}</h4>
                                                    <p>
                                                        <span class="fw-bold">Vacancies:</span> 
                                                        <span class="badge bg-primary fs-6">{{ $job->vacancies }}</span>
                                                    </p>
                                                    <p>
                                                        <span class="fw-bold">Status:</span>
                                                        <span class="badge bg-{{ $job->status == 'approved' ? 'success' : ($job->status == 'pending' ? 'warning' : 'danger') }}">
                                                            {{ ucfirst($job->status) }}
                                                        </span>
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Description:</label>
                                                <div>{!! $job->description !!}</div>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Requirements:</label>
                                                <div>{!! $job->requirements !!}</div>
                                            </div>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Delete Modal -->
                            <div class="modal fade" id="deleteJobRequest{{ $job->id }}" tabindex="-1" aria-labelledby="deleteJobRequestLabel{{ $job->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <form action="{{ url('/admin/deleteJobRequest') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="job_id" value="{{ $job->id }}">
                                        <div class="modal-content">
                                            <div class="modal-body text-center p-5">
                                                <button type="button" class="btn-close float-end" data-bs-dismiss="modal" aria-label="Close"></button>
                                                <div class="mt-2">
                                                    <h4 class="mb-3 mt-4">Are you sure you want to delete this job request?</h4>
                                                    <p><strong>{{ $job->job_title }} by {{ $job->client->company_name }}</strong></p>
                                                    <button type="submit" class="btn btn-danger w-100">Yes, Delete</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
