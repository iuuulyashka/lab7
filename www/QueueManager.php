<?php
class QueueManager {
    private $topic = "lab7_topic";
    private $broker = "kafka:9092";

    public function publish($data) {
        try {
            $conf = new RdKafka\Conf();
            $conf->set("bootstrap.servers", $this->broker);
            $producer = new RdKafka\Producer($conf);
            $topic = $producer->newTopic($this->topic);
            
            $payload = json_encode($data);
            $topic->produce(RD_KAFKA_PARTITION_UA, 0, $payload);
            
            $result = $producer->flush(10000);
            return $result === RD_KAFKA_RESP_ERR_NO_ERROR;
        } catch (Exception $e) {
            return false;
        }
    }

    public function consume(callable $callback) {
        try {
            $conf = new RdKafka\Conf();
            $conf->set("group.id", "lab7_group");
            $conf->set("auto.offset.reset", "earliest");
            $conf->set("bootstrap.servers", $this->broker);
            
            $consumer = new RdKafka\KafkaConsumer($conf);
            $consumer->subscribe([$this->topic]);

            while (true) {
                $message = $consumer->consume(5000);
                if ($message->err === RD_KAFKA_RESP_ERR_NO_ERROR) {
                    $data = json_decode($message->payload, true);
                    if ($data) {
                        $callback($data);
                    }
                }
            }
        } catch (Exception $e) {
            throw $e;
        }
    }
}