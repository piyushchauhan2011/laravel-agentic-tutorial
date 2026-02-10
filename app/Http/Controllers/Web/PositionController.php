<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Position;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PositionController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Position::class);

        $positions = Position::query()
            ->with('company:id,name')
            ->latest()
            ->get();

        $companies = Company::query()
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('positions.index', [
            'positions' => $positions,
            'companies' => $companies,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Position::class);

        $validated = $request->validate([
            'company_id' => ['required', 'integer', 'exists:companies,id'],
            'title' => ['required', 'string', 'max:255'],
            'department' => ['nullable', 'string', 'max:255'],
            'employment_type' => ['required', 'string', 'in:full_time,part_time,contract,internship'],
            'location' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'string', 'in:draft,published,closed'],
            'description' => ['nullable', 'string'],
        ]);

        Position::query()->create($validated);

        return redirect()
            ->route('positions.index')
            ->with('status', 'Position created.');
    }
}
