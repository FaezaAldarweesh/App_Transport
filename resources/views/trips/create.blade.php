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

                        <!-- Students Field -->
                        <div class="mb-3">
                            <label for="students">Students</label>
                            <select id="students" name="students[]" class="form-control select2" multiple="multiple" required>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}">{{ $student->name }}</option>
                                @endforeach
                            </select>
                            @error('students')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                         <!-- Supervisors Field -->
                         <div class="mb-3">
                            <label for="supervisors">Supervisors</label>
                            <select id="supervisors" name="supervisors[]" class="form-control select2" multiple="multiple" required>
                                @foreach($supervisors as $supervisor)
                                    <option value="{{ $supervisor->id }}">{{ $supervisor->name }}</option>
                                @endforeach
                            </select>
                            @error('supervisors')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Drivers Field -->
                        <div class="mb-3">
                            <label for="drivers">Drivers</label>
                            <select id="drivers" name="drivers[]" class="form-control select2" multiple="multiple" required>
                                @foreach($drivers as $driver)
                                    <option value="{{ $driver->id }}">{{ $driver->name }}</option>
                                @endforeach
                            </select>
                            @error('drivers')
                                <div class="text-danger">{{ $message }}</div>
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

@section('scripts')
<!-- Add Select2 CSS and JS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/css/select2.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "Select Students",
            allowClear: true
        });
    });
</script>
@endsection
