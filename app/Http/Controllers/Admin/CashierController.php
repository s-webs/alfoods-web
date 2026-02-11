<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cashier;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CashierController extends Controller
{
    public function index(): View
    {
        $cashiers = Cashier::with('user')->get();

        return view('admin.cashiers.index', compact('cashiers'));
    }

    public function create(): View
    {
        $users = User::orderBy('name')->get();

        return view('admin.cashiers.create', compact('users'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'name' => ['required', 'string', 'max:255'],
            'enabled' => ['nullable', 'boolean'],
        ]);

        $validated['enabled'] = $request->boolean('enabled');

        Cashier::create($validated);

        return redirect()->route('admin.cashiers.index')->with('success', 'Cashier created.');
    }

    public function edit(Cashier $cashier): View
    {
        $users = User::orderBy('name')->get();

        return view('admin.cashiers.edit', compact('cashier', 'users'));
    }

    public function update(Request $request, Cashier $cashier): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'name' => ['required', 'string', 'max:255'],
            'enabled' => ['nullable', 'boolean'],
        ]);

        $validated['enabled'] = $request->boolean('enabled');

        $cashier->update($validated);

        return redirect()->route('admin.cashiers.index')->with('success', 'Cashier updated.');
    }

    public function destroy(Cashier $cashier): RedirectResponse
    {
        $cashier->delete();

        return redirect()->route('admin.cashiers.index')->with('success', 'Cashier deleted.');
    }
}
