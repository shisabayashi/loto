<?php

use Phalcon\Loader;
use Phalcon\Mvc\Micro;
use Phalcon\Di\FactoryDefault;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;

$loader = new Loader();

$loader->registerDirs(
    [
        __DIR__ . "/models/entity",
        __DIR__ . "/models/service/api",
    ]
);

$loader->register();

$di = new FactoryDefault();

// 設定ファイルを読み込み
$configFile = __DIR__ . "/config/config.php";
$config = '';

if (is_readable($configFile)) {
    $config = include $configFile;

    $di->set("config", $config);
}

// database
$di->set('db', function() use($config){
    $dbAdapter =  new DbAdapter(array(
        "host"     => $config->database->host,
        "username" => $config->database->username,
        "password" => $config->database->password,
        "dbname"   => $config->database->dbname,
    ));
    return $dbAdapter;
});

$app = new Micro($di);

// check
$app->get('/', function() {
    echo "RESTful API Phalcon";
});

// get lottery result
$app->get(
    "/api/event-number/{event_number:[0-9]+}",
    function ($event_number) use ($app, $config) {

        $type_id = $app->request->getQuery("type_id");
        $requestToken = $app->request->getQuery("token");
        $token = $config->api_token;
        $generateToken = hash('sha256', "$event_number-$token");
        if (!($requestToken === $generateToken)) {
            echo "Unavailable token";
            return;
        }

        $phql = "SELECT lr.loto_numbers, lr.bonus_numbers 
                    FROM EventNumberEntity en, LotteryResultEntity lr 
                    WHERE en.event_number = :event_number: 
                    AND en.loto_type_id = :type_id: 
                    AND en.id = lr.event_number_id";

        $results = $app->modelsManager->executeQuery(
            $phql,
            [
                "event_number" => $event_number,
                "type_id"      => $type_id,
            ]
        );

        $data = [];
        foreach ($results as $result) {
            $data[] = [
                "loto_numbers"  => $result->loto_numbers,
                "bonus_numbers" => $result->bonus_numbers,
            ];
        }

        echo json_encode($data);
    }
);

// get latest event number
$app->get(
    "/api/latest-event-number/type-id/{type_id:[0-9]}",
    function ($type_id) use ($app, $config) {

        $requestToken = $app->request->getQuery("token");
        $token = $config->api_token;
        $generateToken = hash('sha256', $type_id .'-' .$token);
        if (!($requestToken === $generateToken)) {
            echo "Unavailable token" .PHP_EOL;
            return;
        }

        $phql = "SELECT event_number FROM LatestEventEntity WHERE loto_type_id = :type_id:";
        $results = $app->modelsManager->executeQuery(
            $phql,
            [
                "type_id" => $type_id,
            ]
        );

        $data = [];
        foreach ($results as $result) {
            $data[] = [
                "event_number"  => $result->event_number,
            ];
        }

        echo json_encode($data);
    }
);

$app->get(
    "/api/event-number-list/type-id/{type_id:[0-9]}",
    function ($type_id) use ($app, $config) {

        $requestToken = $app->request->getQuery("token");
        $token = $config->api_token;
        $generateToken = hash('sha256', $type_id .'-' .$token);
        if (!($requestToken === $generateToken)) {
            echo "Unavailable token" .PHP_EOL;
            return;
        }

        $phql = "SELECT event_number
                FROM EventNumberEntity
                WHERE loto_type_id = :type_id:
                ORDER BY event_number DESC
                LIMIT 5";
        $results = $app->modelsManager->executeQuery(
            $phql,
            [
                "type_id" => $type_id,
            ]
        );

        $data = [];
        foreach ($results as $result) {
            $data['event_number'][] = $result->event_number;
        }

        //echo json_encode($data);
        echo "aaa";
    }
);

$app->handle();
