<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CandidateController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', Candidate::class);

        $candidates = Candidate::query()
            ->latest()
            ->get();

        return view('candidates.index', [
            'candidates' => $candidates,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Candidate::class);

        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:candidates,email'],
            'phone' => ['nullable', 'string', 'max:32'],
            'current_company' => ['nullable', 'string', 'max:255'],
            'resume_url' => ['nullable', 'url', 'max:2048'],
            'score' => ['nullable', 'integer', 'between:0,100'],
        ]);

        Candidate::query()->create($validated);

        return redirect()
            ->route('candidates.index')
            ->with('status', 'Candidate created.');
    }
}
