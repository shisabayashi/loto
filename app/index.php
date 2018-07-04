<?php
ini_set("display_errors", 1);
error_reporting(E_ALL);


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
        $generateToken = hash('sha256', $event_number .'-' .$token);
        if (!($requestToken === $generateToken)) {
            echo "Unavailable token";
            return;
        }
        $phql = "SELECT loto_numbers, bonus_numbers, loto_date
                    FROM LotteryResultEntity
                    WHERE event_number = :event_number: 
                    AND loto_type_id = :type_id: ";
        $results = $app->modelsManager->executeQuery(
            $phql,
            [
                "event_number" => $event_number,
                "type_id"      => $type_id,
            ]
        );
        $data = [];
        foreach ($results as $result) {
            $data["loto_numbers"] = $result->loto_numbers;
            $data["bonus_numbers"] = $result->bonus_numbers;
            $data["loto_date"] = $result->loto_date;
        }

        echo json_encode($data);
    }
);

$app->get(
    "/api/event-number-list/type_id/{type_id:[0-9]}",
    function ($type_id) use ($app, $config) {

        $requestToken = $app->request->getQuery("token");
        $token = $config->api_token;
        $generateToken = hash('sha256', $type_id .'-' .$token);
        if (!($requestToken === $generateToken)) {
            echo "Unavailable token" .PHP_EOL;
            return;
        }
        $phql = "SELECT event_number
                FROM LotteryResultEntity
                WHERE loto_type_id = :type_id:
                ORDER BY event_number DESC
                LIMIT 10";
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

        echo json_encode($data);
    }
);

$app->get(
    "/api/create-loto-number/type_id/{type_id:[0-9]}",
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

        $monthColumns = [
            '01' => 'jan01',
            '02' => 'feb02',
            '03' => 'mar03',
            '04' => 'apr04',
            '05' => 'may05',
            '06' => 'jun06',
            '07' => 'jul07',
            '08' => 'aug08',
            '09' => 'sep09',
            '10' => 'oct10',
            '11' => 'nov11',
            '12' => 'dec12',
        ];
        $monthStr = $monthColumns[substr(date('Ym'), -2)];

        $results = $this->modelsManager->createBuilder()
            ->columns('loto_number')
            ->from('NumberRankingEntity')
            ->where('loto_type_id = :type_id:', ['type_id' => $type_id])
            ->andWhere($monthStr .
                ' = (SELECT MAX(' .$monthStr .')  FROM NumberRankingEntity WHERE loto_type_id = ' .$type_id .')')
            ->getQuery()
            ->execute();

        $rankArray = [];
        foreach ($results as $result) {
            $rankArray[] = $result->loto_number;
        }

        $lotoNumArray = [];
        $loopCnt = 1;
        while(true) {
            $lotoNumber = implode("", randomNumber($lotoNum, $maxNum, $latestArray, $rankArray));
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
                    $lotoNumber = implode("", randomNumber($lotoNum, $maxNum, $latestArray, $rankArray));
                    if (in_array($lotoNumber, $lotoNumArray) === false) {
                        $lotoNumArray[$key] = $lotoNumber;
                        break 1;
                    }
                } // while end
            } // foreach end
        } // while end

//var_dump($lotoNumArray);

        $data = [];
        foreach ($lotoNumArray as $lval) {
            $data['loto_number'][] = $lval;
        }

        echo json_encode($data);
    }
);

$app->handle();

function randomNumber($lotoNum, $maxNum, $latestNumArray, $rankArray){

    $lotoNumArray = [];
    $cnt = 0;

    // ランダム数字生成
    while (true) {
        if (!empty($rankArray) && rand(1, 12) === 2) {
            $rankKey = array_rand($rankArray, 1);
            $randNum = sprintf('%02d', $rankArray[$rankKey]);
        } else {
            $randNum = sprintf('%02d', rand(1, $maxNum));
        }

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
            $lotoNumArray = randomNumber($lotoNum, $maxNum, $latestNumArray, $rankArray);
        }
    }

    return $lotoNumArray;
}

