<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Position;
use App\Support\DatabaseLearning;
use Illuminate\View\View;

class DatabaseLearningController extends Controller
{
    public function __invoke(DatabaseLearning $learning): View
    {
        $this->authorize('viewAny', Position::class);

        return view('learn.database', [
            'relationshipTopics' => $learning->relationshipTopics(),
            'cteExamples' => $learning->cteExamples(),
        ]);
    }
}
