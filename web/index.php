<?php

require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

$uuid = vsprintf('%s%s%s%s%s%s%s%s', str_split(bin2hex(random_bytes(16)), 4));
$userUUID = null;
$total = 0;

if (isset($_COOKIE['_welcome-client']) && !empty($_COOKIE['_welcome-client'])) {
    $userUUID = $_COOKIE['_welcome-client'];
} else {
    setcookie("_welcome-client", $uuid, strtotime('+1day'));
    $userUUID = $uuid;
}

$redisAuth = 'a975c295ddeab5b1a5323df92f61c4cc9fc88207';
$redis = new Redis();
//Connecting to Redis
$redis->connect('tcp://redis', 6379);
$redis->auth($redisAuth);


if ($redis->ping()) {
    /** Insert user id with life time */
    $redis->setEx($userUUID, 30, strtotime('+30 sec'));
    $total = $redis->dbSize();
} else {
    throw new RedisException("Ошибка соединения с Redis сервером");
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Количество пользователей на сайте</title>
    <link href='/css/style.css' rel='stylesheet'>
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
</head>
<body>
<h1 class="center">Сейчас на сайте!</h1>
<div class="center">
    <div class="loader">
        <div class="center">
            <p class="label-counter"><?= $total ?></p>
        </div>
    </div>
</div>
</body>
</html>

