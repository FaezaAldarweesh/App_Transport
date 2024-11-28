@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>{{ __('Dashboard') }}</h5>

                    <a href="{{ route('home') }}" class="btn btn-link">
                        <i class="bi bi-house-door">Home</i>
                    </a>
                </div>

                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('student.index') }}" class="btn btn-primary me-2">
                            <i class="bi bi-list-student me-1"></i> student
                        </a>

                        <a href="{{ route('supervisor.index') }}" class="btn btn-primary me-2">
                            <i class="bi bi-list-student me-1"></i> supervisor
                        </a>

                        <a href="{{ route('driver.index') }}" class="btn btn-primary me-2">
                            <i class="bi bi-list-student me-1"></i> driver
                        </a>

                        <a href="{{ route('bus.index') }}" class="btn btn-primary me-2">
                            <i class="bi bi-list-student me-1"></i> Bus
                        </a>

                        <a href="{{ route('path.index') }}" class="btn btn-primary me-2">
                            <i class="bi bi-list-student me-1"></i> Path
                        </a>

                        <a href="{{ route('station.index') }}" class="btn btn-primary me-2">
                            <i class="bi bi-list-student me-1"></i> station
                        </a>

                        <a href="{{ route('trip.index') }}" class="btn btn-primary me-2">
                            <i class="bi bi-list-student me-1"></i> trip
                        </a>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
