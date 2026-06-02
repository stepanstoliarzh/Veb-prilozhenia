<?php
// Подготавливаем значение для вывода в HTML
function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

// Генерируем случайное число от 0 до 100 с двумя знаками после запятой
function randomValue() {
    return round(mt_rand(0, 10000) / 100, 2);
}

// Приводим введенное число к нормальному виду: запятую меняем на точку
function normalizeNumber($value) {
    return (float) str_replace(',', '.', trim((string)$value));
}

// Возвращаем понятное название выбранной задачи
function taskName($task) {
    $names = [
        'triangle_area' => 'Площадь треугольника',
        'triangle_perimeter' => 'Периметр треугольника',
        'box_volume' => 'Объём параллелепипеда',
        'average' => 'Среднее арифметическое',
        'sum' => 'Сумма трёх чисел',
        'max' => 'Максимальное из трёх чисел',
        'product' => 'Произведение трёх чисел'
    ];
    return $names[$task] ?? 'Неизвестная задача';
}

// Решаем выбранную математическую задачу по значениям A, B и C
function solveTask($task, $a, $b, $c) {
    switch ($task) {
        case 'triangle_area':
            // Площадь треугольника считаем по формуле Герона
            $p = ($a + $b + $c) / 2;
            $square = $p * ($p - $a) * ($p - $b) * ($p - $c);
            if ($a <= 0 || $b <= 0 || $c <= 0 || $a + $b <= $c || $a + $c <= $b || $b + $c <= $a || $square < 0) {
                return 'error';
            }
            return round(sqrt($square), 2);
        case 'triangle_perimeter':
            return round($a + $b + $c, 2);
        case 'box_volume':
            return round($a * $b * $c, 2);
        case 'average':
            return round(($a + $b + $c) / 3, 2);
        case 'sum':
            return round($a + $b + $c, 2);
        case 'max':
            return round(max($a, $b, $c), 2);
        case 'product':
            return round($a * $b * $c, 2);
        default:
            return 'error';
    }
}

// Данные для шапки страницы
$studentName = 'Столярж Степан Алексеевич';
$studentGroup = '241-353';
$labTitle = 'Лабораторная работа № А-6 · Вариант 19';
$subtitle = 'Использование форм для передачи данных в программу PHP. Тест математических знаний';

// Значения по умолчанию для формы
$initFio = $_GET['F'] ?? $studentName;
$initGroup = $_GET['G'] ?? $studentGroup;
$initA = randomValue();
$initB = randomValue();
$initC = randomValue();

// Проверяем, была ли отправлена форма
$isProcessed = isset($_POST['A']);
$viewMode = $_POST['VIEW'] ?? 'browser';
$report = '';
$mailMessage = '';
$fio = $initFio;
$group = $initGroup;

if ($isProcessed) {
    // Получаем данные из формы
    $fio = trim($_POST['FIO'] ?? '');
    $group = trim($_POST['GROUP'] ?? '');
    $about = trim($_POST['ABOUT'] ?? '');
    $a = normalizeNumber($_POST['A'] ?? 0);
    $b = normalizeNumber($_POST['B'] ?? 0);
    $c = normalizeNumber($_POST['C'] ?? 0);
    $task = $_POST['TASK'] ?? 'average';
    $userAnswerRaw = trim($_POST['RESULT'] ?? '');
    $email = trim($_POST['MAIL'] ?? '');
    $sendMail = isset($_POST['SEND_MAIL']);

    // Определяем задачу и считаем правильный ответ программой
    $taskLabel = taskName($task);
    $programResult = solveTask($task, $a, $b, $c);

    // Сравниваем ответ пользователя с ответом программы
    if ($userAnswerRaw === '') {
        $userAnswerText = 'Задача самостоятельно решена не была';
        $status = 'Ошибка: тест не пройден';
        $statusClass = 'bad';
    } elseif ($programResult === 'error') {
        $userAnswerText = e($userAnswerRaw);
        $status = 'Ошибка: задача не может быть решена при таких входных данных';
        $statusClass = 'bad';
    } else {
        $userAnswer = normalizeNumber($userAnswerRaw);
        $userAnswerText = e($userAnswerRaw);
        $isCorrect = abs($userAnswer - (float)$programResult) < 0.001;
        $status = $isCorrect ? 'Тест пройден' : 'Ошибка: тест не пройден';
        $statusClass = $isCorrect ? 'good' : 'bad';
    }

    // Формируем отчет для вывода на странице
    $report .= '<div class="report-grid">';
    $report .= '<p><strong>ФИО:</strong> ' . e($fio) . '</p>';
    $report .= '<p><strong>Группа:</strong> ' . e($group) . '</p>';
    $report .= '<p><strong>Тип задачи:</strong> ' . e($taskLabel) . '</p>';
    $report .= '<p><strong>Входные данные:</strong> A = ' . e($a) . ', B = ' . e($b) . ', C = ' . e($c) . '</p>';
    $report .= '<p><strong>Ваш ответ:</strong> ' . $userAnswerText . '</p>';
    $report .= '<p><strong>Ответ программы:</strong> ' . ($programResult === 'error' ? 'Ошибка вычисления' : e($programResult)) . '</p>';
    if ($about !== '') {
        $report .= '<p class="wide"><strong>Немного о себе:</strong><br>' . nl2br(e($about)) . '</p>';
    }
    $report .= '<p class="wide status ' . $statusClass . '"><strong>' . e($status) . '</strong></p>';
    $report .= '</div>';

    // Если поставлен флажок, отправляем результат на e-mail
    if ($sendMail && $email !== '') {
        $plainReport = "ФИО: $fio\nГруппа: $group\nТип задачи: $taskLabel\nВходные данные: A=$a, B=$b, C=$c\nВаш ответ: $userAnswerRaw\nОтвет программы: $programResult\n$status";
        @mail($email, 'Результат теста по ЛР А-6', $plainReport, "Content-Type: text/plain; charset=utf-8\r\n");
        $mailMessage = 'Результаты теста были автоматически отправлены на e-mail: ' . e($email);
    }
}
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($studentName) ?> · <?= e($studentGroup) ?> · <?= e($labTitle) ?></title>
    <link rel="stylesheet" href="css/style.css?v=7">
