@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header">{{ __('Add New Trip') }}</div>

                <div class="card-body">

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('trip.store') }}" method="POST">
                        @csrf

                        <!-- Name Field -->
                        <div class="mb-3">
                            <label for="name">Name</label>
                            <select id="name" name="name" class="form-control" required>
                                <option value="" disabled selected>Select name</option>
                                <option value="delivery">توصيل</option>
                                <option value="school">مدرسية</option>
                            </select>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Type Field -->
                        <div class="mb-3">
                            <label for="type">Type</label>
                            <select id="type" name="type" class="form-control" required>
                                <option value="" disabled selected>Select type</option>
                                <option value="go">ذهاب</option>
                                <option value="back">إياب</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Path Field -->
                        <div class="mb-3">
                            <label for="path_id">Path</label>
                            <select id="path_id" name="path_id" class="form-control" required>
                                <option value="" disabled selected>Select Path</option>
                                @foreach($paths as $path)
                                    <option value="{{ $path->id }}">{{ $path->name }}</option>
                                @endforeach
                            </select>
                            @error('path_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Bus Field -->
                        <div class="mb-3">
                            <label for="bus_id">Bus</label>
                            <select id="bus_id" name="bus_id" class="form-control" required>
                                <option value="" disabled selected>Select Bus</option>
                                @foreach($buses as $bus)
                                    <option value="{{ $bus->id }}">{{ $bus->name }}</option>
                                @endforeach
                            </select>
                            @error('bus_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-primary">Add Trip</button>
                            <a href="{{ route('trip.index') }}" class="btn btn-secondary ms-2">Back</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
