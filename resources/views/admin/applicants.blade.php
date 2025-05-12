@extends('admin.layout.dashboard')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Applicants</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active">Applicants</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1"></h4>
                <div class="flex-shrink-0">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addApplicant">Add Applicant</button>
                </div>
            </div><!-- end card header -->
            <div class="card-body">
                <table id="datatable" class="table table-bordered dt-responsive nowrap w-100">
                    <thead>
                    <tr>
                        <th>Lastname</th>
                        <th>Othernames</th>
                        <th>Email</th>
                        <th>Phone Number</th>
                        <th>City</th>
                        <th>Actions</th>
                    </tr>
                    </thead>

                    <tbody>
                        @foreach($applicants as $applicant)
                            <tr>
                                <td>{{ $applicant->last_name }}</td>
                                <td>{{ $applicant->othernames }}</td>
                                <td>{{ $applicant->email }}</td>
                                <td>{{ $applicant->phone }}</td>
                                <td>{{ $applicant->city }}</td>
                                <td>
                                    <a href="{{ url('admin/viewApplicant/'.$applicant->slug) }}" class="btn btn-primary m-1">
                                        <i class="mdi mdi-comment-eye"></i>
                                    </a>                                    
                                    <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#editApplicant{{$applicant->id}}" class="btn btn-info"><i class="mdi mdi-account-edit"></i></a>
                                    <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#deleteApplicant{{$applicant->id}}" class="btn btn-danger"><i class="mdi mdi-delete"></i></a>
                                </td>
                            </tr>

                            <!-- Add Applicant Modal -->
                            <div class="modal fade" id="addApplicant" tabindex="-1" aria-labelledby="addApplicantLabel" aria-hidden="true">
                                <div class="modal-dialog modal-xl">
                                <form action="{{ url('/admin/newApplicant') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="addApplicantLabel">Add New Applicant</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body row g-3">
                                        <div class="col-md-6">
                                        <label>Title</label>
                                        <input type="text" name="title" class="form-control">
                                        </div>
                                        <div class="col-md-6">
                                        <label>Other Names *</label>
                                        <input type="text" name="othernames" class="form-control" required>
                                        </div>
                                        <div class="col-md-6">
                                        <label>Last Name *</label>
                                        <input type="text" name="last_name" class="form-control" required>
                                        </div>
                                        <div class="col-md-6">
                                        <label>Date of Birth *</label>
                                        <input type="date" name="dob" class="form-control" required>
                                        </div>
                                        <div class="col-md-6">
                                        <label>Email *</label>
                                        <input type="email" name="email" class="form-control" required>
                                        </div>
                                        <div class="col-md-6">
                                        <label>Phone *</label>
                                        <input type="text" name="phone" class="form-control" required>
                                        </div>
                                        <div class="col-md-6">
                                        <label>Address</label>
                                        <input type="text" name="address" class="form-control">
                                        </div>
                                        <div class="col-md-6">
                                        <label>City</label>
                                        <input type="text" name="city" class="form-control">
                                        </div>
                                        <div class="col-md-6">
                                        <label>State</label>
                                        <input type="text" name="state" class="form-control">
                                        </div>
                                        <div class="col-md-6">
                                        <label>Gender *</label>
                                        <select name="gender" class="form-control" required>
                                            <option value="">Select</option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                            <option value="Other">Other</option>
                                        </select>
                                        </div>
                                        <div class="col-md-6">
                                        <label>Password *</label>
                                        <input type="password" name="password" class="form-control" required>
                                        </div>
                                        <div class="col-md-6">
                                        <label>Confirm Password *</label>
                                        <input type="password" name="password_confirmation" class="form-control" required>
                                        </div>
                                        <div class="col-md-6">
                                        <label>Profile Image</label>
                                        <input type="file" name="image" class="form-control">
                                        </div>
                                        <div class="col-md-6">
                                        <label>CV (PDF/DOC)</label>
                                        <input type="file" name="cv" class="form-control">
                                        </div>
                                        <div class="col-md-6">
                                        <label>Cover Letter (PDF/DOC)</label>
                                        <input type="file" name="cover_letter" class="form-control">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-success">Save Applicant</button>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                    </div>
                                </form>
                                </div>
                            </div>
  

                            <!-- Edit Modal -->
                            <div id="editApplicant{{$applicant->id}}" class="modal fade" tabindex="-1" aria-hidden="true" style="display: none;">
                                <div class="modal-dialog modal-dialog-centered modal-xl">
                                    <div class="modal-content border-0 overflow-hidden">
                                        <div class="modal-header p-3">
                                            <h4 class="card-title mb-0">Update Applicant</h4>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ url('/admin/updateApplicant') }}" method="post" enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" name="applicant_id" value="{{ $applicant->id }}">
                            
                                                <div class="row">
                                                    <!-- Left Column: Image and Upload -->
                                                    <div class="col-md-3 text-center mb-4">
                                                        <div>
                                                            <img src="{{ $applicant->image ? asset($applicant->image) : asset('assets/images/users/avatar-1.jpg') }}"
                                                                 alt="Applicant Image"
                                                                 class="img-thumbnail mb-2"
                                                                 style="width: 100%; max-width: 180px; height: auto; object-fit: cover; border-radius: 10px;">
                                                        </div>
                                                        <label class="form-label mt-2">Change Image (JPG/PNG)</label>
                                                        {{-- <input type="file" class="form-control" name="image" accept="image/*"> --}}
                                                        <input type="file" class="form-control" name="image" accept="image/*" onchange="previewImage(this, 'image-preview-{{ $applicant->id }}')">
                                                    </div>
                            
                                                    <!-- Right Column: Form Fields -->
                                                    <div class="col-md-9">
                                                        <div class="row">
                                                            <div class="col-md-6 mb-3">
                                                                <label for="email" class="form-label">Email</label>
                                                                <input type="email" class="form-control" name="email" value="{{ $applicant->email }}">
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label for="title" class="form-label">Title</label>
                                                                <input type="text" class="form-control" name="title" value="{{ $applicant->title }}">
                                                            </div>
                            
                                                            <div class="col-md-6 mb-3">
                                                                <label for="othernames" class="form-label">Other Names</label>
                                                                <input type="text" class="form-control" name="othernames" value="{{ $applicant->othernames }}">
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label for="last_name" class="form-label">Last Name</label>
                                                                <input type="text" class="form-control" name="last_name" value="{{ $applicant->last_name }}">
                                                            </div>
                            
                                                            <div class="col-md-6 mb-3">
                                                                <label for="dob" class="form-label">Date of Birth</label>
                                                                <input type="date" class="form-control" name="dob" value="{{ $applicant->dob }}">
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label for="phone" class="form-label">Phone</label>
                                                                <input type="text" class="form-control" name="phone" value="{{ $applicant->phone }}">
                                                            </div>
                            
                                                            <div class="col-md-6 mb-3">
                                                                <label for="address" class="form-label">Address</label>
                                                                <input type="text" class="form-control" name="address" value="{{ $applicant->address }}">
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label for="city" class="form-label">City</label>
                                                                <input type="text" class="form-control" name="city" value="{{ $applicant->city }}">
                                                            </div>
                            
                                                            <div class="col-md-6 mb-3">
                                                                <label for="state" class="form-label">State</label>
                                                                <input type="text" class="form-control" name="state" value="{{ $applicant->state }}">
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label for="gender" class="form-label">Gender</label>
                                                                <select class="form-control" name="gender">
                                                                    <option value="male" {{ $applicant->gender == 'male' ? 'selected' : '' }}>Male</option>
                                                                    <option value="female" {{ $applicant->gender == 'female' ? 'selected' : '' }}>Female</option>
                                                                    <option value="other" {{ $applicant->gender == 'other' ? 'selected' : '' }}>Other</option>
                                                                </select>
                                                            </div>
                            
                                                            <div class="col-md-6 mb-3">
                                                                <label for="cv" class="form-label">CV (PDF)</label>
                                                                <input type="file" class="form-control" name="cv" accept=".pdf">
                                                                @if($applicant->cv)
                                                                    <small>Current: <a href="{{ asset($applicant->cv) }}" target="_blank">View CV</a></small>
                                                                @endif
                                                            </div>
                            
                                                            <div class="col-md-6 mb-3">
                                                                <label for="cover_letter" class="form-label">Cover Letter (PDF)</label>
                                                                <input type="file" class="form-control" name="cover_letter" accept=".pdf">
                                                                @if($applicant->cover_letter)
                                                                    <small>Current: <a href="{{ asset($applicant->cover_letter) }}" target="_blank">View Cover Letter</a></small>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                            
                                                <hr>
                                                <div class="text-end">
                                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            

                            <!-- Delete Modal -->
                            <div id="deleteApplicant{{$applicant->id}}" class="modal fade" tabindex="-1" aria-hidden="true" style="display: none;">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-body text-center p-5">
                                            <div class="text-end">
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="mt-2">
                                                <h4 class="mb-3 mt-4">Are you sure you want to delete this applicant: <br/> <br> {{ $applicant->title . ' ' . $applicant->last_name }}?</h4>
                                                <form action="{{ url('/admin/deleteApplicant') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="applicant_id" value="{{ $applicant->id }}">
                                                    <hr>
                                                    <button type="submit" class="btn btn-danger w-100">Yes, Delete</button>
                                                </form>
                                            </div>
                                        </div>
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

@endsection
