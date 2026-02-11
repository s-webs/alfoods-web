<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCounterpartyRequest;
use App\Http\Requests\UpdateCounterpartyRequest;
use App\Models\Counterparty;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CounterpartyController extends Controller
{
    public function index(): View
    {
        $counterparties = Counterparty::orderBy('name')->get();

        return view('admin.counterparties.index', compact('counterparties'));
    }

    public function create(): View
    {
        return view('admin.counterparties.create');
    }

    public function store(StoreCounterpartyRequest $request): RedirectResponse
    {
        Counterparty::create($request->validated());

        return redirect()->route('admin.counterparties.index')->with('success', 'Counterparty created.');
    }

    public function show(Counterparty $counterparty): View
    {
        return view('admin.counterparties.show', compact('counterparty'));
    }

    public function edit(Counterparty $counterparty): View
    {
        return view('admin.counterparties.edit', compact('counterparty'));
    }

    public function update(UpdateCounterpartyRequest $request, Counterparty $counterparty): RedirectResponse
    {
        $counterparty->update($request->validated());

        return redirect()->route('admin.counterparties.index')->with('success', 'Counterparty updated.');
    }

    public function destroy(Counterparty $counterparty): RedirectResponse
    {
        $counterparty->delete();

        return redirect()->route('admin.counterparties.index')->with('success', 'Counterparty deleted.');
    }
}
