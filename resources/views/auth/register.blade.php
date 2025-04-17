@extends('layouts.app_simple')

@section('title', 'Register')

@section('content')

    <div class="d-flex align-items-center justify-content-center" style="min-height: 100vh;">
        <div class="row w-100">
            <div class="col-md-3 grid-margin stretch-card mx-auto">
                <div class="card">
                    <div class="card-body">
                        <h2 class="text-center mb-4">Register</h2>

                        <form class="forms-sample" method="POST" action="{{ route('register') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="name" class="form-label">User Name</label>
                                <input name="name" type="text" class="form-control @error('name') is-invalid @enderror" id="name" placeholder="User Name" value="{{ old('name') }}">
                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email address</label>
                                <input name="email" type="email" class="form-control @error('email') is-invalid @enderror" id="email" placeholder="Email" value="{{ old('email') }}">
                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input name="password" type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                                       autocomplete="off" placeholder="Password">
                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Confirm Password</label>
                                <input name="password_confirmation" type="password" class="form-control" id="password_confirmation"
                                       autocomplete="off" placeholder="Confirm Password">
                            </div>

                            <br>

                            <button type="submit" class="btn btn-primary w-100">Register</button>

                            <div class="text-center mt-3">
                                <a href="{{ route('login') }}" class="text-muted small">
                                    Already have an account? Login here
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

