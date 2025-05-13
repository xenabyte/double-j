@extends('admin.layout.dashboard')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Job Posting</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active">{{ $jobPosting->title }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Sidebar: Company / Image -->
    <div class="col-xl-4">
        <div class="card overflow-hidden">
            <div class="card-body pt-0">
                <div class="d-flex align-items-start gap-3 mt-3">
                    <div class="flex-shrink-0">
                        <img src="{{ $jobPosting->image ? asset($jobPosting->image) : asset('assets/images/default-job.png') }}" alt="Job Image" class="img-thumbnail rounded" style="width: 100px; height: auto; border-radius: 10px !important;">

                    </div>
                    <div class="flex-grow-1">
                        <h5 class="font-size-15 text-truncate mb-1">
                            {{ $jobPosting->title }}
                        </h5>
                        <p class="text-muted mb-0 text-truncate">Job Opening</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Card -->
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Job Details</h4>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                        <tbody>
                            <tr>
                                <th>Status</th>
                                <td>
                                    <span class="badge bg-{{ $jobPosting->status == 'open' ? 'success' : 'danger' }}">
                                        {{ ucfirst($jobPosting->status) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Applications</th>
                                <td>{{ $jobPosting->applications()->count() }}</td>
                            </tr>
                            <tr>
                                <th>Posted</th>
                                <td>{{ $jobPosting->created_at->diffForHumans() }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Info -->
    <div class="col-xl-8">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Job Description</h4>
                <p>{!! $jobPosting->description ?? '<em>No description provided.</em>' !!}</p>
    
                <hr>
    
                <h4 class="card-title mb-3">Requirements</h4>
                <p>{!! $jobPosting->requirements ?? '<em>No specific requirements.</em>' !!}</p>
            </div>
        </div>
    </div>
    
    
</div>

@endsection
