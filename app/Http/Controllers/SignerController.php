<?php

namespace App\Http\Controllers;

use App\Models\Signer;
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
            'role' => 'required|string|max:255',
        ]);

        Signer::create($validated);

        return redirect()->route('signer.index')->with('success', 'Signer created successfully.');
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
            'role' => 'required|string|max:255',
        ]);

        $signer->update($validated);

        return redirect()->route('signer.index')->with('success', 'Signer updated successfully.');
    }

    public function destroy(Signer $signer)
    {
        $this->authorize('delete', $signer);
        $signer->delete();
        return redirect()->route('signer.index')->with('success', 'Signer deleted successfully.');
    }
}
