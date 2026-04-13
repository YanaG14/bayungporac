<?php
// Absolute path to Composer autoload
require_once __DIR__ . '/vendor/autoload.php';

use Elastic\Elasticsearch\ClientBuilder; // <-- updated namespace

// Elasticsearch credentials
$client = ClientBuilder::create()
    ->setHosts(['http://elastic:+oS3eMM6aW-BzO+_2o4Z@localhost:9200'])
    ->build();

$response = $client->info();
echo "<pre>";
print_r($response);