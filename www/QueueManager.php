<?php
require_once 'vendor/autoload.php';

use Kafka\Producer;
use Kafka\ProducerConfig;
use Kafka\Consumer;
use Kafka\ConsumerConfig;

class QueueManager {
    private $topic = 'lab7_topic';

    public function __construct() {
        // Инициализация конфигурации (может потребоваться для некоторых версий)
    }

    public function publish($data) {
        $config = ProducerConfig::getInstance();
        $config->setMetadataBrokerList('kafka:9092');
        $config->setRequiredAck(1);
        $config->setIsAsyn(false);
        $config->setProduceInterval(500);

        $producer = new Producer();
        $producer->send([
            [
                'topic' => $this->topic,
                'value' => json_encode($data),
                'key' => uniqid(),
            ],
        ]);
    }

    public function consume(callable $callback) {
        $config = ConsumerConfig::getInstance();
        $config->setMetadataBrokerList('kafka:9092');
        $config->setGroupId('lab7_group');
        $config->setTopics([$this->topic]);
        $config->setOffsetReset('earliest');

        $consumer = new Consumer();
        $consumer->start(function($topic, $part, $message) use ($callback) {
            if (isset($message['message']['value'])) {
                $data = json_decode($message['message']['value'], true);
                if ($data) {
                    $callback($data);
                }
            }
            return true;
        });
    }
}