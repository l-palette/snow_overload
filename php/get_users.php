<?php
header('Content-Type: application/json');

// Проверка авторизации (если нужно ограничить доступ)
session_start();
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Необходима авторизация']);
    exit;
}

$host = "localhost";
$username = "netforces";
$db_password = "S6978a49";
$dbname = "netforces";

try {
    $conn = new mysqli($host, $username, $db_password, $dbname);

    if ($conn->connect_error) {
        throw new Exception('Ошибка подключения: ' . $conn->connect_error);
    }

    $query = "SELECT id, username, email, role FROM users";
    $result = $conn->query($query);

    if (!$result) {
        throw new Exception('Ошибка запроса: ' . $conn->error);
    }

    $users = [];
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }

    echo json_encode(['success' => true, 'users' => $users]);

    $conn->close();

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Ошибка: ' . $e->getMessage()]);
}
?>