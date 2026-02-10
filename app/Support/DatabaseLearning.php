<?php

namespace App\Support;

class DatabaseLearning
{
    public function relationshipTopics(): array
    {
        return [
            [
                'title' => 'ARC (exclusive subtype) relationships',
                'summary' => 'ARC relationships model an entity that can relate to exactly one subtype at a time.',
                'atsExample' => 'Activity subjects point to either a position or an application, never both.',
                'implementation' => 'activities.subject_type + activities.subject_id with a morph map.',
            ],
            [
                'title' => 'Ternary relationships',
                'summary' => 'A single record captures a relationship between three entities.',
                'atsExample' => 'Applications connect candidates, positions, and the recruiter who submitted them.',
                'implementation' => 'applications.candidate_id + applications.position_id + applications.submitted_by.',
            ],
            [
                'title' => 'Polymorphic relationships',
                'summary' => 'Multiple models share the same relationship through type/id columns.',
                'atsExample' => 'Activity::subject() can reference applications or positions for unified audit history.',
                'implementation' => 'Laravel morphTo + morphMap in AppServiceProvider.',
            ],
            [
                'title' => 'Recursive & self-referencing trees',
                'summary' => 'A table references itself to build hierarchies like org charts or stage taxonomies.',
                'atsExample' => 'Add positions.parent_id to capture reporting lines or stage trees.',
                'implementation' => 'Self-referencing foreign key + recursive CTE for traversal.',
            ],
        ];
    }

    public function cteExamples(): array
    {
        return [
            [
                'title' => 'Pipeline funnel aggregation',
                'summary' => 'CTE used in the pipeline metrics endpoint to count applications per stage.',
                'sql' => <<<'SQL'
                    WITH stage_counts AS (
                        SELECT current_stage, COUNT(*) AS total
                        FROM applications
                        GROUP BY current_stage
                    )
                    SELECT current_stage, total
                    FROM stage_counts
                    ORDER BY total DESC, current_stage ASC;
                    SQL,
            ],
            [
                'title' => 'Stage velocity with window functions',
                'summary' => 'Analyze how long candidates stay in each stage.',
                'sql' => <<<'SQL'
                    WITH stage_events AS (
                        SELECT application_id,
                               to_stage,
                               changed_at,
                               LAG(changed_at) OVER (
                                   PARTITION BY application_id
                                   ORDER BY changed_at
                               ) AS previous_changed_at
                        FROM application_stages
                    ),
                    stage_durations AS (
                        SELECT application_id,
                               to_stage,
                               EXTRACT(EPOCH FROM (changed_at - previous_changed_at)) / 3600 AS hours_in_stage
                        FROM stage_events
                        WHERE previous_changed_at IS NOT NULL
                    )
                    SELECT to_stage, AVG(hours_in_stage) AS avg_hours_in_stage
                    FROM stage_durations
                    GROUP BY to_stage
                    ORDER BY avg_hours_in_stage DESC;
                    SQL,
            ],
            [
                'title' => 'Recursive CTE for hierarchy traversal',
                'summary' => 'Walk a self-referencing tree, such as positions that report to a parent position.',
                'sql' => <<<'SQL'
                    WITH RECURSIVE reporting_tree AS (
                        SELECT id, title, parent_id, 1 AS depth
                        FROM positions
                        WHERE parent_id IS NULL
                        UNION ALL
                        SELECT child.id, child.title, child.parent_id, parent.depth + 1
                        FROM positions AS child
                        JOIN reporting_tree AS parent ON parent.id = child.parent_id
                    )
                    SELECT *
                    FROM reporting_tree
                    ORDER BY depth, title;
                    SQL,
            ],
        ];
    }
}
