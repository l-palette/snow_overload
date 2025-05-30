<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Метод не поддерживается']);
    exit;
}

$email = $_POST['email'] ?? '';
$user_password = $_POST['password'] ?? '';

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
$db_password = "S6978a49";
$dbname = "netforces";

try {
    $conn = new mysqli($host, $username, $db_password, $dbname);

    if ($conn->connect_error) {
        throw new Exception('Ошибка подключения: ' . $conn->connect_error);
    }

    // Изменяем запрос, чтобы получить также роль пользователя
    $stmt = $conn->prepare("SELECT id, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'Пользователь не найден']);
        exit;
    }

    $user = $result->fetch_assoc();

    if (password_verify($user_password, $user['password'])) {
        session_start();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $email;
        $_SESSION['role'] = $user['role']; // Сохраняем роль в сессии

        // Определяем страницу для перенаправления в зависимости от роли
        $redirect_page = ($user['role'] === 'admin') ? 'users.html' : 'post.html';

        echo json_encode([
            'success' => true,
            'message' => 'Вход выполнен успешно!',
            'redirect' => $redirect_page
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Неверный пароль']);
    }

    $stmt->close();
    $conn->close();

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Ошибка: ' . $e->getMessage()]);
}
?>