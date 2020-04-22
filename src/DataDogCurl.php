<?php

namespace GopalJha\LaravelSQSDataDog;

class DataDogCurl
{
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

        if (!function_exists('curl_init')) {
            return 'Sorry cURL is not installed!';
        }

        $url = config('datadog.host') . 'series?api_key=' . config('datadog.api_key');

        $ch = curl_init($url);
        $payload = json_encode(array("series" => [$series]));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
        $this->writeLog(" Curl response: " . json_encode($result));
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
