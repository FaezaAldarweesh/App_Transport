@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header">{{ __('Edit Trip') }}</div>

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

                    <form action="{{ route('trip.update', $trip->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $trip->name) }}">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="father_phone" class="form-label">father's phone</label>
                            <input type="text" class="form-control @error('father_phone') is-invalid @enderror" id="father_phone" name="father_phone" value="{{ old('father_phone', $trip->father_phone) }}">
                            @error('father_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="mather_phone" class="form-label">mather's phone</label>
                            <input type="text" class="form-control @error('mather_phone') is-invalid @enderror" id="mather_phone" name="mather_phone" value="{{ old('mather_phone', $trip->mather_phone) }}">
                            @error('mather_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="longitude" class="form-label">longitude</label>
                            <input type="text" class="form-control @error('longitude') is-invalid @enderror" id="longitude" name="longitude" value="{{ old('longitude', $trip->longitude) }}">
                            @error('longitude')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="latitude" class="form-label">latitude</label>
                            <input type="text" class="form-control @error('latitude') is-invalid @enderror" id="latitude" name="latitude" value="{{ old('latitude', $student->latitude) }}">
                            @error('latitude')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="exampleInputEmail1">Parent</label>
                            <select id="user_id" name="user_id" class="form-control" required>
                                <option value="" disabled {{ old('user_id', $student->user_id) ? '' : 'selected' }}>Select Parent</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" 
                                        {{ old('user_id', $student->user_id) == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                                <option value="">Select Status</option>
                                <option value="attendee" {{ old('status', $student->status) == 'attendee' ? 'selected' : '' }}>موجود</option>
                                <option value="absent_all" {{ old('status', $student->status) == 'absent_all' ? 'selected' : '' }}>غيائب يوم كامل</option>
                                <option value="absent_go" {{ old('status', $student->status) == 'absent_go' ? 'selected' : '' }}>غياب رحلة ذهاب</option>
                                <option value="absent_back" {{ old('status', $student->status) == 'absent_back' ? 'selected' : '' }}>غياب رحلة إياب</option>
                                <option value="transported" {{ old('status', $student->status) == 'transported' ? 'selected' : '' }}>تم نقله إلى باص أخر</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-primary">Update Student</button>
                            <a href="{{ route('student.index') }}" class="btn btn-secondary ms-2">Back</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
