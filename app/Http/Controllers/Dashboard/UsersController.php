<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UsersController extends Controller
{

    public function index()
    {
        $users = User::select()->orderBy('id','DESC') -> paginate(PAGINATION_COUNT);
        return view('dashboard.users.index', compact('users'));
    }

    public function create()
    {
        return view('dashboard.users.create');
    }

    public function store(UserRequest $request)
    {
        try {
            $filePath = "";
            if ($request->has('photo')) {
                $filePath = uploadImage('users', $request->photo);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
                'photo' => $filePath,
            ]);

            return redirect()->route('admin.users')->with(['success' => 'User was added successfully.']);

        } catch (\Exception $ex) {
            return redirect()->route('admin.users')->with(['error' => 'Something went wrong.']);

        }

    }


    public function edit($id)
    {
        $user = User::find($id);
        if (!$user)
            return redirect()->route('admin.users')->with(['error' => 'This user was not found. ']);
        return view('dashboard.users.edit', compact('user'));
    }


    public function update($id, UserRequest $request)
    {
        try {
            $user = User::find($id);
            if (!$user)
                return redirect()->route('admin.users')->with(['error' => 'This user was not found.']);
            if ($request->has('photo')) {
                $filePath = uploadImage('users', $request->photo);
                deleteImage($user->photo);
                $user->update([
                    'photo' => $filePath,
                ]);
            }

            $data = $request->except('photo', '_token', 'password', 'id');
            if ($request->has('password') && !is_null($request-> password)) {
                $data['password'] = $request->password;
            }

            User::where('id', $id)
                ->update(
                    $data
                );
            return redirect()->route('admin.users')->with(['success' => 'User was updated successfully.']);
        } catch (\Exception $ex) {
            return redirect()->route('admin.users')->with(['error' => 'Something went wrong.']);
        }

    }

    public function destroy($id)
    {

        try {
            $user = User::find($id);
            if (!$user)
                return redirect()->route('admin.users')->with(['error' => 'This user was not found.']);

            deleteImage($user->photo);
            $user->delete();
            return redirect()->route('admin.users')->with(['success' => 'User was deleted successfully.']);
        } catch (\Exception $ex) {
            return redirect()->route('admin.users')->with(['error' => 'Something went wrong.']);
        }
    }

}
