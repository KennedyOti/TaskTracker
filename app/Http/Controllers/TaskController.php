<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Retrieve filter parameters from request
        $filters = [
            'name' => request('name'),
            'status' => request('status', 'all'),
            'due_date' => request('due_date'),
            'priority' => request('priority'),
        ];

        // Build query using Eloquent scopes for extensibility and readability
        $query = Task::with('assignedUser')
            ->filterByName($filters['name'])
            ->filterByStatus($filters['status'])
            ->filterByDueDate($filters['due_date'])
            ->filterByPriority($filters['priority']);

        // Order by created_at descending for better UX (newest first)
        $tasks = $query->orderBy('created_at', 'desc')->paginate(10);

        $users = User::all();

        return view('portal.tasks.index', compact('tasks', 'filters', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all();
        return view('portal.tasks.create', compact('users'));
    }

    /**
     * Store a newly created resource/task in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assigned_user_id' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date|after:now', // Due date must be in the future
            'priority' => 'required|in:low,medium,high',
        ]);

        Task::create($request->only(['title', 'description', 'assigned_user_id', 'due_date', 'priority']));

        return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        return view('portal.tasks.show', compact('task'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        $users = User::all();
        return view('portal.tasks.edit', compact('task', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assigned_user_id' => 'nullable|exists:users,id',
            'due_date' => 'nullable|date', // Allow past dates for updates
            'priority' => 'required|in:low,medium,high',
        ]);

        $task->update($request->only(['title', 'description', 'assigned_user_id', 'due_date', 'priority']));

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully.');
    }

    public function toggleStatus(Task $task)
    {
        $task->status = $task->status === 'pending' ? 'completed' : 'pending';
        $task->save();
        return redirect()->back()->with('success', 'Task status updated successfully.');
    }

    public function assign(Request $request, Task $task)
    {
        $request->validate([
            'assigned_user_id' => 'required|exists:users,id',
        ]);

        $task->update(['assigned_user_id' => $request->assigned_user_id]);

        return redirect()->back()->with('success', 'Task assigned successfully.');
    }
}
