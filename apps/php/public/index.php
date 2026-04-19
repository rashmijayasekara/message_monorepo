<?php
$logFile = getenv('LOG_FILE') ?: '/data/message_log.txt';

function ensureLogDir(string $logFile): void {
    $dir = dirname($logFile);
    if ($dir && !is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

function readMessages(string $logFile): array {
    if (!file_exists($logFile)) return [];
    $lines = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    return $lines ?: [];
}

function appendMessage(string $logFile, string $message): void {
    ensureLogDir($logFile);
    file_put_contents($logFile, trim($message) . PHP_EOL, FILE_APPEND | LOCK_EX);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = trim($_POST['message'] ?? '');
    if ($message !== '') {
        appendMessage($logFile, $message);
    }
    header('Location: /');
    exit;
}

$messages = readMessages($logFile);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Message Logger</title>
  <style>
    body { font-family: Arial, sans-serif; max-width: 600px; margin: 40px auto; padding: 0 20px; }
    input[type="text"] { width: 100%; padding: 8px; font-size: 16px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; margin-bottom: 10px; }
    button { padding: 8px 20px; font-size: 16px; background: #0070f3; color: white; border: none; border-radius: 4px; cursor: pointer; }
    ul { padding-left: 20px; }
    li { margin-bottom: 8px; }
  </style>
</head>
<body>
  <h1>Message Logger</h1>

  <form method="POST" action="/">
    <label for="message"><strong>Message:</strong></label><br/>
    <input id="message" name="message" type="text" placeholder="Enter your message..." required />
    <button type="submit">Submit</button>
  </form>

  <h2>Logged Messages</h2>
  <?php if (!empty($messages)): ?>
    <ul>
      <?php foreach ($messages as $msg): ?>
        <li><?= htmlspecialchars($msg) ?></li>
      <?php endforeach; ?>
    </ul>
  <?php else: ?>
    <p style="color:#666">No messages yet.</p>
  <?php endif; ?>
</body>
</html>
