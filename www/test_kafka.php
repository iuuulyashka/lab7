<?php
echo "Testing Kafka send...\n";
try {
    $conf = new RdKafka\Conf();
    $conf->set("bootstrap.servers", "kafka:9092");
    $producer = new RdKafka\Producer($conf);
    $topic = $producer->newTopic("lab7_topic");
    
    $data = ["name" => "Direct Test", "time" => date("H:i:s")];
    $payload = json_encode($data);
    
    echo "Sending: $payload\n";
    $topic->produce(RD_KAFKA_PARTITION_UA, 0, $payload);
    
    $result = $producer->flush(10000);
    if ($result === RD_KAFKA_RESP_ERR_NO_ERROR) {
        echo "SUCCESS: Message sent!\n";
    } else {
        echo "FAILED: Error code $result\n";
    }
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
?>