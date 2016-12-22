<?php
//phpinfo();

echo "<font color=red>google.com test</font><br>";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://www.google.com');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$feedData = curl_exec($ch);

var_dump($feedData);
// error happen?
if(curl_errno($ch))
{
    echo 'Curl error: ' . curl_error($ch);
}
curl_close($ch);

echo "<br><br><font color=red>facebook.com test</font><br>";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://graph.facebook.com/nike/posts?access_token=439271626171835|-V79s0TIUVsjj_5lgc6ydVvaFZ8');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$feedData = curl_exec($ch);

var_dump($feedData);
// error happen?
if(curl_errno($ch))
{
    echo 'Curl error: ' . curl_error($ch);
}
curl_close($ch);


exit;






















///////////////////////////////////////////////////////////
require_once __DIR__ . '/Facebook/autoload.php';
$fb = new Facebook\Facebook([
  'app_id' => '931641080258473',
  'app_secret' => '31e6c67b1968a4e1338c6805617c93e2',
  'default_graph_version' => 'v2.2',
  ]);

$linkData = [
  'link' => 'http://www.4399.com',
  'message' => 'User provided message',
  ];

try {
  // Returns a `Facebook\FacebookResponse` object
  $response = $fb->post('/me/feed', $linkData, '{access-token}');
} catch(Facebook\Exceptions\FacebookResponseException $e) {
  echo 'Graph returned an error: ' . $e->getMessage();
  exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
  echo 'Facebook SDK returned an error: ' . $e->getMessage();
  exit;
}

$graphNode = $response->getGraphNode();

echo 'Posted with id: ' . $graphNode['id'];