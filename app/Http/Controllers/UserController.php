<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    //
    public function index(Request $request)
    {
        //get all users with pagination
        $users = DB::table('users')
        ->when($request->input('name'), function($query, $name) {
            $query->where('name', 'like', '%'. $name . '%')->orWhere('email', 'like', '%' . $name . '%');
        })
        ->paginate(10);
        return view('pages.users.index', compact('users'));
    }

    public function create() {
        return view('pages.users.create');
    }

    public function store(Request $request) {

        $request->validate([
            'name'=> 'required',
            'email'=> 'required|email|unique:users',
            'password'=> 'required|min:8',
            'role'=> 'required|in:admin,staff,user',
        ]);

        // create user
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->role = $request->role;
        $user->save();

        return redirect()->route('users.index')->with('success', 'User created successfully');
    }

    public function show($id) {
        return view('pages.users.show');
    }

    public function edit($id) {
        $user = User::findOrfail($id);
        return view('pages.users.edit', compact('user'));
    }

    public function update(Request $request, $id) {
        $request->validate([
            'name'=> 'required',
            'email'=> 'required',
            'role'=> 'required|in:admin,staff,user',
        ]);
        // update user
        $user = User::find($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        $user->save();

        //if password is not empty
        if($request->password) {
            $user->password = Hash::make($request->password);
            $user->save();
        }

        return redirect()->route('users.index')->with('success', 'User updated successfully');
    }

    public function destroy($id) {
        $user = User::find($id);
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully');
    }
}
