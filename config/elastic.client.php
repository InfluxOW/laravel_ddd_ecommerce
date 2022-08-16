<?php

$elasticUrl = env('ELASTICSEARCH_URL') ?? 'localhost:9200';

$config = [
    'default' => env('ELASTIC_CONNECTION', 'default'),
    'connections' => [
        'default' => [
            'hosts' => [
                $elasticUrl,
            ],
        ],
    ],
];

$parsedElasticUrl = parse_url($elasticUrl);
$elasticUsername = $parsedElasticUrl['user'] ?? null;
$elasticPassword = $parsedElasticUrl['pass'] ?? null;

if (isset($elasticUsername, $elasticPassword)) {
    $config['connections']['default']['basicAuthentication'] = [$elasticUsername, $elasticPassword];
}

return $config;
