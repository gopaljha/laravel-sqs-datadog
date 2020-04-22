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
        if($driver == "curl") {
            $datadogclient = new DataDogCurl();
            $datadogclient->increment($metric, $tags, $host);
        } else if($driver == "sqs") {
            $sqs = (!empty(config('datadog.SQS_URL')) && !is_null(config('datadog.SQS_URL'))) ? config('datadog.SQS_URL') : null;
            if(!empty($sqs) && !is_null($sqs)) {
                $job = (new DataDogIncrement($metric, $tags, $host))->onQueue($sqs);
                dispatch($job);
            }
        } else if($driver == "job") {
            dispatch(new DataDogIncrement($metric, $tags, $host));
        } else {
            $datadogclient = new DataDogClient();
            $datadogclient->increment($metric, $tags, $host);
        }
    }
}
