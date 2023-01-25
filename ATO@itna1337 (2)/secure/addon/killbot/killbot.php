<?php

class Killbot
{
	function __construct() {
		$this->domain = 'https://killbot.org';
	}
	function get_client_ip() {
		// Get real visitor IP behind CloudFlare network
        if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
            $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
            $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
        }
        $client  = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote  = $_SERVER['REMOTE_ADDR'];
        
        if(filter_var($client, FILTER_VALIDATE_IP)) {
            $ip = $client;
        } elseif(filter_var($forward, FILTER_VALIDATE_IP)) {
            $ip = $forward;
        } else {
            $ip = $remote;
        }
        
        return $ip;
	}
	function isCurlEnable(){
		return function_exists('curl_version');
	}
	function httpGet($url){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Killbot Blocker');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		$response = curl_exec($ch);
		return $response;
	}
	function httpPost($url , $array){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($array));
		curl_setopt($ch, CURLOPT_USERAGENT, 'Killbot Blocker');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		$response = curl_exec($ch);
		return $response;
	}
	function json($respons){
		return json_decode($respons,true);
	}
	function connection($apikey){
		$url 		= $this->domain."/api/v2/profile?apikey=".$apikey;
		$respons 	= $this->httpGet($url);
		$json		= $this->json($respons);
		return $json['meta']['code'] == 200 ? true : false;
	}
	function redirect($apikey, $keyname){
		$url 		= $this->domain."/api/v2/shortlink?ip=".$this->get_client_ip()."&apikey=".$apikey."&keyname=".$keyname."&ua=".urlencode($_SERVER['HTTP_USER_AGENT'])."&url=".urlencode($_SERVER['REQUEST_URI']);
		$respons 	= $this->httpGet($url);
		return $respons;
	}
	function error($code){
		$tempale = file_get_contents(__DIR__ . '/templates/main.html');
		switch ($code) {
			case 'self_403':
				header('HTTP/1.0 403 Forbidden');
				$tempale = str_replace("{text}", "403 Forbidden", $tempale);
				$tempale = str_replace("{error_message}", "You dont have authorization to view this page.", $tempale);
				die($tempale);
			break;
			case 'self_404':
				header("HTTP/1.0 404 Not Found");
				$tempale = str_replace("{text}", "404 Not Found", $tempale);
				$tempale = str_replace("{error_message}", "The requested was not found on this server.", $tempale);
				die($tempale);
			break;
			case 'self_suspend':
				header('HTTP/1.0 503 Service Unavailable');
				$tempale = str_replace("{text}", "Service Suspended", $tempale);
				$tempale = str_replace("{error_message}", "Please contact support to correct issues causing your service to be offline.", $tempale);
				die($tempale);
			break;
			case 'self_cloudflare':
				header('HTTP/1.0 522 Connection timed out');
				date_default_timezone_set('UTC');
				$cloudflare = file_get_contents(__DIR__ . '/templates/cloudflare.html');
				$cloudflare = str_replace("{domain}", $_SERVER['SERVER_NAME'], $cloudflare);
				$cloudflare = str_replace("{ray_id}", substr(md5(time()), 0, 16), $cloudflare);
				$cloudflare = str_replace("{ip_address}", $_SERVER['REMOTE_ADDR'], $cloudflare);
				$cloudflare = str_replace("{date}", date('Y-m-d h:i:s'), $cloudflare);
				die($cloudflare);
			break;
			default:
				die(header("Location: ".$code));
			break;
		}
	}
}