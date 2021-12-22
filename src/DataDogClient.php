<?php

namespace GopalJha\LaravelSQSDataDog;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use function GuzzleHttp\json_encode;

class DataDogClient
{
    public function __construct()
    {
        $this->client = new Client;
    }

    public function increment($metric, $tags, $host)
    {
        $series = [
            'metric' => $metric,
            'points' => [
                array(time(), 1),
            ],
            'type' => 'count',
        ];

        if (!empty($tags)) {
            $series['tags'] = $tags;
        }

        if (!is_null($host)) {
            $series['host'] = $host;
        }

        try {
            $this->client->post(
                config('datadog.HOST') . 'series?api_key=' . config('datadog.API_KEY'),
                [
                    RequestOptions::JSON => [
                        'series' => [$series],
                    ],
                ]
            );
        } catch (\Exception $th) {
            $this->writeLog("Metrix: " . json_encode($series));
            $this->writeLog("Parent Error: " . json_encode($th->getMessage()));

            try {
                $this->client->request(
                    "POST",
                    config('datadog.HOST') . 'series?api_key=' . config('datadog.API_KEY'),
                    [
                        "json" => [
                            'series' => [$series],
                        ],
                    ]
                );
            } catch (\Exception $td) {
                $this->writeLog("Metrix: " . json_encode($series));
                $this->writeLog("Child Error: " . json_encode($td->getMessage()));
            }
        }
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
