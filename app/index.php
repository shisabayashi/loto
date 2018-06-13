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

        $data = [];
        echo json_encode($data);
    }
);

$app->get(
    "/api/event-number-list/type-id/{type_id:[0-9]}",
    function ($type_id) use ($app, $config) {

        $data = [];
        echo json_encode($data);
    }
);

$app->get(
    "/api/create-loto-number/type-id/{type_id:[0-9]}",
    function ($type_id) use ($app, $config) {

        $requestToken = $app->request->getQuery("token");
        $token = $config->api_token;
        $generateToken = hash('sha256', $type_id .'-' .$token);

        if (!($requestToken === $generateToken)) {
            echo "Unavailable token" .PHP_EOL;
        }

        $phql1 = "SELECT loto_numbers FROM LatestEventEntity WHERE loto_type_id = :type_id:";
        $results = $app->modelsManager->executeQuery(
            $phql1,
            [
                "type_id" => $type_id,
            ]
        );
        $lotoNumbers = '';
        foreach ($results as $result) {
            $lotoNumbers = $result->loto_numbers;
        }
        $latestArray = str_split($lotoNumbers, 2);

        $lotoNum = $config->loto_infos->config->$type_id->loto_num;
        $maxNum = $config->loto_infos->config->$type_id->max_num;
        $replyCnt = $config->loto_infos->reply_count;

        $lotoNumArray = [];
        $loopCnt = 1;
        while(true) {
            $lotoNumber = implode("", randomNumber($lotoNum, $maxNum, $latestArray));
            if (in_array($lotoNumber, $lotoNumArray) === false) {
                $lotoNumArray[] = $lotoNumber;
                $loopCnt++;
            }
            if ($loopCnt > $replyCnt) {
                break;
            }
        }

//var_dump($lotoNumArray);

        while(true){
            $results = $this->modelsManager->createBuilder()
                ->columns('loto_numbers')
                ->from('LotteryResultEntity')
                ->inWhere('loto_numbers', $lotoNumArray)
                ->andWhere('loto_type_id = :type_id:', ['type_id' => $type_id])
                ->getQuery()
                ->execute();

            if (count($results) === 0) {
                break;
            }

            foreach ($results as $result) {
                $key = array_search($result->loto_numbers, $lotoNumArray);
                while(true) {
                    $lotoNumber = implode("", randomNumber($lotoNum, $maxNum, $latestArray));
                    if (in_array($lotoNumber, $lotoNumArray) === false) {
                        $lotoNumArray[$key] = $lotoNumber;
                        break 1;
                    }
                } // while end
            } // foreach end
        } // while end

//var_dump($lotoNumArray);

        echo json_encode($lotoNumArray);
    }
);

$app->handle();

function randomNumber($lotoNum, $maxNum, $latestNumArray){

    $lotoNumArray = [];
    $cnt = 0;
    // ランダム数字生成
    for (;;) {
        $randNum = sprintf('%02d', rand(1, $maxNum));
        if (!in_array($randNum, $lotoNumArray)) {
            $lotoNumArray[] = $randNum;
            $cnt++;
            if($cnt == $lotoNum) {
                break;
            }
        }
    }
    sort($lotoNumArray);

    $arrCnt = count($lotoNumArray);
    $equ = 0;
    for ($i=0;$i<$arrCnt;$i++) {
        if (in_array($lotoNumArray[$i], $latestNumArray)){
            $equ++;
        }
        if ($equ === 3){
            echo 'equ' .PHP_EOL;
            $lotoNumArray = randomNumber($lotoNum, $maxNum, $latestNumArray);
        }
    }

    return $lotoNumArray;
}

