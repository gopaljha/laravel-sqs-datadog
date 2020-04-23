<?php

namespace GopalJha\LaravelSQSDataDog;

class DataDogCurl
{
    public function increment($metric, $tags, $host)
    {
        if (!function_exists('curl_init')) {
            return 'Sorry cURL is not installed!';
        }
        try {
            // code...
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
    
            $url = config('datadog.HOST') . 'series?api_key=' . config('datadog.API_KEY');
    
            $ch = curl_init($url);
            $payload = json_encode(array("series" => [$series]));
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($ch);
            curl_close($ch);
            $this->writeLog(" DataDog curl response: " . json_encode($result));
        } catch (\Exception $th) {
            $this->writeLog(" DataDog curl exception response: " . json_encode($th->getMessage()));
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
