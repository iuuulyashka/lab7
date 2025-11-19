<?php
require "QueueManager.php";

if (!file_exists("processed_kafka.log")) {
    file_put_contents("processed_kafka.log", "");
}

echo "Worker started\n";

try {
    $q = new QueueManager();
    $q->consume(function($data) {
        echo "Received: " . json_encode($data) . "\n";
        sleep(2);
        file_put_contents("processed_kafka.log", json_encode($data) . PHP_EOL, FILE_APPEND);
        echo "Saved to log\n\n";
    });
} catch (Exception $e) {
    echo "Worker error: " . $e->getMessage() . "\n";
}