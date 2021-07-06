@extends('layouts.blank')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Registered</div>

                <div class="card-body">
                    <p>Your account has been registered.</p>
                    <a class="btn btn-primary" href="{{ route('login') }}">
                        {{ __('Login') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
