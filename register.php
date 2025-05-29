<?php
require 'db.php'; // Файл с подключением к БД

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Валидация
    if (empty($username) || empty($email) || empty($password)) {
        die("Все поля обязательны для заполнения");
    }

    if ($password !== $confirm_password) {
        die("Пароли не совпадают");
    }

    // Хэширование пароля
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Запрос к БД
    try {
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$username, $email, $hashed_password]);

        echo "Регистрация успешна! Теперь вы можете войти.";
    } catch (PDOException $e) {
        if ($e->errorInfo[1] == 1062) {
            die("Пользователь с таким именем или email уже существует");
        } else {
            die("Ошибка: " . $e->getMessage());
        }
    }
}
?>