<?php
// 引入 aws.phar（必须提前放在 wordpress 根目录下）
require __DIR__ . '/aws.phar';

// 使用 mysqli 并启用 SSL
define('DB_CLIENT_FLAGS', MYSQLI_CLIENT_SSL);

use Aws\SecretsManager\SecretsManagerClient;
use Aws\Exception\AwsException;

// 初始化 Secrets Manager 客户端
$client = new SecretsManagerClient([
    'version' => 'latest',
    'region'  => 'ap-southeast-2'
]);

// 获取 secret 并解析 JSON 内容
$result = $client->getSecretValue([
    'SecretId' => 'rds/wordpress',
]);

$secret = $result['SecretString'];
$data = json_decode($secret, true);

// 设置 WordPress 数据库常量
define('DB_NAME',     $data['db_name']);
define('DB_USER',     $data['username']);
define('DB_PASSWORD', $data['password']);
define('DB_HOST',     $data['dbhost']);

define('DB_CHARSET', 'utf8');
define('DB_COLLATE', '');
$table_prefix = 'wp_';

// Debug 设置
define('WP_DEBUG', false);

// 绝对路径定义
if (!defined('ABSPATH')) {
    define('ABSPATH', __DIR__ . '/');
}
require_once ABSPATH . 'wp-settings.php';
