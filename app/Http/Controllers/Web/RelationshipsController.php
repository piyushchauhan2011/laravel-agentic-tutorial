<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Feedback;
use App\Models\Referral;
use App\Models\Skill;
use App\Models\SkillAssessment;
use App\Models\Tag;
use App\Support\DepartmentHierarchyService;
use Illuminate\View\View;

class RelationshipsController extends Controller
{
    public function index(DepartmentHierarchyService $hierarchyService): View
    {
        return view('relationships.index', [
            'departments' => Department::with(['parent', 'children'])->orderBy('level')->orderBy('name')->get(),
            'departmentTree' => $hierarchyService->getFullTree(),
            'depthStats' => $hierarchyService->getDepthStats(),
            'pipelineFunnel' => $hierarchyService->getPipelineFunnel(),
            'tags' => Tag::withCount(['candidates', 'positions', 'companies'])->get(),
            'skills' => Skill::withCount('assessments')->get(),
            'feedbacks' => Feedback::with(['interview', 'offer', 'author'])->latest()->limit(10)->get(),
            'referrals' => Referral::with(['candidate', 'referrer', 'position'])->latest()->limit(10)->get(),
            'assessments' => SkillAssessment::with(['candidate', 'position', 'skill'])->latest()->limit(10)->get(),
        ]);
    }
}
