<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Метод не поддерживается']);
    exit;
}

$email = $_POST['email'] ?? '';
$user_password = $_POST['password'] ?? ''; // Changed variable name

if (empty($email)) {
    echo json_encode(['success' => false, 'message' => 'Введите email']);
    exit;
}

if (empty($user_password)) {
    echo json_encode(['success' => false, 'message' => 'Введите пароль']);
    exit;
}

$host = "localhost";
$username = "netforces";
$db_password = "S6978a49"; // Changed variable name
$dbname = "netforces";

// Using mysqli instead of PDO
$conn = new mysqli($host, $username, $db_password, $dbname);

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Ошибка подключения: ' . $conn->connect_error]);
    exit;
}

$stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Пользователь не найден']);
    exit;
}

$user = $result->fetch_assoc();

// Compare with the user-provided password (now called $user_password)
if ($user_password === $user['password']) {
    session_start();
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['email'] = $email;

    echo json_encode([
        'success' => true,
        'message' => 'Вход выполнен успешно!',
        'redirect' => 'forum.html'
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Неверный пароль']);
}

$stmt->close();
$conn->close();
?>