<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'parent_id',
        'level',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Department::class, 'parent_id');
    }

    public function positions(): HasMany
    {
        return $this->hasMany(Position::class);
    }

    public function recursiveChildren(): HasMany
    {
        return $this->children()->with('recursiveChildren');
    }

    public function ancestors(): BelongsTo
    {
        return $this->parent()->with('ancestors');
    }

    /**
     * Get the full department hierarchy using a recursive CTE.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function getHierarchyTree()
    {
        return DB::table(DB::raw('departments'))
            ->select('departments.*')
            ->orderBy('level')
            ->orderBy('name')
            ->get();
    }

    /**
     * Get all descendants of this department using a recursive CTE.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getDescendants()
    {
        $query = '
            WITH RECURSIVE dept_tree AS (
                SELECT id, name, parent_id, level, description
                FROM departments
                WHERE id = ?
                UNION ALL
                SELECT d.id, d.name, d.parent_id, d.level, d.description
                FROM departments d
                INNER JOIN dept_tree dt ON d.parent_id = dt.id
            )
            SELECT * FROM dept_tree WHERE id != ?
            ORDER BY level, name
        ';

        return DB::select($query, [$this->id, $this->id]);
    }

    /**
     * Get all ancestors of this department using a recursive CTE.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getAncestors()
    {
        $query = '
            WITH RECURSIVE dept_ancestors AS (
                SELECT id, name, parent_id, level, description
                FROM departments
                WHERE id = ?
                UNION ALL
                SELECT d.id, d.name, d.parent_id, d.level, d.description
                FROM departments d
                INNER JOIN dept_ancestors da ON d.id = da.parent_id
            )
            SELECT * FROM dept_ancestors WHERE id != ?
            ORDER BY level, name
        ';

        return DB::select($query, [$this->id, $this->id]);
    }

    /**
     * Get department depth statistics using a CTE.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function getDepthStats()
    {
        $query = '
            WITH RECURSIVE dept_depth AS (
                SELECT id, name, parent_id, 0 as depth
                FROM departments
                WHERE parent_id IS NULL
                UNION ALL
                SELECT d.id, d.name, d.parent_id, dd.depth + 1
                FROM departments d
                INNER JOIN dept_depth dd ON d.parent_id = dd.id
            )
            SELECT depth, COUNT(*) as department_count
            FROM dept_depth
            GROUP BY depth
            ORDER BY depth
        ';

        return collect(DB::select($query));
    }
}
