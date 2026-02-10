<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Candidate;
use App\Models\Position;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ApplicationController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', Application::class);

        $applications = Application::query()
            ->with([
                'candidate:id,full_name,email',
                'position:id,title',
                'submitter:id,name',
            ])
            ->latest()
            ->get();

        $positions = Position::query()->orderBy('title')->get(['id', 'title']);
        $candidates = Candidate::query()->orderBy('full_name')->get(['id', 'full_name']);

        return view('applications.index', [
            'applications' => $applications,
            'positions' => $positions,
            'candidates' => $candidates,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Application::class);

        $validated = $request->validate([
            'position_id' => ['required', 'integer', 'exists:positions,id'],
            'candidate_id' => [
                'required',
                'integer',
                'exists:candidates,id',
                Rule::unique('applications')->where(fn ($query) => $query->where('position_id', $request->integer('position_id'))),
            ],
            'current_stage' => ['required', 'string', 'in:applied,screening,interview,offer,hired,rejected'],
            'status' => ['required', 'string', 'in:active,withdrawn,hired,rejected'],
            'source' => ['nullable', 'string', 'max:255'],
            'cover_letter' => ['nullable', 'string'],
            'applied_at' => ['nullable', 'date'],
        ]);

        $validated['submitted_by'] = $request->user()->id;

        Application::query()->create($validated);

        return redirect()
            ->route('applications.index')
            ->with('status', 'Application created.');
    }
}
