<?php


namespace App\Helpers;


use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class Fixerio
{
    private string $key;
    private string $url;
    private int $cacheTime;

    public function __construct()
    {
        $this->key = config("fixerio")["key"];
        $this->cacheTime = config("fixerio")["cache_time"];
        $this->setUrl();
    }

    private function setUrl()
    {
        if(config("fixerio")["subscription_plan"])
        {
            $this->url = "https://data.fixer.io/api/";
        }
        else
        {
            $this->url = "http://data.fixer.io/api/";
        }
    }

    public static function latest()
    {
        return app(self::class)->get("latest");
    }

    public function get(string $param)
    : array
    {
        return Cache::remember("fixerio_$param", $this->cacheTime, function() use ($param){
            return Http::get("$this->url$param", [
                "access_key" => $this->key
            ])->json();
        });

    }

}