<?php
/*
 * Modified: prepend directory path of current file, because of this file own different ENV under between Apache and command line.
 * NOTE: please remove this comment.
 */
defined('BASE_PATH') || define('BASE_PATH', getenv('BASE_PATH') ?: realpath(dirname(__FILE__) . '/../..'));
defined('APP_PATH') || define('APP_PATH', BASE_PATH . '/app');

return new \Phalcon\Config([
    'database' => [
        'adapter'     => 'Mysql',
        'host'        => 'localhost',
        'username'    => '',
        'password'    => '',
        'dbname'      => '',
        'charset'     => 'utf8',
    ],
    'application' => [
        'appDir'         => APP_PATH . '/',
        'controllersDir' => APP_PATH . '/controllers/',
        'modelsDir'      => APP_PATH . '/models/',
        'migrationsDir'  => APP_PATH . '/migrations/',
        'viewsDir'       => APP_PATH . '/views/',
        'pluginsDir'     => APP_PATH . '/plugins/',
        'libraryDir'     => APP_PATH . '/library/',
        'cacheDir'       => BASE_PATH . '/cache/',

        // This allows the baseUri to be understand project paths that are not in the root directory
        // of the webpspace.  This will break if the public/index.php entry point is moved or
        // possibly if the web server rewrite rules are changed. This can also be set to a static path.
        'baseUri'        => preg_replace('/public([\/\\\\])index.php$/', '', $_SERVER["PHP_SELF"]),
    ],
    'loto_infos' => [
        '5'  => [
            'url'            => "https://www.mizuhobank.co.jp/takarakuji/loto/miniloto/index.html",
            'loto_num'       => "5",
            'bonus_num'      => "1",
            'get_data_count' => "1",
        ],
        '6'  => [
            'url'            => "https://www.mizuhobank.co.jp/takarakuji/loto/loto6/index.html",
            'loto_num'       => "6",
            'bonus_num'      => "1",
            'get_data_count' => "1",
        ],
        '7'  => [
            'url'            => "https://www.mizuhobank.co.jp/takarakuji/loto/loto7/index.html",
            'loto_num'       => "7",
            'bonus_num'      => "2",
            'get_data_count' => "1",
        ],
    ],
    'scraping_info' => [
        'table_element'        => "table.typeTK:eq(%num%)",
        'event_number_element' => ".alnCenter.bgf7f7f7:eq(0)",
        'event_date_element'   => "td.alnCenter:eq(0)",
        'loto_number_element'  => ".alnCenter.extension:eq(%num%)",
        'bonus_number_element' => ".alnCenter.extension.green:eq(%num%)",
    ],
    'api_token' => '',
]);
