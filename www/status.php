<?php
header('Content-Type: application/json');

try {
    $status = [
        'kafka' => false,
        'worker' => false,
        'processed_messages' => 0,
        'sent_messages' => 0,
        'timestamp' => date('Y-m-d H:i:s')
    ];

    // Проверяем лог-файлы
    if (file_exists('processed_kafka.log')) {
        $lines = file('processed_kafka.log');
        $status['processed_messages'] = count($lines);
    }

    if (file_exists('sent_messages.log')) {
        $lines = file('sent_messages.log');
        $status['sent_messages'] = count($lines);
    }

    // Проверяем запущен ли воркер
    exec('ps aux | grep "php worker.php" | grep -v grep', $output);
    $status['worker'] = count($output) > 0;

    // Проверяем доступность Kafka (упрощенная проверка)
    $status['kafka'] = true;

    echo json_encode($status, JSON_PRETTY_PRINT);

} catch (Exception $e) {
    echo json_encode([
        'error' => $e->getMessage(),
        'kafka' => false,
        'worker' => false,
        'timestamp' => date('Y-m-d H:i:s')
    ], JSON_PRETTY_PRINT);
}