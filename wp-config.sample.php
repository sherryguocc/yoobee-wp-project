<?php
// Import aws.phar
require __DIR__ . '/aws.phar';

// Using mysqli with SSL enabled
define('MYSQL_CLIENT_FLAGS', MYSQLI_CLIENT_SSL);

use Aws\SecretsManager\SecretsManagerClient;
use Aws\Exception\AwsException;

// Initialize the Secrets Manager client
$client = new SecretsManagerClient([
    'version' => 'latest',
    'region'  => 'ap-southeast-2'
]);

// Get the secret and parse the JSON content
$result = $client->getSecretValue([
    'SecretId' => 'rds/wordpress',
]);

$secret = $result['SecretString'];
$data = json_decode($secret, true);

// Setting WordPress database constants
define('DB_NAME',     $data['db_name']);
define('DB_USER',     $data['username']);
define('DB_PASSWORD', $data['password']);
define('DB_HOST',     $data['dbhost']);

define('DB_CHARSET', 'utf8');
define('DB_COLLATE', '');
$table_prefix = 'wp_';

// Debug Settings
define('WP_DEBUG', false);

// Absolute path definition
if (!defined('ABSPATH')) {
    define('ABSPATH', __DIR__ . '/');
}
require_once ABSPATH . 'wp-settings.php';
