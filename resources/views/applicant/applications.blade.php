@extends('applicant.layout.dashboard')

@section('content')

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
                            <th>Job Title</th>
                            <th>Application Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($applications as $app)
                            <tr>
                                <td>{{ $app->jobPosting->title ?? 'N/A' }}</td>
                                <td>{{ $app->created_at->format('M d, Y') }}</td>
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
                                <td>
                                    <button type="button" class="btn btn-primary m-1" data-bs-toggle="modal" data-bs-target="#viewAppModal{{ $app->id }}">
                                        <i class="mdi mdi-eye"></i>
                                    </button>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>

                @foreach($applications as $app)
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
                @endforeach

            </div>
        </div>
    </div>
</div>


@endsection