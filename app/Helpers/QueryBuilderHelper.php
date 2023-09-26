<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Builder;

class QueryBuilderHelper
{
    /**
     * @return Builder
     */
    public static function sortingQuery(Builder $builder): mixed
    {
        $requestQuery = app('request')->query();

        $order = isset($requestQuery['order']) ? $requestQuery['order'] : 'id';
        $sort = isset($requestQuery['sort']) ? $requestQuery['sort'] : 'DESC';

        return $builder->orderBy($order, $sort);
    }

    /**
     * id,name,email
     */
    public static function searchQuery(Builder $builder): mixed
    {
        $requestQuery = app('request')->query();
        $search = isset($requestQuery['search']) ? $requestQuery['search'] : null;
        $columns = isset($requestQuery['columns']) ? $requestQuery['columns'] : null;

        if ($search && $columns) {
            $columns = explode(',', $columns);
            $searchableFields = collect($columns);

            return $builder->where(function (Builder $builder) use ($search, $searchableFields) {
                return $searchableFields->map(function ($field) use ($search, $builder, $searchableFields) {
                    $method = $searchableFields->first() === $field ? 'where' : 'orWhere';

                    return $builder->{$method}($field, 'LIKE', "%$search%");
                });
            });
        }

        return $builder;
    }

    /**
     * @return Builder
     */
    public static function paginationQuery(Builder $builder): mixed
    {
        $requestQuery = app('request')->query();
        $page = isset($requestQuery['page']) ? $requestQuery['page'] : null;
        $perPage = isset($requestQuery['per_page']) ? $requestQuery['per_page'] : null;

        if ($page && $perPage) {
            return $builder->paginate(perPage: $perPage, page: $page)->appends($requestQuery);
        }

        return $builder->get();
    }

    /**
     * @return Builder
     */
    public static function filterQuery(Builder $builder): mixed
    {
        $requestQuery = app('request')->query();
        $type = isset($requestQuery['type']) ? $requestQuery['type'] : null;
        $value = isset($requestQuery['value']) ? $requestQuery['value'] : null;

        if ($type) {
            return $builder->whereHas($type);
        }

        return $builder;
    }
}
