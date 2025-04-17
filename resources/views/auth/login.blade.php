@extends('layouts.app_simple')

@section('title', 'Login')

@section('content')

    <div class="d-flex align-items-center justify-content-center" style="min-height: 100vh;">
        <div class="row w-100">
            <div class="col-md-3 grid-margin stretch-card mx-auto">
                <div class="card">
                    <div class="card-body">
                        <h2 class="text-center mb-4">Login</h2>

                        <form class="forms-sample" method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label">Email address</label>
                                <input name="email" type="email" class="form-control" id="email" placeholder="Email" value="{{ old('email') }}">
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input name="password" type="password" class="form-control" id="password"
                                       autocomplete="off" placeholder="Password">
                            </div>
                            <div class="form-check mb-3">
                                <input name="remember" type="checkbox" class="form-check-input" id="remember">
                                <label class="form-check-label" for="remember">
                                    Remember me
                                </label>
                            </div>
                            <br>
                            <button type="submit" class="btn btn-primary w-100">Login</button>
                            <div class="text-center mt-3">
                                <a href="{{ route('register') }}" class="text-muted small">
                                    Don't have an account? Register here
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

