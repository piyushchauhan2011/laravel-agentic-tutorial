<?php

namespace App\Support;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class DepartmentHierarchyService
{
    /**
     * Get the full department tree using a recursive CTE.
     */
    public function getFullTree(): Collection
    {
        $query = "
            WITH RECURSIVE dept_tree AS (
                SELECT id, name, parent_id, level, description, 
                       CAST(name AS TEXT) as path
                FROM departments
                WHERE parent_id IS NULL
                UNION ALL
                SELECT d.id, d.name, d.parent_id, d.level, d.description,
                       dt.path || ' > ' || d.name
                FROM departments d
                INNER JOIN dept_tree dt ON d.parent_id = dt.id
            )
            SELECT * FROM dept_tree
            ORDER BY path
        ";

        return collect(DB::select($query));
    }

    /**
     * Get descendants of a specific department using a recursive CTE.
     */
    public function getDescendants(int $departmentId): Collection
    {
        $query = "
            WITH RECURSIVE dept_descendants AS (
                SELECT id, name, parent_id, level, description, 0 as depth
                FROM departments
                WHERE id = ?
                UNION ALL
                SELECT d.id, d.name, d.parent_id, d.level, d.description, dd.depth + 1
                FROM departments d
                INNER JOIN dept_descendants dd ON d.parent_id = dd.id
            )
            SELECT * FROM dept_descendants
            WHERE id != ?
            ORDER BY depth, name
        ";

        // First binding: CTE anchor (WHERE id = ?), second: exclude root from results (WHERE id != ?)
        return collect(DB::select($query, [$departmentId, $departmentId]));
    }

    /**
     * Get ancestors (path to root) using a recursive CTE.
     */
    public function getAncestors(int $departmentId): Collection
    {
        $query = "
            WITH RECURSIVE dept_ancestors AS (
                SELECT id, name, parent_id, level, description, 0 as distance
                FROM departments
                WHERE id = ?
                UNION ALL
                SELECT d.id, d.name, d.parent_id, d.level, d.description, da.distance + 1
                FROM departments d
                INNER JOIN dept_ancestors da ON d.id = da.parent_id
            )
            SELECT * FROM dept_ancestors
            WHERE id != ?
            ORDER BY distance DESC
        ";

        // First binding: CTE anchor (WHERE id = ?), second: exclude self from results (WHERE id != ?)
        return collect(DB::select($query, [$departmentId, $departmentId]));
    }

    /**
     * Get depth statistics using a CTE.
     */
    public function getDepthStats(): Collection
    {
        $query = "
            WITH RECURSIVE dept_depth AS (
                SELECT id, name, parent_id, 0 as depth
                FROM departments
                WHERE parent_id IS NULL
                UNION ALL
                SELECT d.id, d.name, d.parent_id, dd.depth + 1
                FROM departments d
                INNER JOIN dept_depth dd ON d.parent_id = dd.id
            )
            SELECT depth as level, COUNT(*) as department_count
            FROM dept_depth
            GROUP BY depth
            ORDER BY depth
        ";

        return collect(DB::select($query));
    }

    /**
     * Pipeline funnel using CTE to aggregate application stages.
     */
    public function getPipelineFunnel(): Collection
    {
        $query = "
            WITH stage_counts AS (
                SELECT current_stage as stage, COUNT(*) as total
                FROM applications
                GROUP BY current_stage
            )
            SELECT stage, total
            FROM stage_counts
            ORDER BY total DESC
        ";

        return collect(DB::select($query));
    }
}
