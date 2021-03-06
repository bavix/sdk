<?php

include_once dirname(__DIR__) . '/vendor/autoload.php';

$corundum = new \Bavix\SDK\Corundum(new Bavix\Slice\Slice([

    'app' => [

        // application settings
        'client_id'     => '3',
        'client_secret' => 'S3O39tPlD3IWSDd8AcwWj1gzZ6lKzR1QLnJvwcgF',

        'url' => [
            'token'  => 'http://corundum.local/oauth/token',
            'verify' => 'http://corundum.local/api/verify',
            'upload' => 'http://corundum.local/api/image'
        ],

    ],

    'users' => [
        'test' => [
            'username' => 'test@corundum.local',
            'password' => 'test@corundum.local',
        ],
    ]

]));

// upload
try
{
    $slice = $corundum->upload('test', dirname(__DIR__) . '/web/index.png');
}
catch (\Throwable $throwable)
{
    if ($throwable instanceof \Bavix\Exceptions\NotFound\Path)
    {
        throw $throwable;
    }

    $slice = $corundum->getResults();
}

echo \Bavix\Helpers\JSON::encode([
    'code' => $corundum->getCode(),
    'data' => $slice
]);

// regenerate
//try
//{
//    $slice = $corundum->update('test', 'MdaS9e.jpeg', new \Bavix\Slice\Slice([
////        'params' => [
////            'checkExists' => true
////        ]
//    ]));
//}
//catch (\Throwable $throwable)
//{
//    if ($throwable instanceof \Bavix\Exceptions\NotFound\Path)
//    {
//        throw $throwable;
//    }
//
//    $slice = $corundum->getResults();
//}
//
//echo \Bavix\Helpers\JSON::encode([
//    'code' => $corundum->getCode(),
//    'data' => $slice
//]);


// delete
//try
//{
//    $slice = $corundum->delete('test', 'mgRZIh.jpeg');
//}
//catch (\Throwable $throwable)
//{
//    if ($throwable instanceof \Bavix\Exceptions\NotFound\Path)
//    {
//        throw $throwable;
//    }
//
//    $slice = $corundum->getResults();
//}
//
//echo \Bavix\Helpers\JSON::encode([
//    'code' => $corundum->getCode(),
//    'data' => $slice
//]);
