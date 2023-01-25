<?php
require_once('autoload.php');

$htaccess = md5(file_get_contents(".htaccess")) == '7e7f442dde6f43f5021abe94d4231351' ? 'OK' : '<font style="color:red">Please fix the .htaccess file.</font>';
$apikey = strlen($config['apikey']) != 45 ? '<font style="color:red">Pleas edit files config.php and replace \'YOUR_APIKEY\' with your valid API Key.</font>' : 'OK';
$curl = $Killbot->isCurlEnable() ? 'OK' : '<font style="color:red">Please enable CURL in your server or hosting.</font>';
if(strlen($config['apikey']) == 45){
    $connect = $Killbot->connection($config['apikey']) ? 'OK' : '<font style="color:red">Can\'t connect to Killbot due invalid API Key.</font>';
    $message = $htaccess == 'OK' && $apikey == 'OK' && $connect == 'OK' && $curl == 'OK' ? 'Everything is well organized, Killbot will protect from evil bots.' : 'APIKEY: ' . $apikey . ' | CONNECTION: ' . $connect. ' | HTACCESS: ' . $htaccess . ' | CURL: ' . $curl;
} else {
    $connect = '<font style="color:red">Can\'t connect to Killbot due invalid API Key.</font>';
    $message = $htaccess == 'OK' && $apikey == 'OK' && $curl == 'OK' ? 'Everything is well organized, Killbot will protect from evil bots.' : 'APIKEY: ' . $apikey . ' | HTACCESS: ' . $htaccess . ' | CURL: ' . $curl;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Killbot Tracking Services</title>
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
    <style>
        html, body{background-color: #fff; color: #636b6f; font-family: 'Raleway', sans-serif; font-weight: 100; height: 100vh; margin: 0;}.full-height{height: 100vh;}.flex-center{align-items: center; display: flex; justify-content: center;}.position-ref{position: relative;}.top-right{position: absolute; right: 10px; top: 18px;}.content{text-align: center;}.title{font-size: 84px;}.links > a{color: #636b6f; padding: 0 25px; font-size: 12px; font-weight: 600; letter-spacing: .1rem; text-decoration: none; text-transform: uppercase;}.m-b-md{margin-bottom: 30px;}
    </style>
</head>

<body>
    <div class="flex-center position-ref full-height">
        <div class="content">
            <div class="title m-b-md">Killbot Tracking Services</div>
            <div class="links"> <a href="#"><?=$message;?></a> </div>
        </div>
    </div>
</body>

</html>