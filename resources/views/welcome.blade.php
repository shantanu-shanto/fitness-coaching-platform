@extends('layouts.app')

@section('title', 'Welcome - Fitness Coaching Platform')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <h1 class="display-4 mb-4">
                <i class="bi bi-heart-pulse"></i> Fitness Coaching Platform
            </h1>
            <p class="lead mb-4">
                Connect with professional fitness coaches and achieve your fitness goals
            </p>

            @auth
                <div class="alert alert-info">
                    You are logged in as <strong>{{ auth()->user()->name }}</strong>
                </div>
                @if (auth()->user()->isCoach())
                    <a href="{{ route('coach.dashboard') }}" class="btn btn-primary btn-lg">
                        <i class="bi bi-speedometer2"></i> Go to Coach Dashboard
                    </a>
                @else
                    <a href="{{ route('client.dashboard') }}" class="btn btn-primary btn-lg">
                        <i class="bi bi-speedometer2"></i> Go to Client Dashboard
                    </a>
                @endif
            @else
                <div class="row">
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="bi bi-person-badge"></i> For Coaches
                                </h5>
                                <p class="card-text">Create and manage meal plans and workout routines for your clients</p>
                                <a href="{{ route('register') }}" class="btn btn-primary">Register as Coach</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="bi bi-person"></i> For Clients
                                </h5>
                                <p class="card-text">Track your fitness journey and follow personalized plans</p>
                                <a href="{{ route('register') }}" class="btn btn-primary">Register as Client</a>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <p class="mb-3">Already have an account?</p>
                <a href="{{ route('login') }}" class="btn btn-outline-primary btn-lg">
                    <i class="bi bi-box-arrow-in-right"></i> Login
                </a>
            @endauth
        </div>
    </div>
</div>
@endsection
