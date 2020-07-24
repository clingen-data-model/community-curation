<?php

namespace App\Services\Search;

use App\Note;
use App\User;
use App\CurationGroup;
use Illuminate\Support\Collection;
use App\Contracts\ModelSearchService;
use Illuminate\Database\Eloquent\Builder;

class NotesSearchService implements ModelSearchService
{
    protected $validFilters = [
        'notable_type',
        'notable_id',
        'content',
        'created_by_id'
    ];

    public function search($params):Collection
    {
        return $this->buildQuery($params)
                    ->get();
    }
    
    public function buildQuery($params):Builder
    {
        $query = Note::query()
                    ->select(['notes.*']);

        foreach ($params as $key => $value) {
            if ($key == 'select') {
                $query->select($value);
            }

            if ($key == 'with') {
                $query->with($value);
            }

            if (in_array($key, $this->validFilters)) {
                if ($key == 'content') {
                    $query->where(function ($q) use ($value) {
                        $q->where('content', 'LIKE', '%'.$value.'%');
                    });
                    continue;
                }
                $query->where($key, $value);
            }
        }

        $this->setOrder($params, $query);
 
        return $query;
    }

    private function setOrder($params, $query)
    {
        $sortField = (isset($params['sortBy'])) ? $params['sortBy'] : 'notes.id';
        $sortDir = (isset($params['sortDesc']) && $params['sortDesc'] === 'true') ? 'desc' : 'asc';
        $query->orderBy($sortField, $sortDir);
    }
}
