@extends('client.layout.dashboard')

@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Company Profile</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active">Company Profile</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-xl-4">
        <div class="card overflow-hidden">
            <div class="bg-primary-subtle p-3 d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="text-primary mb-0">Hello {{ $client->name ?? 'User' }}!</h5>
                </div>
                <img src="{{ asset('assets/images/profile-img.png') }}" alt="" class="img-fluid rounded" style="max-width: 120px;">
            </div>

            <div class="card-body pt-0">
                <div class="d-flex align-items-start gap-3 mt-3">
                    <div class="flex-shrink-0">
                        <img src="{{ $client->logo ? asset($client->logo) : asset('assets/images/users/avatar-1.jpg') }}" alt="Logo" class="img-thumbnail rounded" style="width: 100px; height: auto; border-radius: 10px !important;">
                    </div>
                    <div class="flex-grow-1">
                        <h5 class="font-size-15 text-truncate mb-1">{{ $client->company_name ?? 'Company Name' }}</h5>
                        <p class="text-muted mb-0 text-truncate">{{ $client->industry ?? 'Industry' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Company Info</h4>

                @if($client)
                    <div class="table-responsive">
                        <table class="table table-nowrap mb-0">
                            <tbody>
                                <tr>
                                    <th scope="row">Company Name :</th>
                                    <td>{{ $client->company_name }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Industry :</th>
                                    <td>{{ $client->industry }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Company Email :</th>
                                    <td>{{ $client->company_email }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Company Phone :</th>
                                    <td>{{ $client->company_phone }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Contact Person :</th>
                                    <td>{{ $client->name }} ({{ $client->phone }})</td>
                                </tr>
                                <tr>
                                    <th scope="row">Address :</th>
                                    <td>{{ $client->company_address }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted">No Company information available yet.</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-xl-8">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Edit Company Profile</h4>
                <hr>

                <form action="{{ url('/client/updateProfile') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="accordion" id="companyAccordion">

                        <!-- Company Info -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingCompanyInfo">
                                <button class="accordion-button fw-medium" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCompanyInfo" aria-expanded="true" aria-controls="collapseCompanyInfo">
                                    Company Information
                                </button>
                            </h2>
                            <div id="collapseCompanyInfo" class="accordion-collapse collapse show" aria-labelledby="headingCompanyInfo" data-bs-parent="#companyAccordion">
                                <div class="accordion-body">
                                    <div class="row">
                                        <div class="col-lg-6 mb-3">
                                            <label for="company_name" class="form-label">Company Name</label>
                                            <input type="text" name="company_name" class="form-control" value="{{ old('company_name', $client->company_name ?? '') }}">
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <label for="industry" class="form-label">Industry</label>
                                            <input type="text" name="industry" class="form-control" value="{{ old('industry', $client->industry ?? '') }}">
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <label for="company_email" class="form-label">Company Email</label>
                                            <input type="email" name="company_email" class="form-control" value="{{ old('company_email', $client->company_email ?? '') }}">
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <label for="company_phone" class="form-label">Company Phone</label>
                                            <input type="text" name="company_phone" class="form-control" value="{{ old('company_phone', $client->company_phone ?? '') }}">
                                        </div>
                                        <div class="col-lg-12 mb-3">
                                            <label for="company_address" class="form-label">Company Address</label>
                                            <input type="text" name="company_address" class="form-control" value="{{ old('company_address', $client->company_address ?? '') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Person Info -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingContactPerson">
                                <button class="accordion-button fw-medium collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseContactPerson" aria-expanded="false" aria-controls="collapseContactPerson">
                                    Contact Person
                                </button>
                            </h2>
                            <div id="collapseContactPerson" class="accordion-collapse collapse" aria-labelledby="headingContactPerson" data-bs-parent="#companyAccordion">
                                <div class="accordion-body">
                                    <div class="row">
                                        <div class="col-lg-6 mb-3">
                                            <label for="name" class="form-label">Full Name</label>
                                            <input type="text" name="name" class="form-control" value="{{ old('name', $client->name ?? '') }}">
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <label for="phone" class="form-label">Phone Number</label>
                                            <input type="text" name="phone" class="form-control" value="{{ old('phone', $client->phone ?? '') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Logo Upload -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingLogo">
                                <button class="accordion-button fw-medium collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseLogo" aria-expanded="false" aria-controls="collapseLogo">
                                    Upload Company Logo
                                </button>
                            </h2>
                            <div id="collapseLogo" class="accordion-collapse collapse" aria-labelledby="headingLogo" data-bs-parent="#companyAccordion">
                                <div class="accordion-body">
                                    <div class="mb-3">
                                        <label for="logo" class="form-label">Logo (Image)</label>
                                        <input type="file" name="logo" class="form-control" id="logo">
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <hr>
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">Update Profile</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

@endsection
