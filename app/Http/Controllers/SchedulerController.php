<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
class SchedulerController extends Controller
{
    public function index()
    {
        // Eager load address and bio relationships
        $users = User::with('address', 'bio')->get();

//        foreach ($users as $user) {
//            // Ensure 'address' is a loaded relation and 'address' field exists in the address model
//            echo $user->address->address ?? 'No address';
//            echo $user->bio->short_bio ?? 'No bio';
//        }

//        dd($users);
        return view('scheduler.index', compact('users'));
    }
}
