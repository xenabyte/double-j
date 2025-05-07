@extends('applicant.layout.dashboard')

@section('content')

<!-- ============================================================== -->
<!-- Start right Content here -->
<!-- ============================================================== --> 

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Biodata</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active">Biodata</li>
                </ol>
            </div>

        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-xl-4">
        <div class="card overflow-hidden">
            <div class="bg-primary-subtle">
                <div class="row">
                    <div class="col-7">
                        <div class="text-primary p-3">
                            <h5 class="text-primary">Welcome {{ $applicant->last_name ?? 'Applicant' }}!</h5>
                        </div>
                    </div>
                    <div class="col-5 align-self-end">
                        <img src="{{ asset('assets/images/profile-img.png') }}" alt="" class="img-fluid">
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="avatar-md profile-user-wid mb-4">
                            <img src="{{ $applicant->image ? asset($applicant->image) : asset('assets/images/users/avatar-1.jpg') }}" alt="Profile Image" class="img-thumbnail rounded-circle">
                        </div>
                        <h5 class="font-size-15 text">{{ $applicant->last_name .' '. $applicant->othernames ?? 'Cynthia Price' }}</h5>
                        <p class="text-muted mb-0 text-truncate">Applicant</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- end card -->

        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Personal Information</h4>

                @if($applicant)
                    <p class="text-muted mb-4">{{ $applicant->bio }}</p>
                    <div class="table-responsive">
                        <table class="table table-nowrap mb-0">
                            <tbody>
                                <tr>
                                    <th scope="row">Full Name :</th>
                                    <td>{{ $applicant->title .' '. $applicant->last_name .' '. $applicant->othernames}}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Mobile :</th>
                                    <td>{{ $applicant->phone }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">E-mail :</th>
                                    <td>{{ $applicant->email }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Gender :</th>
                                    <td>{{ $applicant->gender }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted">No personal information available yet.</p>
                @endif
            </div>
        </div>
        <!-- end card -->
    </div>         
    
    {{-- <div class="col-xl-8">

        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Biodata Form</h4>
                <hr>
                <form action="{{ url('/applicant/updateBiodata') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" name="title" class="form-control" id="title" value="{{ old('title', $applicant->title ?? '') }}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="othernames" class="form-label">First & Middle Name(s)</label>
                                <input type="text" name="othernames" class="form-control" id="othernames" value="{{ old('othernames', $applicant->othernames ?? '') }}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input type="text" name="last_name" class="form-control" id="last_name" value="{{ old('last_name', $applicant->last_name ?? '') }}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="dob" class="form-label">Date of Birth</label>
                                <input type="date" name="dob" class="form-control" id="dob" value="{{ old('dob', $applicant->dob ?? '') }}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="text" name="phone" class="form-control" id="phone" value="{{ old('phone', $applicant->phone ?? '') }}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="gender" class="form-label">Gender</label>
                                <select name="gender" id="gender" class="form-select">
                                    <option value="">Select Gender</option>
                                    <option value="Male" {{ old('gender', $applicant->gender ?? '') == 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ old('gender', $applicant->gender ?? '') == 'Female' ? 'selected' : '' }}>Female</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label for="address" class="form-label">Address</label>
                                <input type="text" name="address" class="form-control" id="address" value="{{ old('address', $applicant->address ?? '') }}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="city" class="form-label">City</label>
                                <input type="text" name="city" class="form-control" id="city" value="{{ old('city', $applicant->city ?? '') }}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="state" class="form-label">State</label>
                                <input type="text" name="state" class="form-control" id="state" value="{{ old('state', $applicant->state ?? '') }}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="cv" class="form-label">Upload CV (PDF/DOC)</label>
                                <input type="file" name="cv" class="form-control" id="cv">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="cover_letter" class="form-label">Upload Cover Letter (PDF/DOC)</label>
                                <input type="file" name="cover_letter" class="form-control" id="cover_letter">
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="mb-4">
                        <label for="image" class="form-label">Upload Profile Picture</label>
                        <input type="file" name="image" class="form-control" id="image">
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">Update Biodata</button>
                    </div>
                </form>
            </div>
        </div>
    </div> --}}
    <div class="col-xl-8">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Biodata Form</h4>
                <hr>
    
                <form action="{{ url('/applicant/updateBiodata') }}" method="POST" enctype="multipart/form-data">
                    @csrf
    
                    <div class="accordion" id="biodataAccordion">
    
                        <!-- Personal Info -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingPersonal">
                                <button class="accordion-button fw-medium" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePersonal" aria-expanded="true" aria-controls="collapsePersonal">
                                    Personal Information
                                </button>
                            </h2>
                            <div id="collapsePersonal" class="accordion-collapse collapse show" aria-labelledby="headingPersonal" data-bs-parent="#biodataAccordion">
                                <div class="accordion-body">
                                    <div class="row">
                                        <div class="col-lg-6 mb-3">
                                            <label for="title" class="form-label">Title</label>
                                            <input type="text" name="title" class="form-control" id="title" value="{{ old('title', $applicant->title ?? '') }}">
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <label for="othernames" class="form-label">First & Middle Name(s)</label>
                                            <input type="text" name="othernames" class="form-control" id="othernames" value="{{ old('othernames', $applicant->othernames ?? '') }}">
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <label for="last_name" class="form-label">Last Name</label>
                                            <input type="text" name="last_name" class="form-control" id="last_name" value="{{ old('last_name', $applicant->last_name ?? '') }}">
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <label for="dob" class="form-label">Date of Birth</label>
                                            <input type="date" name="dob" class="form-control" id="dob" value="{{ old('dob', $applicant->dob ?? '') }}">
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <label for="phone" class="form-label">Phone Number</label>
                                            <input type="text" name="phone" class="form-control" id="phone" value="{{ old('phone', $applicant->phone ?? '') }}">
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <label for="gender" class="form-label">Gender</label>
                                            <select name="gender" id="gender" class="form-select">
                                                <option value="">Select Gender</option>
                                                <option value="Male" {{ old('gender', $applicant->gender ?? '') == 'Male' ? 'selected' : '' }}>Male</option>
                                                <option value="Female" {{ old('gender', $applicant->gender ?? '') == 'Female' ? 'selected' : '' }}>Female</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
    
                        <!-- Contact Info -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingContact">
                                <button class="accordion-button fw-medium collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseContact" aria-expanded="false" aria-controls="collapseContact">
                                    Contact Information
                                </button>
                            </h2>
                            <div id="collapseContact" class="accordion-collapse collapse" aria-labelledby="headingContact" data-bs-parent="#biodataAccordion">
                                <div class="accordion-body">
                                    <div class="row">
                                        <div class="col-lg-12 mb-3">
                                            <label for="address" class="form-label">Address</label>
                                            <input type="text" name="address" class="form-control" id="address" value="{{ old('address', $applicant->address ?? '') }}">
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <label for="city" class="form-label">City</label>
                                            <input type="text" name="city" class="form-control" id="city" value="{{ old('city', $applicant->city ?? '') }}">
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <label for="state" class="form-label">State</label>
                                            <input type="text" name="state" class="form-control" id="state" value="{{ old('state', $applicant->state ?? '') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
    
                        <!-- Uploads -->
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingUploads">
                                <button class="accordion-button fw-medium collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseUploads" aria-expanded="false" aria-controls="collapseUploads">
                                    Upload Documents
                                </button>
                            </h2>
                            <div id="collapseUploads" class="accordion-collapse collapse" aria-labelledby="headingUploads" data-bs-parent="#biodataAccordion">
                                <div class="accordion-body">
                                    <div class="row">
                                        <div class="col-lg-6 mb-3">
                                            <label for="cv" class="form-label">Upload CV (PDF/DOC)</label>
                                            <input type="file" name="cv" class="form-control" id="cv">
                                        </div>
                                        <div class="col-lg-6 mb-3">
                                            <label for="cover_letter" class="form-label">Upload Cover Letter (PDF/DOC)</label>
                                            <input type="file" name="cover_letter" class="form-control" id="cover_letter">
                                        </div>
                                        <div class="col-lg-12 mb-3">
                                            <label for="image" class="form-label">Upload Profile Picture</label>
                                            <input type="file" name="image" class="form-control" id="image">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
    
                    </div>
    
                    <hr>
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">Update Biodata</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
</div>
<!-- end row -->

@endsection