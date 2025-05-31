@extends('admin.layout.dashboard')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Clients</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ url('#') }}">{{ $client->company_name }}</a></li>
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
                        <img src="{{ $client->logo ? asset($client->logo) : asset('assets/images/users/avatar-1.jpg') }}" alt="Company Logo" class="img-thumbnail rounded" style="width: 100px;">
                    </div>
                    <div class="flex-grow-1">
                        <h5 class="font-size-15 text-truncate mb-1">
                            {{ $client->company_name }}
                        </h5>
                        <p class="text-muted mb-0 text-truncate">Client Company</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Info Card -->
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Contact Information</h4>

                @if($client)
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <tbody>
                                <tr>
                                    <th>Contact Person</th>
                                    <td>{{ $client->name }}</td>
                                </tr>
                                <tr>
                                    <th>Phone</th>
                                    <td>{{ $client->phone }}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>{{ $client->email }}</td>
                                </tr>
                                <tr>
                                    <th>Industry</th>
                                    <td>{{ $client->industry ?? 'Not specified' }}</td>
                                </tr>
                                <tr>
                                    <th>Company Email</th>
                                    <td>{{ $client->company_email ?? 'Not provided' }}</td>
                                </tr>
                                <tr>
                                    <th>Company Phone</th>
                                    <td>{{ $client->company_phone ?? 'Not provided' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted">No client data found.</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Company Address -->
    <div class="col-xl-8">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Company Information</h4>
                <table class="table table-sm table-bordered">
                    <tbody>
                        <tr>
                            <th>Company Name</th>
                            <td>{{ $client->company_name }}</td>
                        </tr>
                        <tr>
                            <th>Company Address</th>
                            <td>{{ $client->company_address ?? 'Not specified' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
