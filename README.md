База данных netforces
Логин netforces
Пароль S6978a49


### Структура
snow_overload/
|   php/
|   |   login.php
|   |   test_db.php
|   .gitignore
|   404.html
|   feedback.html
|   forum.html
|   index.html
|   login.html
|   recipe1.html
|   recipe2.html
|   recipe3.html
|   recipes.html
|   robots.txt
|   script.js
|   sitemap.xml
|   styles.css

login.php
<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Метод не поддерживается']);
    exit;
}

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($email)) {
    echo json_encode(['success' => false, 'message' => 'Введите email']);
    exit;
}

if (empty($password)) {
    echo json_encode(['success' => false, 'message' => 'Введите пароль']);
    exit;
}

$host = "localhost";
$username = "netforces";
$password = "S6978a49";
$dbname = "netforces";

// Using mysqli instead of PDO
$conn = new mysqli($host, $username, $password, $dbname);

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

if ($password === $user['password']) {
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

login.html
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход в систему</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Дополнительные стили для формы входа */
        .login-links {
            display: flex;
            justify-content: space-between;
            margin: 15px 0;
            font-size: 0.9em;
        }

        .login-links a {
            color: #fff;
            text-decoration: none;
            transition: all 0.3s ease;
            padding: 5px 10px;
            border-radius: 4px;
            background: rgba(255, 255, 255, 0.1);
        }

        .login-links a:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
        }

        .divider {
            display: flex;
            align-items: center;
            margin: 20px 0;
            color: rgba(255, 255, 255, 0.7);
        }

        .divider::before,
        .divider::after {
            content: "";
            flex: 1;
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
            margin: 0 10px;
        }
        /* Стили для уведомлений */
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 25px;
            border-radius: 5px;
            color: white;
            font-weight: bold;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            opacity: 0;
            transform: translateX(100%);
            transition: all 0.3s ease;
            max-width: 300px;
        }

        .notification.show {
            opacity: 1;
            transform: translateX(0);
        }

        .notification.error {
            background-color: #ff4444;
            border-left: 5px solid #cc0000;
        }

        .notification.success {
            background-color: #00C851;
            border-left: 5px solid #007E33;
        }
    </style>
    <script>
         function handleLogin(event) {
            event.preventDefault(); // Предотвращаем стандартную отправку формы

            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            // Создаем FormData объект для правильной отправки данных
            const formData = new FormData();
            formData.append('email', email);
            formData.append('password', password);

            fetch('php/login.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                    setTimeout(() => {
                        window.location.href = data.redirect || 'index.html';
                    }, 1500);
                } else {
                    showNotification(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Ошибка соединения с сервером', 'error');
            });
        }

        // Функция для показа уведомлений
        function showNotification(message, type) {
            const container = document.getElementById('notification-container');
            const notification = document.createElement('div');
            notification.className = `notification ${type}`;
            notification.textContent = message;

            container.appendChild(notification);

            // Показываем уведомление
            setTimeout(() => {
                notification.classList.add('show');
            }, 10);

            // Убираем уведомление через 5 секунд
            setTimeout(() => {
                notification.classList.remove('show');
                setTimeout(() => {
                    notification.remove();
                }, 300);
            }, 5000);
        }
        function validateLogin() {
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const button = document.getElementById('login-button');

            // Email validation
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            const isEmailValid = emailPattern.test(email);

            // Enable button only when all fields are valid
            button.disabled = !(isEmailValid && password.length >= 6);
        }
    </script>
</head>
<body>
    <div class="header">
        <nav>
            <ul>
                <li><a href="index.html">Главная</a></li>
                <li><a href="recipes.html">Рецепты</a></li>
                <li><a href="forum.html">Форум</a></li>
                <li><a href="login.html" class="active">Войти</a></li>
            </ul>
        </nav>
    </div>
    <div class="snow"></div>
    <div class="content">
        <div class="form">
            <section id="login-form">
    <h2>Вход в аккаунт</h2>
    <div id="user-count" style="text-align: center; margin-bottom: 20px; font-style: italic;"></div>
    
    <form id="loginForm" onsubmit="handleLogin(event)">
        <label for="email">Электронная почта:</label>
        <input type="email" id="email" name="email" required placeholder="Ваш email" oninput="validateLogin()">

        <label for="password">Пароль:</label>
        <input type="password" id="password" name="password" required placeholder="Не менее 6 символов" minlength="6" oninput="validateLogin()">

        <div class="login-links">
            <a href="register.html">Создать аккаунт</a>
        </div>

        <button type="submit" class="recipe-btn" id="login-button" disabled>Войти</button>
    </form>
    
    <canvas id="fireworksCanvas" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none;"></canvas>
</section>
        </div>
    </div>
    <div class="snowdrift"></div>
    <div class="snowdrift1" style="left: 0%; width: 100%; height: 90px;"></div>
    <audio autoplay loop>
        <source src="new-year.mp3" type="audio/mpeg">
        Ваш браузер не поддерживает аудио.
    </audio>
    <script src="script.js"></script>
    <div id="notification-container"></div>
</body>
</html>



### Для загрузки
lftp -u netforces,S6978a49 -p 2021 ftp.web-prj.ru
lcd /Users/tatiana/PycharmProjects/snow_overload
rm 404.html && put 404.html
rm feedback.html && put feedback.html
rm index.html && put index.html
rm login.html && put login.html
rm forum.html && put forum.html
rm recipe1.html && put recipe1.html
rm recipe2.html && put recipe2.html
rm recipe3.html && put recipe3.html
rm recipes.html && put recipes.html
rm register.html && put register.html
cd php
lcd php
rm login.php && put login.php
rm logout.php && put logout.php
rm register.php && put register.php


get 404.html
get feedback.html
get forum.html
get index.html
get login.html
get recipe1.html
get recipe2.html
get recipe3.html
get recipes.html
get register.html

rm 404.html
rm feedback.html
rm forum.html
rm index.html
rm login.html
rm recipe1.html
rm recipe2.html
rm recipe3.html
rm recipes.html
rm register.html