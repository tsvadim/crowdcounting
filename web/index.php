<?php

require dirname(__DIR__).DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';


$uuid = vsprintf('%s%s%s%s%s%s%s%s', str_split(bin2hex(random_bytes(16)), 4));
$userUUID = null;


if (isset($_COOKIE['_welcome-client'])){
    $userUUID = $_COOKIE['_welcome-client'];
}else{
   $SS=  setcookie("_welcome-client", $uuid, strtotime('+30 sec'));
    $userUUID = $uuid;
}

$redisAuth = 'a975c295ddeab5b1a5323df92f61c4cc9fc88207';
$redis = new Redis();
//Connecting to Redis
$redis->connect('tcp://redis', 6379);
$redis->auth($redisAuth);


if ($redis->ping()) {
//    echo "PONGn";


}
/** Записываем нового пользователя, если его еще нету.   */
//if($redis->setNx($userUUID, strtotime('+30 sec'))){}
/** Insert user id with life time */
$redis->setEx($userUUID,30, strtotime('+30 sec'));
//$redis->pSetEx($userUUID,30, strtotime('+30 sec'));
$total = $redis->dbSize();
//var_dump($total);
//var_dump($redis->getDbNum());
?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        .center {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 120px;
        }
        .loader {
            border: 16px solid #f3f3f3;
            border-radius: 50%;
            border-top: 16px solid #198754 ;
            border-bottom: 16px solid #198754 ;
            width: 120px;
            height: 120px;
        }
        .label-counter{
            color: #198754;
            text-align: center;
            font-size: 40pt;
        }
    </style>
</head>
<body>
<h1 class="center">Сейчас на сайте!</h1>

<div class="center">
    <div class="loader">
        <div class="center">
            <p class="label-counter"><?=$total?></p>
        </div>
    </div>
</div>

</body>
</html>

