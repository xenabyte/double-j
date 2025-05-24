@extends('client.layout.dashboard')

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

<!-- New Job Request Button -->
<div class="mb-3 text-end">
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#jobRequestModal">
        New Job Request
    </button>
</div>

<!-- Job Requests Table -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">Your Job Requests</h4>
            </div>
            <div class="card-body">
                <table id="datatable" class="table table-bordered dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Vacancies</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($jobRequests as $job)
                        <tr>
                            <td><img src="{{ $job->image ? asset($job->image) : asset('assets/images/brands/mail_chimp.png') }}" alt="avatar" width="40" height="40" class="rounded-circle"></td>
                            <td>{{ $job->job_title }}</td>
                            <td>{{ $job->vacancies }}</td>
                            <td>
                                <span class="badge bg-{{ $job->status == 'approved' ? 'success' : ($job->status == 'pending' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($job->status) }}
                                </span>
                            </td>
                            <td>
                                <!-- View -->
                                <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#viewModal{{ $job->id }}"><i class="mdi mdi-eye"></i></button>
                                <!-- Edit -->
                                <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#editModal{{ $job->id }}"><i class="mdi mdi-pen"></i></button>
                                <!-- Delete -->
                                <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $job->id }}"><i class="mdi mdi-delete"></i></button>
                            </td>
                        </tr>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editModal{{ $job->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $job->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-xl">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Job Request</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>

                                    <form action="{{ url('client/updateJobRequest') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="client_id" value="{{ auth()->user()->id }}">
                                        <input type="hidden" name="job_id" value="{{ $job->id }}">

                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Job Title</label>
                                                <input type="text" name="job_title" class="form-control" value="{{ $job->job_title }}" required>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Number of Vacancies</label>
                                                <input type="number" name="vacancies" class="form-control" value="{{ $job->vacancies }}" required>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Job Poster (optional)</label>
                                                <input type="file" name="image" class="form-control">
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Description</label>
                                                <textarea name="description" class="form-control" rows="4" required>{{ $job->description }}</textarea>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Requirements</label>
                                                <textarea name="requirements" class="form-control" rows="4" required>{{ $job->requirements }}</textarea>
                                            </div>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-success">Update</button>
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- View Modal -->
                        <div class="modal fade" id="viewModal{{ $job->id }}" tabindex="-1" aria-labelledby="viewModalLabel{{ $job->id }}" aria-hidden="true">
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
                        <div class="modal fade" id="deleteModal{{ $job->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $job->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    
                                    <form action="{{ url('client/deleteJobRequest') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="job_id" value="{{ $job->id }}">

                                        <div class="modal-body">
                                            <p>Are you sure you want to delete the job request: <strong>{{ $job->job_title }}</strong>?</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-danger">Yes, Delete</button>
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- New Job Request Modal -->
<div class="modal fade" id="jobRequestModal" tabindex="-1" aria-labelledby="jobRequestModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">New Job Request</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ url('client/newJobRequest') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="client_id" value="{{ auth()->user()->id }}">

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="job_title" class="form-label">Job Title</label>
                        <input type="text" class="form-control" name="job_title" id="job_title" required>
                    </div>

                    <div class="mb-3">
                        <label for="vacancies" class="form-label">Number of Vacancies</label>
                        <input type="number" class="form-control" name="vacancies" id="vacancies" min="1" required>
                    </div>

                    <div class="mb-3">
                        <label for="image" class="form-label">Job Poster</label>
                        <input type="file" class="form-control" name="image" id="image" accept="image/*">
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Job Description</label>
                        <textarea name="description" id="description" class="form-control" rows="4" ></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="requirements" class="form-label">Job Requirements</label>
                        <textarea name="requirements" id="requirements" class="form-control" rows="4" ></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Submit Request</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
