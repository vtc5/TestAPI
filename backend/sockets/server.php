<?php

// pecl install event
require_once __DIR__ . '/vendor/autoload.php';
use Workerman\Worker;
//Worker::$eventLoopClass = '\Workerman\Events\Ev';

// массив для связи соединения пользователя и необходимого нам параметра
$users = [];

// создаём ws-сервер, к которому будут подключаться все наши пользователи
$ws_worker = new Worker("websocket://0.0.0.0:8000");
// создаём обработчик, который будет выполняться при запуске ws-сервера

$ws_worker->onWorkerStart = function() use (&$users)
{
  // создаём локальный tcp-сервер, чтобы отправлять на него сообщения из кода нашего сайта
  $inner_tcp_worker = new Worker("tcp://0.0.0.0:1234");
  // создаём обработчик сообщений, который будет срабатывать,
  // когда на локальный tcp-сокет приходит сообщение
  $inner_tcp_worker->onMessage = function($connection, $data) use (&$users) {
    $data = json_decode($data);
    if (is_object($data)) {
      // отправляем сообщение пользователю по userId
      if (isset($users[$data->user])) {
        $webconnection = $users[$data->user];
        $webconnection->send($data->message);
      }
    }
  };
  $inner_tcp_worker->listen();
};


$ws_worker->onConnect = function($connection) use (&$users)
{
  /** @var \Workerman\Connection\TcpConnection $connection */
  $connection->onWebSocketConnect = function($connection) use (&$users)
  {
    $connection->onMessage = function ($connection, $data) use (&$users) {
      print_r($data);
    };
    echo $connection->getRemoteIp() . " - userId:" . $_GET['user'] . "; memory ".memory_get_usage() ."\n";
    // при подключении нового пользователя сохраняем get-параметр, который же сами и передали со страницы сайта
    $users[$_GET['user']] = $connection;
    // вместо get-параметра можно также использовать параметр из cookie, например $_COOKIE['PHPSESSID']
    $connection->send('Ok');
  };
};

/** @var \Workerman\Connection\TcpConnection $connection */
$ws_worker->onClose = function($connection) use(&$users)
{
  echo $connection->getRemoteIp(). " - disconnected \n";
  // удаляем параметр при отключении пользователя
  $user = array_search($connection, $users);
  unset($users[$user]);
};

// Run worker
Worker::runAll();