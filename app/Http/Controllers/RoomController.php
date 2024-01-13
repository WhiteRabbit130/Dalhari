<?php
namespace App\Http\Controllers;

use App\Models\User;

class RoomController extends Controller
{
    public function index()
    {
        $user = User::with('address', 'bio')->where('id', auth()->id())->get()->first();

        return view('room.index', compact('user'));
    }
}

