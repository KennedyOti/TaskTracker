@extends('layouts.portal')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Dashboard</h1>

    <div class="row">
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">Total Tasks</h5>
                    <h2>{{ \App\Models\Task::count() }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-warning mb-3">
                <div class="card-body">
                    <h5 class="card-title">Pending Tasks</h5>
                    <h2>{{ \App\Models\Task::where('status', 'pending')->count() }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title">Completed Tasks</h5>
                    <h2>{{ \App\Models\Task::where('status', 'completed')->count() }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5>Recent Tasks</h5>
                </div>
                <div class="card-body">
                    @if(\App\Models\Task::count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Status</th>
                                        <th>Assigned To</th>
                                        <th>Created</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach(\App\Models\Task::with('assignedUser')->latest()->take(5)->get() as $task)
                                        <tr>
                                            <td>{{ $task->title }}</td>
                                            <td>
                                                <span class="badge {{ $task->status === 'completed' ? 'bg-success' : 'bg-warning' }}">
                                                    {{ ucfirst($task->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $task->assignedUser ? $task->assignedUser->name : 'Unassigned' }}</td>
                                            <td>{{ $task->created_at->format('M d, Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">No tasks yet. <a href="{{ route('tasks.create') }}">Create your first task</a>.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection