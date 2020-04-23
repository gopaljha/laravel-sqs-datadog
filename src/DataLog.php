<?php

namespace GopalJha\LaravelSQSDataDog;

use GopalJha\LaravelSQSDataDog\DataDogLog;

class DataLog
{
    public static function log($status = 'info', $message, $ddsource, $service, $ddtags, $hostname = "IntegratorV2")
    {
            $datadoglog = new DataDogLog();
            $datadoglog->log($status, $message, $ddsource, $service, $ddtags, $hostname);
    }
}