</head>
<body class="<?= $viewMode === 'print' ? 'print-view' : 'browser-view' ?>">
<header>
    <div class="header-inner">
        <img src="img/logo.png" alt="Логотип университета" class="logo">
        <div class="header-text">
            <div class="line1"><?= e($studentName) ?> · <?= e($studentGroup) ?> · <?= e($labTitle) ?></div>
            <div class="line2"><?= e($subtitle) ?></div>
        </div>
    </div>
</header>

<main>
    <section class="hero">
        <h1>Тест математических знаний</h1>
        <p class="lead">Форма передаёт данные методом POST, программа решает выбранную задачу и сравнивает результат с ответом пользователя.</p>
    </section>

    <?php if (!$isProcessed): ?>
        <section class="card form-card">
            <form method="post" action="" class="test-form">
                <div class="form-row">
                    <label for="fio">ФИО</label>
                    <input type="text" id="fio" name="FIO" value="<?= e($initFio) ?>" required>
                </div>
                <div class="form-row">
                    <label for="group">Номер группы</label>
                    <input type="text" id="group" name="GROUP" value="<?= e($initGroup) ?>" required>
                </div>
                <div class="form-row">
                    <label for="a">Значение A</label>
                    <input type="text" id="a" name="A" value="<?= e($initA) ?>" required>
                </div>
                <div class="form-row">
                    <label for="b">Значение B</label>
                    <input type="text" id="b" name="B" value="<?= e($initB) ?>" required>
                </div>
                <div class="form-row">
                    <label for="c">Значение C</label>
                    <input type="text" id="c" name="C" value="<?= e($initC) ?>" required>
                </div>
                <div class="form-row">
                    <label for="task">Тип задачи</label>
                    <select id="task" name="TASK">
                        <option value="triangle_area">Площадь треугольника</option>
                        <option value="triangle_perimeter">Периметр треугольника</option>
                        <option value="box_volume">Объём параллелепипеда</option>
                        <option value="average">Среднее арифметическое</option>
                        <option value="sum">Сумма трёх чисел</option>
                        <option value="max">Максимальное из трёх чисел</option>
                        <option value="product">Произведение трёх чисел</option>
                    </select>
                </div>
                <div class="form-row">
                    <label for="result">Ваш ответ</label>
                    <input type="text" id="result" name="RESULT" placeholder="Введите предполагаемый результат">
                </div>
                <div class="form-row textarea-row">
                    <label for="about">Немного о себе</label>
                    <textarea id="about" name="ABOUT" rows="4" placeholder="Краткая информация о студенте"></textarea>
                </div>
                <div class="form-row">
                    <label for="view">Вид отчёта</label>
                    <select id="view" name="VIEW">
                        <option value="browser">Версия для просмотра в браузере</option>
                        <option value="print">Версия для печати</option>
                    </select>
                </div>
                <div class="form-row checkbox-row">
                    <span></span>
                    <label class="check-label"><input type="checkbox" name="SEND_MAIL" id="sendMail"> Отправить результат теста по e-mail</label>
                </div>
                <div class="form-row mail-row" id="mailBlock">
                    <label for="mail">Ваш e-mail</label>
                    <input type="email" id="mail" name="MAIL" placeholder="example@mail.ru">
                </div>
                <div class="form-row button-row">
                    <span></span>
                    <button type="submit">Проверить</button>
                </div>
            </form>
        </section>
    <?php else: ?>
        <section class="card result-card">
            <h2>Отчёт по результатам теста</h2>
            <?= $report ?>
            <?php if ($mailMessage !== ''): ?>
                <p class="message"><?= $mailMessage ?></p>
            <?php endif; ?>
            <a class="repeat-link" href="?F=<?= urlencode($fio) ?>&G=<?= urlencode($group) ?>">Повторить тест</a>
        </section>
    <?php endif; ?>
</main>

<footer>
    <div class="footer-inner">
        <span><?= e($studentName) ?> · <?= e($studentGroup) ?></span>
        <span class="badge">Лабораторная работа № А-6</span>
    </div>
</footer>

<script>
const checkbox = document.getElementById('sendMail');
const mailBlock = document.getElementById('mailBlock');
if (checkbox && mailBlock) {
    const toggleMail = () => { mailBlock.style.display = checkbox.checked ? 'grid' : 'none'; };
    checkbox.addEventListener('change', toggleMail);
    toggleMail();
}
</script>
</body>
</html>
