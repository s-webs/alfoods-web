<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shift;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShiftController extends Controller
{
    public function index(): View
    {
        $shifts = Shift::orderByDesc('opened_at')->get();

        return view('admin.shifts.index', compact('shifts'));
    }

    public function create(): View
    {
        return view('admin.shifts.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'opened_at' => ['required', 'date'],
            'closed_at' => ['nullable', 'date', 'after:opened_at'],
        ]);

        Shift::create($validated);

        return redirect()->route('admin.shifts.index')->with('success', 'Shift created.');
    }

    public function edit(Shift $shift): View
    {
        return view('admin.shifts.edit', compact('shift'));
    }

    public function update(Request $request, Shift $shift): RedirectResponse
    {
        $validated = $request->validate([
            'opened_at' => ['required', 'date'],
            'closed_at' => ['nullable', 'date'],
        ]);

        $shift->update($validated);

        return redirect()->route('admin.shifts.index')->with('success', 'Shift updated.');
    }

    public function destroy(Shift $shift): RedirectResponse
    {
        $shift->delete();

        return redirect()->route('admin.shifts.index')->with('success', 'Shift deleted.');
    }
}
