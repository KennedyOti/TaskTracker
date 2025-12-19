@extends('layouts.portal')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 style="font-size: 1rem; font-weight: bold;">Tasks Management</h1>
        <a href="{{ route('tasks.create') }}" class="btn btn-primary">Add New Task</a>
    </div>

    <!-- Filter Form -->
    <div class="mb-3">
        <form method="GET" action="{{ route('tasks.index') }}" class="row g-3">
            <div class="col-md-3">
                <input type="text" class="form-control" name="name" placeholder="Search by name" value="{{ $filters['name'] ?? '' }}">
            </div>
            <div class="col-md-2">
                <select class="form-select" name="status">
                    <option value="all" {{ ($filters['status'] ?? 'all') === 'all' ? 'selected' : '' }}>All Status</option>
                    <option value="pending" {{ ($filters['status'] ?? 'all') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="completed" {{ ($filters['status'] ?? 'all') === 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>
            <div class="col-md-3">
                <input type="date" class="form-control" name="due_date" value="{{ $filters['due_date'] ?? '' }}">
            </div>
            <div class="col-md-2">
                <select class="form-select" name="priority">
                    <option value="">All Priorities</option>
                    <option value="low" {{ ($filters['priority'] ?? '') === 'low' ? 'selected' : '' }}>Low</option>
                    <option value="medium" {{ ($filters['priority'] ?? '') === 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="high" {{ ($filters['priority'] ?? '') === 'high' ? 'selected' : '' }}>High</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('tasks.index') }}" class="btn btn-secondary">Clear</a>
            </div>
        </form>
    </div>

    @if($tasks->count() > 0)
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Due Date</th>
                        <th>Priority</th>
                        <th>Assigned To</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tasks as $task)
                        <tr>
                            <td><a href="{{ route('tasks.show', $task) }}">{{ $task->title }}</a></td>
                            <td>{{ Str::limit($task->description, 50) }}</td>
                            <td>
                                <span class="badge {{ $task->status === 'completed' ? 'bg-success' : 'bg-warning' }} status-badge">
                                    {{ ucfirst($task->status) }}
                                </span>
                            </td>
                            <td>{{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('M d, Y H:i') : 'No due date' }}</td>
                            <td>
                                <span class="badge {{ $task->priority === 'high' ? 'bg-danger' : ($task->priority === 'medium' ? 'bg-warning' : 'bg-info') }}">
                                    {{ ucfirst($task->priority) }}
                                </span>
                            </td>
                            <td>{{ $task->assignedUser ? $task->assignedUser->name : 'Unassigned' }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('tasks.show', $task) }}" class="btn btn-sm btn-outline-info">View</a>
                                    <a href="{{ route('tasks.edit', $task) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                                    @if($task->assignedUser && $task->status !== 'completed')
                                        <form action="{{ route('tasks.toggle-status', $task) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('POST')
                                            <button type="submit" class="btn btn-sm btn-outline-success">
                                                Mark Complete
                                            </button>
                                        </form>
                                    @else
                                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="openAssignModal({{ $task->id }}, '{{ $task->title }}')">Assign</button>
                                    @endif
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="confirmDelete({{ $task->id }}, '{{ $task->title }}')">Delete</button>
                                </div>
                                <form id="delete-form-{{ $task->id }}" action="{{ route('tasks.destroy', $task) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="text-center">
            <p class="text-muted">No tasks found. <a href="{{ route('tasks.create') }}">Create your first task</a>.</p>
        </div>
    @endif
</div>

@push('scripts')
<script>
function confirmDelete(taskId, taskTitle) {
    Swal.fire({
        title: 'Are you sure?',
        text: `Do you want to delete "${taskTitle}"?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form-' + taskId).submit();
        }
    });
}
</script>
@endpush

<!-- Assign Task Modal -->
<div class="modal fade" id="assignModal" tabindex="-1" aria-labelledby="assignModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="assignModalLabel">Assign Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="assignForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <p id="assignTaskTitle"></p>
                    <div class="mb-3">
                        <label for="assigned_user_id" class="form-label">Select User</label>
                        <select class="form-select" id="assigned_user_id" name="assigned_user_id" required>
                            <option value="">Choose a user...</option>
                            @foreach($users ?? [] as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Assign</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function confirmDelete(taskId, taskTitle) {
    Swal.fire({
        title: 'Are you sure?',
        text: `Do you want to delete "${taskTitle}"?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form-' + taskId).submit();
        }
    });
}

function openAssignModal(taskId, taskTitle) {
    document.getElementById('assignTaskTitle').textContent = `Assign "${taskTitle}" to a team member:`;
    document.getElementById('assignForm').action = `/tasks/${taskId}/assign`;
    document.getElementById('assigned_user_id').value = '';
    var assignModal = new bootstrap.Modal(document.getElementById('assignModal'));
    assignModal.show();
}
</script>
@endsection