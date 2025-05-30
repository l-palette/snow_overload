import bcrypt

# Пароль для хеширования
password = 'alexandr'

# Генерация соли и хеширование пароля
hashed_password = bcrypt.hashpw(password.encode('utf-8'), bcrypt.gensalt())

print("Хешированный пароль:", hashed_password.decode('utf-8'))