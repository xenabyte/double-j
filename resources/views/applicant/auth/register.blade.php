@extends('applicant.layout.auth')

@section('content')

<div class="col-md-8 col-lg-6 col-xl-5">
    <div class="card overflow-hidden">
        <div class="bg-primary-subtle">
            <div class="row">
                <div class="col-7">
                    <div class="text-primary p-4">
                        <h5 class="text-primary">Free Register</h5>
                        <p>Get your free Skote account now.</p>
                    </div>
                </div>
                <div class="col-5 align-self-end">
                    <img src="{{ asset('assets/images/profile-img.png') }}" alt="" class="img-fluid">
                </div>
            </div>
        </div>
        <div class="card-body pt-0"> 
            <div>
                <a href="{{ url('/applicant/register') }}">
                    <div class="avatar-md profile-user-wid mb-4">
                        <span class="avatar-title rounded-circle bg-light">
                            <img src="{{ !empty($pageGlobalData->setting) ? asset($pageGlobalData->setting->favicon) : '' }}" alt="" class="rounded-circle" height="34">
                        </span>
                    </div>
                </a>
            </div>
            <div class="p-2">
                <form class="needs-validation" role="form" method="POST" action="{{ url('/applicant/register') }}">
                    @csrf

                    <div class="mb-3{{ $errors->has('email') ? ' has-error' : '' }}">
                        <label for="email" class="form-label">Email</label>
                        <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Enter email" required> 
                        @if ($errors->has('email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif 
                        <div class="invalid-feedback">
                            Please Enter Email
                        </div>      
                    </div>


                    <div class="mb-3{{ $errors->has('password') ? ' has-error' : '' }}">
                        <label for="password" class="form-label">Password</label>
                        <input id="password" type="password" name="password" class="form-control" placeholder="Enter password" required>
                        @if ($errors->has('password'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                        <div class="invalid-feedback">
                            Please Enter Password
                        </div>       
                    </div>

                    <div class="mb-3{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                        <label for="password_confirmation" class="form-label"> Confirm Password</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" class="form-control" placeholder="Please Confirm password" required>
                        @if ($errors->has('password_confirmation'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password_confirmation') }}</strong>
                            </span>
                        @endif
                        <div class="invalid-feedback">
                            Please Confirm Password
                        </div>       
                    </div>

                    <div class="mt-4 d-grid">
                        <button class="btn btn-primary waves-effect waves-light" type="submit">Register</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
    <div class="mt-5 text-center">
        
        <div>
            <p>Already have an account ? <a href="{{ url('/applicant/login') }}" class="fw-medium text-primary"> Login</a> </p>
        </div>
    </div>

</div>
@endsection


        


    
               