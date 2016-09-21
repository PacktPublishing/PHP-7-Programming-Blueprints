$es_query {
   
};
title
tags
content


$params = [
    'index' => 'posts',
    'type' => 'my_posts',
    'id' => '',
    'body' => [
        'script' => 'unter += count',
        'params' => [
            'count' => 4,
            '' => ,
        ],
        'upsert' => [
            'counter' => 1
        ]
    ]
];

$response = $client->update($params);

