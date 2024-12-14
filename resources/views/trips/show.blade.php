@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header">{{ __('Trip Details') }}</div>

                <div class="card-body">

                    <h3>name: {{ $trip->name }}</h3>
                    <p>type: {{ $trip->type }}</p>
                    <p>path: {{ $trip->path->name }}</p>
                    <p>bus: {{ $trip->bus->name }}</p>
                    <p>status: {{ $trip->status == 0 ? 'منتهية' : 'جارية'}}</p>

                    @foreach($trip->drivers as $driver)
                        <p>drivers: {{ $driver->name}}</p>
                    @endforeach

                    @foreach($trip->supervisors as $supervisor)
                        <p>supervisor: {{ $supervisor->name}}</p>
                    @endforeach

                    @foreach($trip->students as $student)
                        <p>students: {{ $student->name}}</p>
                    @endforeach

                    <a href="{{ route('trip.index') }}" class="btn btn-secondary mt-3">Back</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
