<?php

namespace App\Traits;

use Illuminate\Pagination\LengthAwarePaginator;

trait Paginatable
{
    /**
     * Reusable pagination for consistency across controllers
     * 
     * @param \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Relations\Relation $query
     * @param int $perPage
     * @return \Illuminate\Http\JsonResponse
     */
    public function paginateResponse($query, $perPage = 15)
    {
        $paginated = $query->paginate($perPage);

        return response()->json([
            'data' => $paginated->items(),
            'meta' => [
                'current_page' => $paginated->currentPage(),
                'last_page' => $paginated->lastPage(),
                'per_page' => $paginated->perPage(),
                'total' => $paginated->total(),
                'has_more' => $paginated->hasMorePages(),
            ]
        ]);
    }
}
