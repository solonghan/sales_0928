<?php

$ch = curl_init();

$base_url = 'https://sales.anbon.vip/';
curl_setopt($ch, CURLOPT_URL, $base_url . 'Cron/schedule_notify');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);

$r = curl_exec($ch);
curl_close($ch);