<?php

/**
 * @see https://github.com/spatie/laravel-query-builder
 */

use App\Domain\Generic\Query\Enums\QueryKey;

return [

    /*
     * By default the package will use the `include`, `filter`, `sort`
     * and `fields` query parameters as described in the readme.
     *
     * You can customize these query string parameters here.
     */
    'parameters' => [
        'include' => QueryKey::INCLUDE->value,
        'filter' => QueryKey::FILTER->value,
        'sort' => QueryKey::SORT->value,
        'fields' => QueryKey::FIELDS->value,
        'append' => QueryKey::APPEND->value,
    ],

    /*
     * Related model counts are included using the relationship name suffixed with this string.
     * For example: GET /users?include=postsCount
     */
    'count_suffix' => 'Count',

    /*
     * By default the package will throw an `InvalidFilterQuery` exception when a filter in the
     * URL is not allowed in the `allowedFilters()` method.
     */
    'disable_invalid_filter_query_exception' => false,

    /*
     * By default the package inspects query string of request using $request->query().
     * You can change this behavior to inspect the request body using $request->input()
     * by setting this value to `body`.
     *
     * Possible values: `query_string`, `body`
     */
    'request_data_source' => 'query_string',
];
