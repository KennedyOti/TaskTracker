@extends('layouts.portal')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>{{ $task->title }}</h4>
                    <div>
                        <a href="{{ route('tasks.edit', $task) }}" class="btn btn-outline-secondary btn-sm">Edit</a>
                        <a href="{{ route('tasks.index') }}" class="btn btn-outline-primary btn-sm">Back to List</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Status</h6>
                            <span class="badge {{ $task->status === 'completed' ? 'bg-success' : 'bg-warning' }} fs-6">
                                {{ ucfirst($task->status) }}
                            </span>
                        </div>
                        <div class="col-md-6">
                            <h6>Priority</h6>
                            <span class="badge {{ $task->priority === 'high' ? 'bg-danger' : ($task->priority === 'medium' ? 'bg-warning' : 'bg-info') }} fs-6">
                                {{ ucfirst($task->priority) }}
                            </span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <h6>Assigned To</h6>
                            <p>{{ $task->assignedUser ? $task->assignedUser->name : 'Unassigned' }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6>Due Date</h6>
                            <p>{{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('M d, Y H:i') : 'No due date' }}</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <h6>Created</h6>
                            <p>{{ $task->created_at->format('M d, Y H:i') }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6>Last Updated</h6>
                            <p>{{ $task->updated_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>

                    <h6>Description</h6>
                    <p>{{ $task->description ?: 'No description provided.' }}</p>

                    <div class="mt-3">
                        <form action="{{ route('tasks.toggle-status', $task) }}" method="POST" class="d-inline">
                            @csrf
                            @method('POST')
                            <button type="submit" class="btn {{ $task->status === 'completed' ? 'btn-warning' : 'btn-success' }}">
                                {{ $task->status === 'completed' ? 'Mark as Pending' : 'Mark as Complete' }}
                            </button>
                        </form>
                        <button type="button" class="btn btn-danger ms-2" onclick="confirmDelete()">Delete Task</button>
                    </div>

                    <form id="delete-form" action="{{ route('tasks.destroy', $task) }}" method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function confirmDelete() {
    Swal.fire({
        title: 'Are you sure?',
        text: 'This action cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form').submit();
        }
    });
}
</script>
@endpush
@endsection