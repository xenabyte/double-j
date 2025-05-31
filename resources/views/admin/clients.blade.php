@extends('admin.layout.dashboard')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Clients</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item active">Clients</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Clients</h4>
                <div class="flex-shrink-0">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addClient">Add Client</button>
                </div>
            </div>

            <div class="card-body">
                <table id="datatable" class="table table-bordered dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>Company Name</th>
                            <th>Company Email</th>
                            <th>Client Name</th>
                            <th>Contact Phone</th>
                            <th>Industry</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($clients as $client)
                            <tr>
                                <td>{{ $client->company_name }}</td>
                                <td>{{ $client->email }}</td>
                                <td>{{ $client->name }}</td>
                                <td>{{ $client->phone }}</td>
                                <td>{{ $client->industry }}</td>
                                <td>
                                    <a href="{{ url('admin/viewClient/'.$client->slug) }}" class="btn btn-primary m-1">
                                        <i class="mdi mdi-eye"></i>
                                    </a>
                                    <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#editClient{{$client->id}}" class="btn btn-info">
                                        <i class="mdi mdi-pencil"></i>
                                    </a>
                                    <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#deleteClient{{$client->id}}" class="btn btn-danger">
                                        <i class="mdi mdi-delete"></i>
                                    </a>
                                </td>
                            </tr>

                            <!-- Edit Client Modal -->
                            <div class="modal fade" id="editClient{{$client->id}}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-xl">
                                    <form action="{{ url('/admin/updateClient') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="client_id" value="{{ $client->id }}">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Client</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body row g-3">
                                                <div class="col-md-6">
                                                    <label>Client Name</label>
                                                    <input type="text" name="name" class="form-control" value="{{ $client->name }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Email</label>
                                                    <input type="email" name="email" class="form-control" value="{{ $client->email }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Phone</label>
                                                    <input type="text" name="phone" class="form-control" value="{{ $client->phone }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Company Name</label>
                                                    <input type="text" name="company_name" class="form-control" value="{{ $client->company_name }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Company Email</label>
                                                    <input type="email" name="company_email" class="form-control" value="{{ $client->company_email }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Company Phone</label>
                                                    <input type="text" name="company_phone" class="form-control" value="{{ $client->company_phone }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Company Address</label>
                                                    <input type="text" name="company_address" class="form-control" value="{{ $client->company_address }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Industry</label>
                                                    <input type="text" name="industry" class="form-control" value="{{ $client->industry }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label>Change Logo (Optional)</label>
                                                    <input type="file" name="logo" class="form-control" accept="image/*">
                                                    @if($client->logo)
                                                        <small>Current: <a href="{{ asset($client->logo) }}" target="_blank">View Logo</a></small>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary">Update Client</button>
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- Delete Client Modal -->
                            <div class="modal fade" id="deleteClient{{$client->id}}" tabindex="-1" aria-hidden="true" style="display: none;">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-body text-center p-5">
                                            <div class="text-end">
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <h4 class="mb-3 mt-4">Are you sure you want to delete <br> <br> {{ $client->company_name }}?</h4>
                                            {{-- <p>Are you sure you want to delete <strong>{{ $client->company_name }}</strong>?</p> --}}
                                            <form action="{{ url('/admin/deleteClient') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="client_id" value="{{ $client->id }}">
                                                <hr>
                                                <div class="d-grid">
                                                    <button type="submit" class="btn btn-danger">Yes, Delete</button>
                                                </div>
                                            </form>
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

<!-- Add Client Modal -->
<div class="modal fade" id="addClient" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <form action="{{ url('/admin/newClient') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Client</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body row g-3">
                    <div class="col-md-6">
                        <label>Client Name *</label>
                        <input type="text" name="name" class="form-control" required>
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
                        <label>Company Name *</label>
                        <input type="text" name="company_name" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label>Company Email</label>
                        <input type="email" name="company_email" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label>Company Phone</label>
                        <input type="text" name="company_phone" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label>Company Address</label>
                        <input type="text" name="company_address" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label>Industry</label>
                        <input type="text" name="industry" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label>Logo (JPG/PNG)</label>
                        <input type="file" name="logo" class="form-control" accept="image/*">
                    </div>
                    <div class="col-md-6">
                        <label>Password *</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label>Confirm Password *</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Save Client</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>


@endsection