<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class UserManagementController extends Controller
{
    // List all users
    public function index()
    {
        // Typically you'd restrict this to admins via middleware, 
        // but for now we just return all users.
        $users = User::all();
        return response()->json($users);
    }

    // Update user (e.g., change role)
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'username' => 'sometimes|string|max:255|unique:users,username,'.$id,
            'phone' => ['sometimes', 'string', 'unique:users,phone,'.$id, 'regex:/^(08|031)[0-9]{6,11}$/'],
            'role' => 'sometimes|in:admin,user',
        ]);

        $user->update($request->all());

        return response()->json([
            'message' => 'User updated successfully',
            'user' => $user
        ]);
    }

    // Delete a user
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }
}
