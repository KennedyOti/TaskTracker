@extends('layouts.portal')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>{{ $user->name }}</h4>
                    <a href="{{ route('users.index') }}" class="btn btn-outline-primary btn-sm">Back to Users</a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Name</h6>
                            <p>{{ $user->name }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6>Email</h6>
                            <p>{{ $user->email }}</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <h6>Joined</h6>
                            <p>{{ $user->created_at->format('M d, Y H:i') }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6>Last Updated</h6>
                            <p>{{ $user->updated_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>

                    <h6>Assigned Tasks ({{ $user->assignedTasks->count() }})</h6>
                    @if($user->assignedTasks->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Status</th>
                                        <th>Priority</th>
                                        <th>Due Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($user->assignedTasks as $task)
                                        <tr>
                                            <td><a href="{{ route('tasks.show', $task) }}">{{ $task->title }}</a></td>
                                            <td>
                                                <span class="badge {{ $task->status === 'completed' ? 'bg-success' : 'bg-warning' }}">
                                                    {{ ucfirst($task->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge {{ $task->priority === 'high' ? 'bg-danger' : ($task->priority === 'medium' ? 'bg-warning' : 'bg-info') }}">
                                                    {{ ucfirst($task->priority) }}
                                                </span>
                                            </td>
                                            <td>{{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('M d, Y') : 'No due date' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">No tasks assigned.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection