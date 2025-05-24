@extends('admin.layout.dashboard')

@section('content')

<!-- Page Header -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Job Postings</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active">Job Postings</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Job Postings Table -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h4 class="card-title mb-0">All Job Postings</h4>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addJobPosting">Add Job Posting</button>
            </div>
            <div class="card-body">
                <table id="datatable" class="table table-bordered dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Set Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($jobPostings as $job)
                            <tr>
                                <td>{{ $job->title }}</td>
                                <td>
                                    <span class="btn btn-{{ $job->status === 'open' ? 'success' : 'danger' }} btn-sm m-1 disabled">
                                        {{ ucfirst($job->status) }}
                                    </span>
                                </td>
                                <td>{{ $job->created_at->format('M d, Y') }}</td>
                                <td>
                                    <form action="{{ url('admin/setJobStatus') }}" method="POST" class="d-flex flex-wrap gap-1">
                                        @csrf
                                        <input type="hidden" name="job_id" value="{{ $job->id }}">
                                        @foreach(['open', 'closed'] as $status)
                                            <button 
                                                type="submit" 
                                                name="status" 
                                                value="{{ $status }}" 
                                                class="btn btn-sm 
                                                    {{ $job->status === $status 
                                                        ? ($status === 'open' ? 'btn-success' : 'btn-danger') 
                                                        : 'btn-outline-secondary' 
                                                    }}">
                                                {{ ucfirst($status) }}
                                            </button>
                                        @endforeach
                                    </form>                                    
                                </td>
                                <td>
                                    <a href="{{ url('admin/viewJobPosting/'.$job->slug) }}" class="btn btn-primary m-1">
                                        <i class="mdi mdi-eye"></i>
                                    </a>
                                    <button type="button" class="btn btn-info m-1" data-bs-toggle="modal" data-bs-target="#editJobPosting{{ $job->id }}">
                                        <i class="mdi mdi-pencil"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger m-1" data-bs-toggle="modal" data-bs-target="#deleteJobPosting{{ $job->id }}">
                                        <i class="mdi mdi-delete"></i>
                                    </button>                                    
                                </td>
                            </tr>

                            <!-- Edit Job Posting Modal -->
                            <div class="modal fade" id="editJobPosting{{ $job->id }}" tabindex="-1" aria-labelledby="editJobPostingLabel{{ $job->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-xl">
                                    <form action="{{ url('/admin/updateJobPosting') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="job_id" value="{{ $job->id }}">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editJobPostingLabel{{ $job->id }}">Edit Job Posting</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body row g-3">
                                                <div class="col-md-6">
                                                    <label>Title *</label>
                                                    <input type="text" name="title" class="form-control" value="{{ $job->title }}" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Status *</label>
                                                    <select name="status" class="form-control" required>
                                                        <option value="open" {{ $job->status === 'open' ? 'selected' : '' }}>Open</option>
                                                        <option value="closed" {{ $job->status === 'closed' ? 'selected' : '' }}>Closed</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-12">
                                                    <label>Image</label>
                                                    <input type="file" name="image" class="form-control" accept="image/*">
                                                    @if($job->image)
                                                        <small>Current: <a href="{{ asset($job->image) }}" target="_blank">View Image</a></small>
                                                    @endif
                                                </div>
                                                <div class="col-md-12">
                                                    <label>Description *</label>
                                                    <textarea name="description" class="form-control" rows="4" >{{ $job->description }}</textarea>
                                                </div>
                                                <div class="col-md-12">
                                                    <label>Requirements</label>
                                                    <textarea name="requirements" class="form-control" rows="4">{{ $job->requirements }}</textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-success">Save Changes</button>
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- Delete Job Posting Modal -->
                            <div class="modal fade" id="deleteJobPosting{{ $job->id }}" tabindex="-1" aria-labelledby="deleteJobPostingLabel{{ $job->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <form action="{{ url('/admin/deleteJobPosting') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="job_id" value="{{ $job->id }}">
                                        <div class="modal-content">
                                            <div class="modal-body text-center p-5">
                                                <button type="button" class="btn-close float-end" data-bs-dismiss="modal" aria-label="Close"></button>
                                                <div class="mt-2">
                                                    <h4 class="mb-3 mt-4">Are you sure you want to delete this job posting?</h4>
                                                    <p>{{ $job->title }}</p>
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

<!-- Add Job Posting Modal -->
<div class="modal fade" id="addJobPosting" tabindex="-1" aria-labelledby="addJobPostingLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <form action="{{ url('/admin/newJobPosting') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addJobPostingLabel">Add New Job Posting</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body row g-3">
                    <div class="col-md-6">
                        <label>Title *</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label>Status *</label>
                        <select name="status" class="form-control" required>
                            <option value="open" selected>Open</option>
                            <option value="closed">Closed</option>
                        </select>
                    </div>
                    <div class="col-md-12">
                        <label>Image</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                    </div>
                    <div class="col-md-12">
                        <label>Description *</label>
                        <textarea name="description" rows="4" ></textarea>
                    </div>
                    <div class="col-md-12">
                        <label>Requirements</label>
                        <textarea name="requirements" rows="4"></textarea>
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Save Job Posting</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>


@endsection
