<?php

/**
* access tokenの取得
*
*/
function getAccessToken($client_id, $client_secret, $grant_type = "client_credentials", $scope = "http://api.microsofttranslator.com"){
    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => "https://datamarket.accesscontrol.windows.net/v2/OAuth2-13/",
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query(array(
            "grant_type" => $grant_type,
            "scope" => $scope,
            "client_id" => $client_id,
            "client_secret" => $client_secret
            ))
        ));
    return json_decode(curl_exec($ch));
}

/*
* 翻訳
* @param string $text
* @return string
*/
function translator($text){
	$access_token = getAccessToken("bistro","u/LQzfFkwge2w646H8KAmOfDcnRqoRScmpYHfMZzE/A=")->access_token;
	$params =  array('text' => $text,'to' => 'ja', 'from' => 'en');
    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => "https://api.microsofttranslator.com/v2/Http.svc/Translate?".http_build_query($params),
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HEADER => true,
        CURLOPT_HTTPHEADER => array(
            "Authorization: Bearer ". $access_token),
        ));
	preg_match('/>(.+?)<\/string>/',curl_exec($ch), $m);
    return $m[1];
}

?>