<?php

namespace App\Http\Controllers;

use App\Models\Signer;
use Illuminate\Http\Request;

class SignerController extends Controller
{
    public function index()
    {
        $signers = Signer::latest()->paginate(10);
        return view('signer.index', compact('signers'));
    }

    public function create()
    {
        return view('signer.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
        ]);

        Signer::create($validated);

        return redirect()->route('signer.index')->with('success', 'Signer created successfully.');
    }

    public function edit(Signer $signer)
    {
        return view('signer.edit', compact('signer'));
    }

    public function update(Request $request, Signer $signer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
        ]);

        $signer->update($validated);

        return redirect()->route('signer.index')->with('success', 'Signer updated successfully.');
    }

    public function destroy(Signer $signer)
    {
        $signer->delete();
        return redirect()->route('signer.index')->with('success', 'Signer deleted successfully.');
    }
}
