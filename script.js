const snowContainer = document.querySelector('.snow');

function createSnowflake() {
    const snowflake = document.createElement('div');
    snowflake.classList.add('snowflake');
    snowflake.style.left = Math.random() * window.innerWidth + 'px';
    snowflake.style.animationDuration = Math.random() * 3 + 2 + 's';
    snowflake.style.opacity = Math.random();
    snowContainer.appendChild(snowflake);

    setTimeout(() => {
        snowflake.remove();
    }, 5000);
}

setInterval(createSnowflake, 200);
const fadeElements = document.querySelectorAll('.fade-in');
fadeElements.forEach((element, index) => {
    element.style.animationDelay = `${index * 0.5}s`;
    element.classList.add('fade-in');
});



function createSnowflake() {
    const snowflake = document.createElement('div');
    snowflake.classList.add('snowflake');
    snowflake.style.left = Math.random() * window.innerWidth + 'px';
    snowflake.style.animationDuration = Math.random() * 3 + 2 + 's';
    snowflake.style.opacity = Math.random();
    snowContainer.appendChild(snowflake);

    setTimeout(() => {
        snowflake.remove();
    }, 5000);
}

function startHeavySnowfall() {
    // Создаем много снежинок за короткий промежуток времени
    for (let i = 0; i < 100; i++) { // 50 снежинок
        createSnowflake();
    }
}

// Устанавливаем интервал для создания снежинок
setInterval(createSnowflake, 200);

fadeElements.forEach((element, index) => {
    element.style.animationDelay = `${index * 0.5}s`;
    element.classList.add('fade-in');
});

document.getElementById('celebrate-button').addEventListener('click', function() {
    startHeavySnowfall(); // Запускаем сильный снегопад
});


function showFireworks(recipe, page) {
    createFireworks();
    
    // Задержка перед переходом на новую страницу
    setTimeout(() => {
        window.location.href = page;
    }, 200); // 2000 миллисекунд = 2 секунды
}

function createFireworks() {
    const canvas = document.getElementById('fireworksCanvas');
    const ctx = canvas.getContext('2d');
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;

    function drawFirework(x, y) {
        const particles = 1000;
        for (let i = 0; i < particles; i++) {
            const angle = Math.random() * 2 * Math.PI;
            const radius = Math.random() * 1000;
            const particleX = x + Math.cos(angle) * radius;
            const particleY = y + Math.sin(angle) * radius;
            ctx.fillStyle = `hsl(${Math.random() * 360}, 100%, 50%)`;
            ctx.beginPath();
            ctx.arc(particleX, particleY, 3, 0, Math.PI * 2);
            ctx.fill();
        }
    }

    const x = Math.random() * canvas.width;
    const y = Math.random() * canvas.height;

    drawFirework(x, y);
}

// Обработчик события для очистки канваса
canvas.addEventListener('click', () => {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
});