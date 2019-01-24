<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function filter(Request $request, Builder &$query)
    {
        if (isset($request->sort_by) && $request->sort_by) {
            $query->orderBy($request->sort_by, $request->sort_type);
        }

        foreach($this->filters as $type => $fields) {
            foreach($fields as $key_field => $field) {
                $f = is_array($field) ? $key_field : $field;
                if (isset($request->{$f}) && $request->{$f}) {
                    $this->{'filter' . ucfirst($type)}($field, $request->{$f}, $query);
                }
            }
        }
    }

    protected function showBy(Request $request, Builder $query)
    {
        return $query->paginate($request->show_by ?: 9999);
    }

    protected function showAll(Builder $query)
    {
        return $query->paginate(9999);
    }

    /**
     * FILTERS TYPES
     */

    private function filterMultiple(string $field, $value, Builder &$query)
    {
        $query->whereIn($field, explode(',', $value));
    }

    private function filterEquals(string $field, $value, Builder &$query)
    {
        $query->where($field, $value);
    }

    private function filterExclude(string $field, $value, Builder &$query)
    {
        $query->where($field, '<>', $value);
    }

    private function filterLike(string $field, $value, Builder &$query)
    {
        $query->where($field, 'like', '%' . $value . '%');
    }

    private function filterLikeMultiple(array $fields, $value, Builder &$query)
    {
        $query->where(function ($query) use ($fields, $value) {
            foreach($fields as $field) {
                $query->orWhere($field, 'like', '%' . $value . '%');
            }
        });
    }
}
