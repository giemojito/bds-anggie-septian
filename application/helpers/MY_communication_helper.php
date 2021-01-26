<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
if ( ! function_exists('ApiJakarta')) {
  function ApiJakarta($uRL, $authorization) {
    $cURL = curl_init();

    curl_setopt_array($cURL, array(
        CURLOPT_URL             => $uRL,
        CURLOPT_RETURNTRANSFER  => true,
        CURLOPT_MAXREDIRS       => 10,
        CURLOPT_TIMEOUT         => 30,
        CURLOPT_HTTP_VERSION    => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST   => "GET",
        CURLOPT_HTTPHEADER      => array(
          "Accept: */*",
          "Connection: Keep-Alive",
          "authorization: " . $authorization,
          "cache-control: no-cache",
        )
      )
    );

    $response = curl_exec($cURL);
    $err = curl_error($cURL);

    curl_close($cURL);

    if ($err) {
      return "cURL Error #:" . $err;
    } else {
      return json_decode($response);
    }
  }
}