<?php

header("Content-Type: text/plain");

require_once( dirname(__FILE__) . '/wp-load.php' );

$request = new WP_Http;
$api_url = 'http://www.facebook.com/';
$response = $request->get($api_url);

print_r($response);
?>