<?php

namespace App\Http\Controllers;

use App\Models\Signer;
use App\Models\User;
use Illuminate\Http\Request;

class SignerController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Signer::class);
        $signers = Signer::latest()->paginate(10);
        return view('signer.index', compact('signers'));
    }

    public function create()
    {
        $this->authorize('create', Signer::class);
        return view('signer.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Signer::class);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'role' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Create user account for signer
        $user = User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'role' => 'signer',
            'password' => bcrypt($validated['password']),
        ]);

        // Create signer record linked to user
        Signer::create([
            'name' => $validated['name'],
            'role' => $validated['role'],
            'user_id' => $user->id,
        ]);

        return redirect()->route('signer.index')->with('success', 'Signer account created successfully.');
    }

    public function edit(Signer $signer)
    {
        $this->authorize('update', $signer);
        return view('signer.edit', compact('signer'));
    }

    public function update(Request $request, Signer $signer)
    {
        $this->authorize('update', $signer);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $signer->user_id,
            'role' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $signer->user_id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        // Update signer record
        $signer->update([
            'name' => $validated['name'],
            'role' => $validated['role'],
        ]);

        // Update user account
        $userData = [
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
        ];

        // Only update password if provided
        if (!empty($validated['password'])) {
            $userData['password'] = bcrypt($validated['password']);
        }

        $signer->user->update($userData);

        return redirect()->route('signer.index')->with('success', 'Signer account updated successfully.');
    }

    public function destroy(Signer $signer)
    {
        $this->authorize('delete', $signer);
        $signer->delete();
        return redirect()->route('signer.index')->with('success', 'Signer deleted successfully.');
    }
}
