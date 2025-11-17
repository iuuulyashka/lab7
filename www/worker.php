<?php
require 'QueueManager.php';

// Создаем лог-файл если его нет
if (!file_exists('processed_kafka.log')) {
    file_put_contents('processed_kafka.log', '');
}

echo "👷 Рабочий запущен (Kafka)...\n";
echo "⏳ Ожидание сообщений из топика lab7_topic...\n\n";

try {
    $q = new QueueManager();

    $q->consume(function($data) {
        echo "📥 Получено сообщение: " . json_encode($data) . "\n";
        
        // Имитация обработки
        echo "⏳ Обработка...";
        sleep(1);
        
        // Записываем в лог
        file_put_contents('processed_kafka.log', json_encode($data) . PHP_EOL, FILE_APPEND);
        echo "✅ Обработано\n\n";
    });

} catch (Exception $e) {
    echo "❌ Ошибка: " . $e->getMessage() . "\n";
    echo "🔄 Перезапуск через 5 секунд...\n";
    sleep(5);
}