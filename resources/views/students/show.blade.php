@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header">{{ __('Task Details') }}</div>

                <div class="card-body">

                    <h3>Title: {{ $task->title }}</h3>
                    <p>Description: {{ $task->description }}</p>
                    <p>Due Date: {{ $task->due_date }}</p>
                    <p>Status: {{ $task->status }}</p>

                    <a href="{{ route('task.index') }}" class="btn btn-secondary mt-3">Back</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
