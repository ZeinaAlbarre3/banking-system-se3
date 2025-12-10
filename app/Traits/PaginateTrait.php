<?php

namespace App\Traits;

trait PaginateTrait
{
    public function paginateResponse($collection, $resource): array
    {
        return [
            'items' => $collection,

            'meta' => [
                'path'       => $resource->path(),
                'current'    => $resource->currentPage(),
                'per_page'   => $resource->perPage(),
                'total'      => $resource->total(),
                'last_page'  => $resource->lastPage(),
                'from'       => $resource->firstItem(),
                'to'         => $resource->lastItem(),
            ],

            'links' => [
                'first' => $resource->url(1),
                'last'  => $resource->url($resource->lastPage()),
                'next'  => $resource->nextPageUrl(),
                'prev'  => $resource->previousPageUrl(),
            ],

            'applied_filters' => request()->query(),
        ];
    }
}
