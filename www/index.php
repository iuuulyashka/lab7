<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab 7 - Apache Kafka</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; padding: 20px; }
        .container { max-width: 1000px; margin: 0 auto; }
        .header { text-align: center; color: white; margin-bottom: 40px; }
        .header h1 { font-size: 2.5rem; margin-bottom: 10px; text-shadow: 2px 2px 4px rgba(0,0,0,0.3); }
        .header h2 { font-size: 1.3rem; font-weight: 300; opacity: 0.9; }
        .card { background: white; border-radius: 15px; padding: 30px; margin-bottom: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.2); }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: 600; color: #333; }
        input[type="text"] { width: 100%; padding: 12px 15px; border: 2px solid #e1e5e9; border-radius: 8px; font-size: 16px; }
        .btn { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; padding: 12px 30px; border-radius: 8px; font-size: 16px; cursor: pointer; }
        .log-container { background: #f8f9fa; border-radius: 10px; padding: 20px; margin-top: 20px; max-height: 400px; overflow-y: auto; }
        .log-entry { padding: 12px 15px; margin-bottom: 8px; background: white; border-radius: 8px; border-left: 4px solid #667eea; }
        .alert { padding: 15px; border-radius: 8px; margin-bottom: 20px; text-align: center; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .links { display: flex; gap: 15px; flex-wrap: wrap; }
        .link-card { background: white; padding: 15px; border-radius: 10px; text-decoration: none; color: #333; flex: 1; min-width: 200px; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🚀 Lab 7 - Apache Kafka</h1>
            <h2>Асинхронная обработка через Kafka</h2>
        </div>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">✅ Сообщение отправлено в Kafka!</div>
        <?php endif; ?>
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-error">❌ Ошибка отправки</div>
        <?php endif; ?>

        <div class="card">
            <h3>📨 Отправка в Kafka</h3>
            <form method="POST" action="send.php">
                <div class="form-group">
                    <label for="name">Имя:</label>
                    <input type="text" id="name" name="name" placeholder="Введите имя" required>
                </div>
                <button type="submit" class="btn">Отправить в Kafka</button>
            </form>
        </div>

        <div class="card">
            <h3>📊 Лог обработки</h3>
            <div class="log-container">
                <?php
                if (file_exists('processed_kafka.log') && filesize('processed_kafka.log') > 0) {
                    $lines = array_reverse(file('processed_kafka.log'));
                    foreach ($lines as $line) {
                        $data = json_decode($line, true);
                        if ($data) {
                            echo '<div class="log-entry">';
                            echo '<div><strong>' . htmlspecialchars($data['timestamp']) . '</strong> - ' . htmlspecialchars($data['name']) . '</div>';
                            echo '</div>';
                        }
                    }
                } else {
                    echo '<p>Нет обработанных сообщений</p>';
                }
                ?>
            </div>
        </div>

        <div class="links">
            <a href="http://localhost:9000" target="_blank" class="link-card">🖥️ Kafdrop UI</a>
            <a href="send.php?name=Test" class="link-card">🧪 Тест</a>
            <a href="worker.php" target="_blank" class="link-card">👷 Worker</a>
        </div>
    </div>
</body>
</html>