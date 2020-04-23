<?php

namespace GopalJha\LaravelSQSDataDog;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use function GuzzleHttp\json_encode;

class DataDogLog
{
    public function __construct()
    {
        $this->client = new Client;
    }

    public function log($status, $message, $ddsource, $service, $ddtags, $hostname)
    {
        if (!empty($status)) {
            $log['status'] = $status;
        }

        if (!empty($message)) {
            $log['message'] = $message;
        }

        if (!empty($ddsource)) {
            $log['ddsource'] = $ddsource;
        }

        if (!empty($service)) {
            $log['service'] = $service;
        }

        if (!empty($ddtags)) {
            $log['ddtags'] = $ddtags;
        }

        if (!is_null($hostname)) {
            $log['hostname'] = $hostname;
        }

        return retry(3, function () use ($log) {
            try {
                $this->client->post(
                    config('datadog.LOG_ENDPOINT') . config('datadog.API_KEY'),
                    [
                        RequestOptions::JSON => $log,
                    ]
                );
            } catch (\Exception $th) {
                $this->writeLog("LOG_Metrix: " . json_encode($log));
                $this->writeLog("LOG_Parent Error: " . json_encode($th->getMessage()));
                
                try {
                    $this->client->request(
                        "POST",
                        config('datadog.LOG_ENDPOINT') . config('datadog.API_KEY'),
                        ["json" => $log,]
                    );
                } catch (\Exception $td) {
                    $this->writeLog("LOG_Metrix: " . json_encode($log));
                    $this->writeLog("LOG_Child Error: " . json_encode($td->getMessage()));
                }
            }
        }, 500);
    }

    public function writeLog($message = null)
    {
        $cudate = date("Y-m-d H:i:s");
        if ($fp = fopen(storage_path('logs/datadog_' . date('Y-m-d') . '.log'), 'a')) {
            fwrite($fp, $cudate . "====>" . $message . PHP_EOL);
            fclose($fp);
        }
    }
}
