@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white">{{ __('Dashboard') }}</div>

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>{{ session('success') }}</strong> <!-- يمكنك إضافة رمز لزيادة الجاذبية -->
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <div class="card-body">
                    <h2 class="mb-4 text-center text-secondary">Trashed Task List</h2>

                    <div class="d-flex justify-content-end mb-3">
                         <a href="{{ route('home') }}" class="btn btn-secondary ms-2">Back</a>
                    </div>

                    <table class="table table-hover table-bordered">
                        <thead class="table-primary text-center">
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Due Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tasks as $task)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ $task->title }}</td>
                                    <td>{{ Str::limit($task->description, 50) }}</td>
                                    <td>{{ \Carbon\Carbon::parse($task->due_date)->format('d-m-Y') }}</td>
                                    <td>
                                        <span class="badge 
                                            {{ $task->status == 'Completed' ? 'bg-success' : 'bg-warning text-dark' }}">
                                            {{ ucfirst($task->status) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <form action="{{ route('task.restore', $task->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Are you sure you want to restore this task?');">
                                            @csrf
                                            <button type="submit" class="btn btn-warning btn-sm text-white">
                                                <i class="bi bi-arrow-clockwise"></i> Restore
                                            </button>
                                        </form>
                                        <form action="{{ route('task.forceDelete', $task->id) }}" method="POST" class="d-inline-block ms-2" onsubmit="return confirm('Are you sure you want to permanently delete this task?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="bi bi-trash"></i> Force Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>  
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">No tasks available.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
