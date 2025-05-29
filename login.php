<?php
session_start();
require 'db.php'; // Подключение к БД

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Поиск пользователя
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // Успешная авторизация
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'];

        // Ответ для AJAX
        echo json_encode([
            'success' => true,
            'message' => 'Авторизация успешна',
            'redirect' => $user['role'] === 'admin' ? 'admin_dashboard.php' : 'user_dashboard.php'
        ]);
    } else {
        // Ошибка авторизации
        echo json_encode([
            'success' => false,
            'message' => 'Неверный email или пароль'
        ]);
    }
    exit;
}
?>