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
                            <th>Submitted At</th>
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
                                    <a href="#" class="btn btn-primary m-1">
                                        <i class="mdi mdi-eye"></i>
                                    </a>
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