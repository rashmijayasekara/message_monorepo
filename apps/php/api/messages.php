<?php
header('Content-Type: application/json');

$logFile = getenv('LOG_FILE') ?: '/data/message_log.txt';

function ensureLogDir(string $logFile): void {
    $dir = dirname($logFile);
    if ($dir && !is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

function readMessages(string $logFile): array {
    if (!file_exists($logFile)) return [];
    return file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [];
}

function appendMessage(string $logFile, string $message): void {
    ensureLogDir($logFile);
    file_put_contents($logFile, trim($message) . PHP_EOL, FILE_APPEND | LOCK_EX);
}

function jsonError(string $error, int $status): void {
    http_response_code($status);
    echo json_encode(['error' => $error]);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    echo json_encode(['messages' => readMessages($logFile)]);
} elseif ($method === 'POST') {
    $body = json_decode(file_get_contents('php://input'), true);
    if (!is_array($body) || empty(trim($body['message'] ?? ''))) {
        jsonError('message field is required', 400);
    }
    $message = trim($body['message']);
    appendMessage($logFile, $message);
    http_response_code(201);
    echo json_encode(['status' => 'ok', 'message' => $message]);
} else {
    jsonError('Method not allowed', 405);
}
