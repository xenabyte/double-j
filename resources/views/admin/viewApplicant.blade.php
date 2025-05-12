@extends('admin.layout.dashboard')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Applicants</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ url('#') }}">{{ $applicant->last_name . ' ' . $applicant->othernames }}</a></li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Profile Sidebar -->
    <div class="col-xl-4">
        <div class="card overflow-hidden">
            <div class="card-body pt-0">
                <div class="d-flex align-items-start gap-3 mt-3">
                    <div class="flex-shrink-0">
                        <img src="{{ $applicant->image ? asset($applicant->image) : asset('assets/images/users/avatar-1.jpg') }}" alt="Profile Image" class="img-thumbnail rounded" style="width: 100px;">
                    </div>
                    <div class="flex-grow-1">
                        <h5 class="font-size-15 text-truncate mb-1">
                            {{ $applicant->last_name . ' ' . $applicant->othernames }}
                        </h5>
                        <p class="text-muted mb-0 text-truncate">Applicant</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Personal Info Card -->
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Personal Information</h4>

                @if($applicant)
                    <p class="text-muted mb-4">{{ $applicant->bio }}</p>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <tbody>
                                <tr>
                                    <th>Full Name</th>
                                    <td>{{ $applicant->title .' '. $applicant->last_name .' '. $applicant->othernames }}</td>
                                </tr>
                                <tr>
                                    <th>Mobile</th>
                                    <td>{{ $applicant->phone }}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>{{ $applicant->email }}</td>
                                </tr>
                                <tr>
                                    <th>Gender</th>
                                    <td>{{ $applicant->gender }}</td>
                                </tr>
                                <tr>
                                    <th>Date of Birth</th>
                                    <td>{{ \Carbon\Carbon::parse($applicant->dob)->format('d M, Y') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted">No personal data found.</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Contact Info & Documents -->
    <div class="col-xl-8">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Updated Information</h4>
                <table class="table table-sm table-bordered">
                    <tbody>
                        <tr>
                            <th>Address</th>
                            <td>{{ $applicant->address }}</td>
                        </tr>
                        <tr>
                            <th>City</th>
                            <td>{{ $applicant->city }}</td>
                        </tr>
                        <tr>
                            <th>State</th>
                            <td>{{ $applicant->state }}</td>
                        </tr>
                        <tr>
                            <th>CV</th>
                            <td>
                                @if($applicant->cv)
                                    <a href="{{ asset($applicant->cv) }}" target="_blank" class="text-primary">View CV</a>
                                @else
                                    <span class="text-muted">Not uploaded</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Cover Letter</th>
                            <td>
                                @if($applicant->cover_letter)
                                    <a href="{{ asset($applicant->cover_letter) }}" target="_blank" class="text-primary">View Cover Letter</a>
                                @else
                                    <span class="text-muted">Not uploaded</span>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
