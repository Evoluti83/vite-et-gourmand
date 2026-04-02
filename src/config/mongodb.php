<?php

require_once __DIR__ . '/../../vendor/autoload.php';

function getMongoDB(): MongoDB\Database {
    static $db = null;
    if ($db === null) {
        $client = new MongoDB\Client(
            'mongodb+srv://vite_gourmand:o2lp3pz3YmflR4qA@cluster0.7dp6pji.mongodb.net/?appName=Cluster0'
        );
        $db = $client->vite_gourmand;
    }
    return $db;
}