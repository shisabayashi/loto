<?php

use Phalcon\Di\FactoryDefault\Cli as CliDI;
use Phalcon\Cli\Console as ConsoleApp;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
use Phalcon\Loader;

// CLI ファクトリのデフォルトサービスコンテナを使います
$di = new CliDI();

/**
 * オートローダを登録し、更にローダにタスク用ディレクトリを登録
 */
$loader = new Loader();

$loader->registerDirs(
    [
        __DIR__ . "/tasks",
        __DIR__ . "/models/dto",
        __DIR__ . "/models/entity",
        __DIR__ . "/models/repository",
        __DIR__ . "/models/service/api",
        __DIR__ . "/models/service/batch",
    ]
);

$loader->registerFiles(
    [
        __DIR__ . "/library/scraping/phpQuery-onefile.php",
    ]
);

$loader->register();

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

// コンソールアプリケーションを作成
$console = new ConsoleApp();

$console->setDI($di);

/**
 * コンソールの引数を処理
 */
$arguments = [];

foreach ($argv as $k => $arg) {
    if ($k === 1) {
        $arguments["task"] = $arg;
    } elseif ($k === 2) {
        $arguments["action"] = $arg;
    } elseif ($k >= 3) {
        $arguments["params"][] = $arg;
    }
}

try {
    // 渡された引数の処理
    $console->handle($arguments);
} catch (\Phalcon\Exception $e) {
    echo $e->getMessage();

    exit(255);
}
