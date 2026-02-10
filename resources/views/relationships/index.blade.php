<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Database Relationships Explorer') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-8">

            {{-- Intro --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-2">Advanced Database Relationship Patterns</h3>
                <p class="text-gray-600">This page demonstrates advanced SQL and Eloquent relationship patterns used in this Applicant Tracking System, including recursive/tree structures, polymorphic relations, ternary pivots, ARC (exclusive arc) relationships, and CTE (Common Table Expression) queries.</p>
            </div>

            {{-- 1. Self-referencing / Recursive / Tree: Departments --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-4">🌳 Self-Referencing / Recursive / Tree: Departments</h3>
                <p class="text-sm text-gray-500 mb-4">Each department can have a <code>parent_id</code> pointing to another department, forming a tree. Uses <code>belongsTo('parent')</code> and <code>hasMany('children')</code> on the same model.</p>

                @if($departments->isEmpty())
                    <p class="text-gray-400 italic">No departments yet. Create them in the <a href="/admin" class="text-blue-600 underline">Admin Panel</a>.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm border">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left">Name</th>
                                    <th class="px-4 py-2 text-left">Parent</th>
                                    <th class="px-4 py-2 text-left">Level</th>
                                    <th class="px-4 py-2 text-left">Children</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($departments as $dept)
                                    <tr class="border-t">
                                        <td class="px-4 py-2 font-medium">{{ str_repeat('— ', $dept->level) }}{{ $dept->name }}</td>
                                        <td class="px-4 py-2">{{ $dept->parent?->name ?? '(root)' }}</td>
                                        <td class="px-4 py-2">{{ $dept->level }}</td>
                                        <td class="px-4 py-2">{{ $dept->children->count() }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            {{-- 2. CTE: Department Hierarchy Tree --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-4">📊 CTE: Recursive Department Hierarchy</h3>
                <p class="text-sm text-gray-500 mb-4">Uses a <code>WITH RECURSIVE</code> Common Table Expression to traverse the entire department tree and build full paths.</p>

                @if($departmentTree->isEmpty())
                    <p class="text-gray-400 italic">No department hierarchy data yet.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm border">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left">Department</th>
                                    <th class="px-4 py-2 text-left">Full Path (via CTE)</th>
                                    <th class="px-4 py-2 text-left">Level</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($departmentTree as $node)
                                    <tr class="border-t">
                                        <td class="px-4 py-2 font-medium">{{ $node->name }}</td>
                                        <td class="px-4 py-2 text-gray-600">{{ $node->path }}</td>
                                        <td class="px-4 py-2">{{ $node->level }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                {{-- Depth Stats --}}
                @if($depthStats->isNotEmpty())
                    <h4 class="font-semibold mt-6 mb-2">Depth Statistics (CTE)</h4>
                    <div class="flex gap-4">
                        @foreach($depthStats as $stat)
                            <div class="bg-blue-50 rounded px-4 py-2 text-center">
                                <div class="text-2xl font-bold text-blue-700">{{ $stat->department_count }}</div>
                                <div class="text-xs text-gray-500">Level {{ $stat->level }}</div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- 3. Polymorphic: Tags --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-4">🏷️ Polymorphic Many-to-Many: Tags</h3>
                <p class="text-sm text-gray-500 mb-4">Tags use <code>morphToMany</code> / <code>morphedByMany</code> to attach to Candidates, Positions, and Companies via a single <code>taggables</code> pivot table.</p>

                @if($tags->isEmpty())
                    <p class="text-gray-400 italic">No tags yet. Create them in the <a href="/admin" class="text-blue-600 underline">Admin Panel</a>.</p>
                @else
                    <div class="flex flex-wrap gap-3">
                        @foreach($tags as $tag)
                            <div class="bg-amber-50 border border-amber-200 rounded-lg px-4 py-2">
                                <span class="font-medium">{{ $tag->name }}</span>
                                <span class="text-xs text-gray-500 ml-1">({{ $tag->type }})</span>
                                <div class="text-xs text-gray-400 mt-1">
                                    {{ $tag->candidates_count }} candidates ·
                                    {{ $tag->positions_count }} positions ·
                                    {{ $tag->companies_count }} companies
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- 4. Ternary: Skill Assessments --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-4">🔺 Ternary Relationship: Skill Assessments</h3>
                <p class="text-sm text-gray-500 mb-4">A <code>skill_assessments</code> table connects three entities: <strong>Candidate</strong>, <strong>Position</strong>, and <strong>Skill</strong> with a rating. This is a ternary (three-way) relationship.</p>

                @if($skills->isEmpty() && $assessments->isEmpty())
                    <p class="text-gray-400 italic">No skills or assessments yet. Create them in the <a href="/admin" class="text-blue-600 underline">Admin Panel</a>.</p>
                @else
                    @if($skills->isNotEmpty())
                        <h4 class="font-semibold mb-2">Skills</h4>
                        <div class="flex flex-wrap gap-2 mb-4">
                            @foreach($skills as $skill)
                                <span class="bg-green-50 border border-green-200 rounded px-3 py-1 text-sm">
                                    {{ $skill->name }} <span class="text-gray-400">({{ $skill->category }}, {{ $skill->assessments_count }} assessments)</span>
                                </span>
                            @endforeach
                        </div>
                    @endif

                    @if($assessments->isNotEmpty())
                        <h4 class="font-semibold mb-2">Recent Assessments</h4>
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm border">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-2 text-left">Candidate</th>
                                        <th class="px-4 py-2 text-left">Position</th>
                                        <th class="px-4 py-2 text-left">Skill</th>
                                        <th class="px-4 py-2 text-left">Rating</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($assessments as $assessment)
                                        <tr class="border-t">
                                            <td class="px-4 py-2">{{ $assessment->candidate->full_name }}</td>
                                            <td class="px-4 py-2">{{ $assessment->position->title }}</td>
                                            <td class="px-4 py-2">{{ $assessment->skill->name }}</td>
                                            <td class="px-4 py-2">
                                                <span class="inline-block bg-blue-100 text-blue-800 rounded px-2 py-0.5 text-xs font-bold">{{ $assessment->rating }}/10</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                @endif
            </div>

            {{-- 5. ARC: Feedback --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-4">⚡ ARC (Exclusive Arc): Feedback</h3>
                <p class="text-sm text-gray-500 mb-4">Feedback belongs to <strong>either</strong> an Interview <strong>or</strong> an Offer, but not both. This is an Exclusive Arc pattern using nullable foreign keys with a constraint that exactly one must be set.</p>

                @if($feedbacks->isEmpty())
                    <p class="text-gray-400 italic">No feedback yet. Create them in the <a href="/admin" class="text-blue-600 underline">Admin Panel</a>.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm border">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left">Subject Type</th>
                                    <th class="px-4 py-2 text-left">Subject ID</th>
                                    <th class="px-4 py-2 text-left">Rating</th>
                                    <th class="px-4 py-2 text-left">Author</th>
                                    <th class="px-4 py-2 text-left">Comments</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($feedbacks as $fb)
                                    <tr class="border-t">
                                        <td class="px-4 py-2">
                                            <span class="inline-block rounded px-2 py-0.5 text-xs font-bold {{ $fb->subject_type === 'interview' ? 'bg-purple-100 text-purple-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                {{ ucfirst($fb->subject_type ?? 'N/A') }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-2">#{{ $fb->interview_id ?? $fb->offer_id ?? '-' }}</td>
                                        <td class="px-4 py-2">{{ $fb->rating }}/10</td>
                                        <td class="px-4 py-2">{{ $fb->author?->name ?? '-' }}</td>
                                        <td class="px-4 py-2 max-w-xs truncate">{{ $fb->comments ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            {{-- 6. Self-referencing through Candidates: Referrals --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-4">🔄 Self-Referencing: Candidate Referrals</h3>
                <p class="text-sm text-gray-500 mb-4">A referral connects two candidates (<code>candidate_id</code> and <code>referred_by_candidate_id</code>) with a position. This demonstrates self-referencing through the candidates table.</p>

                @if($referrals->isEmpty())
                    <p class="text-gray-400 italic">No referrals yet.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm border">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left">Candidate</th>
                                    <th class="px-4 py-2 text-left">Referred By</th>
                                    <th class="px-4 py-2 text-left">Position</th>
                                    <th class="px-4 py-2 text-left">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($referrals as $ref)
                                    <tr class="border-t">
                                        <td class="px-4 py-2">{{ $ref->candidate->full_name }}</td>
                                        <td class="px-4 py-2">{{ $ref->referrer->full_name }}</td>
                                        <td class="px-4 py-2">{{ $ref->position->title }}</td>
                                        <td class="px-4 py-2">
                                            <span class="inline-block rounded px-2 py-0.5 text-xs font-bold bg-gray-100 text-gray-700">{{ ucfirst($ref->status) }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            {{-- 7. Pipeline Funnel CTE --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-4">📈 CTE: Pipeline Funnel</h3>
                <p class="text-sm text-gray-500 mb-4">Uses a CTE to aggregate application counts by stage.</p>

                @if($pipelineFunnel->isEmpty())
                    <p class="text-gray-400 italic">No application data yet.</p>
                @else
                    <div class="flex flex-wrap gap-4">
                        @foreach($pipelineFunnel as $stage)
                            <div class="bg-indigo-50 rounded-lg px-6 py-3 text-center">
                                <div class="text-2xl font-bold text-indigo-700">{{ $stage->total }}</div>
                                <div class="text-xs text-gray-500 capitalize">{{ str_replace('_', ' ', $stage->stage) }}</div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Summary --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-2">📚 Relationship Pattern Summary</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div class="border rounded p-3">
                        <strong>🌳 Self-Referencing / Tree</strong>
                        <p class="text-gray-600">Department → parent_id → Department</p>
                    </div>
                    <div class="border rounded p-3">
                        <strong>🏷️ Polymorphic M2M</strong>
                        <p class="text-gray-600">Tag → taggables → Candidate|Position|Company</p>
                    </div>
                    <div class="border rounded p-3">
                        <strong>🔺 Ternary</strong>
                        <p class="text-gray-600">SkillAssessment → Candidate + Position + Skill</p>
                    </div>
                    <div class="border rounded p-3">
                        <strong>⚡ ARC (Exclusive Arc)</strong>
                        <p class="text-gray-600">Feedback → Interview XOR Offer</p>
                    </div>
                    <div class="border rounded p-3">
                        <strong>🔄 Self-Referencing (Candidates)</strong>
                        <p class="text-gray-600">Referral → candidate + referred_by_candidate</p>
                    </div>
                    <div class="border rounded p-3">
                        <strong>📊 CTE (Common Table Expressions)</strong>
                        <p class="text-gray-600">Recursive hierarchy, depth stats, pipeline funnel</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
