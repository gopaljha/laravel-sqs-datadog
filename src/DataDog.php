<?php

namespace GopalJha\LaravelSQSDataDog;

use GopalJha\LaravelSQSDataDog\DataDogCurl;
use GopalJha\LaravelSQSDataDog\DataDogClient;
use GopalJha\LaravelSQSDataDog\Jobs\DataDogIncrement;

class DataDog
{
    public static function increment($metric, array $tags = [], $host = null)
    {
        $driver = config('datadog.driver');
        if($driver == "guzzle") {
            $datadogclient = new DataDogClient();
            $datadogclient->increment($metric, $tags, $host);
        } else if($driver == "sqs" && (!empty(config('datadog.SQS_URL')) && !is_null(config('datadog.SQS_URL')))) {
            $sqs = config('datadog.SQS_URL');
            $job = (new DataDogIncrement($metric, $tags, $host))->onQueue($sqs);
            dispatch($job);
        } else if($driver == "job") {
            dispatch(new DataDogIncrement($metric, $tags, $host));
        } else {
            $datadogclient = new DataDogCurl();
            $datadogclient->increment($metric, $tags, $host);
        }
    }
}
