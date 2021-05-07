<?php

require dirname(__DIR__).DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';

$uuid = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex(random_bytes(16)), 4));
$userUUID = null;
$redisAuth = 'a975c295ddeab5b1a5323df92f61c4cc9fc88207';

if (isset($_COOKIE['_welcome-client'])){
    $userUUID = $_COOKIE['_welcome-client'];
//    xdebug_var_dump($_COOKIE['_welcome-client']);
}else{
    setcookie("_welcome-client", $uuid, strtotime('+1 day'));
    $userUUID = $uuid;
}

$redis = new Redis();
//Connecting to Redis
$redis->connect('tcp://redis', 6379);
$redis->auth($redisAuth);

if ($redis->ping()) {
    //echo "PONGn";
    /** Записываем нового пользователя, если его еще нету.   */
    // if($redis->setNx($userUUID, strtotime('+30 sec'))){}

    /** Insert user id with life time */
    $redis->pSetEx($userUUID,30, strtotime('+30 sec'));
}
$total = $redis->dbSize();
echo "Total: ".round($total,0) ;
