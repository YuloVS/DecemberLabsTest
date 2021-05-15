<?php

return [
    "key" => env("FIXERIO_API_KEY"),
    "subscription_plan" => env("FIXERIO_SUBSCRIPTION_PLAN", false), // False for free, true for paid
    "cache_time" => env("FIXERIO_CACHE_TIME", 3600), // In seconds
];