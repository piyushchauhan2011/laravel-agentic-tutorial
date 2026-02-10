<?php

namespace App\Filament\Pages;

use App\Support\DatabaseLearning as DatabaseLearningContent;
use Filament\Pages\Page;

class DatabaseLearning extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationGroup = 'Learning';

    protected static ?string $navigationLabel = 'Database Learning';

    protected static ?string $title = 'Database Relationships & CTEs';

    protected static ?string $slug = 'learning/database';

    protected static string $view = 'filament.pages.database-learning';

    public function getViewData(): array
    {
        $learning = app(DatabaseLearningContent::class);

        return [
            'relationshipTopics' => $learning->relationshipTopics(),
            'cteExamples' => $learning->cteExamples(),
        ];
    }
}
