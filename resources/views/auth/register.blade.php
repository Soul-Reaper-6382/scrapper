@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <h1 class="heading_h1_login">Create an Account!</h1>
        <div class="col-md-5">
            <div class="card">
                <div class="card-header">{{ __('Register') }}</div>

                <div class="card-body">
                    <form id="payment-form" method="POST" action="{{ url('registerstore') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="name" class="col-md-12 col-form-label">Name</label>

                            <div class="col-md-12">
                                 <input name="name" type="text" required class="form-control @error('name') is-invalid @enderror" id="name" placeholder="Last Name">
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="email" class="col-md-12 col-form-label">Email</label>

                            <div class="col-md-12">
                                <input name="email" type="email" required class="form-control @error('email') is-invalid @enderror" id="email" placeholder="Email">
                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        

                        <div class="row mb-3">
                            <label for="password" class="col-md-12 col-form-label">{{ __('Password') }}</label>

                            <div class="col-md-12" style="position:relative;">
                                <p style="position: absolute;
    right: 20px;
    font-size: 12px;
    color: #cc9249;
    font-weight: bold;bottom: -15px;">The password must be atleast 8 characters.</p>
                                <input id="password" type="password" class="input form-control @error('password') is-invalid @enderror" name="password" minlength="8" required autocomplete="new-password" placeholder="Password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        

                        <div class="row">
                            <div class="col-md-10">
                                <input id="roles" type="hidden" class="form-control" name="roles" value="2">
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-12">
                                <button type="submit" class="btn loginbtn">
                                    {{ __('Register') }}
                                </button>
                                <p class="text-end mt-2">Already have an account?<a href="{{ route('login') }}">{{ __('Login Now') }}</a></p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
