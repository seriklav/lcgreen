<?php
// comment out the following two lines when deployed to production
$debug = false;

if(isset($_SERVER['REMOTE_ADDR']) && ($_SERVER['REMOTE_ADDR'] == '217.113.21.195' || $_SERVER['REMOTE_ADDR'] == '::1')) {
    $debug = true;
}
defined('YII_DEBUG') or define('YII_DEBUG', $debug);
defined('YII_ENV') or define('YII_ENV', 'dev');

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../config/web.php';

(new yii\web\Application($config))->run();
