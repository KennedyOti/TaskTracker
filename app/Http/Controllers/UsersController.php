<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::withCount('assignedTasks')->get();

        return view('portal.users.index', compact('users'));
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->load(['assignedTasks' => function($query) {
            $query->orderBy('created_at', 'desc');
        }]);

        return view('portal.users.show', compact('user'));
    }
}
