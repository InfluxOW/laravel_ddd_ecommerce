<?php

return [
    'default' => env('ELASTIC_CONNECTION', 'default'),
    'connections' => [
        'default' => [
            'hosts' => [
                env('ELASTICSEARCH_URL', 'localhost:9200'),
            ],
        ],
    ],
];
