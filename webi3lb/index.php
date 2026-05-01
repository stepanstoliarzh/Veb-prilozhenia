<?php
$student = 'Столярж Степан Алексеевич';
$group = '241-353';
$lab = 'Лабораторная работа № А-3';
$variant = 19;
$pageTitle = $student.' · '.$group.' · '.$lab.' · Вариант '.$variant;

$store = '';
if (isset($_GET['store'])) {
    $store = preg_replace('/[^0-9]/', '', $_GET['store']);
}

$clicks = 0;
if (isset($_GET['clicks'])) {
    $clicks = (int)$_GET['clicks'];
    if ($clicks < 0) {
        $clicks = 0;
    }
}

if (isset($_GET['key'])) {
    $key = $_GET['key'];
    $clicks++;

    if ($key === 'reset') {
        $store = '';
    } elseif (preg_match('/^[0-9]$/', $key)) {
        $store .= $key;
    }
}

$safeStore = htmlspecialchars($store, ENT_QUOTES, 'UTF-8');
$safeClicks = htmlspecialchars((string)$clicks, ENT_QUOTES, 'UTF-8');
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8'); ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<header>
    <div class="header-inner">
        <img src="img/logo.png" alt="Логотип университета">
        <div class="header-text">
            <div class="line1"><?php echo htmlspecialchars($student, ENT_QUOTES, 'UTF-8'); ?> · <?php echo $group; ?> · <?php echo $lab; ?> · Вариант <?php echo $variant; ?></div>
            <div class="line2">Использование GET-параметров в ссылках. Виртуальная клавиатура</div>
        </div>
    </div>
</header>

<main>
    <section class="hero lab-card">
        <h1>Виртуальная клавиатура</h1>
        <p class="lead">Нажатые цифры передаются через GET-параметры и добавляются в строку результата. Кнопка сброса очищает строку, а в подвале отображается общее число нажатий.</p>

        <div class="result-window"><?php echo $safeStore; ?></div>

        <div class="keyboard" aria-label="Виртуальная клавиатура">
            <div class="key-row">
                <?php
                for ($i = 1; $i <= 5; $i++) {
                    echo '<a class="key" href="?key='.$i.'&amp;store='.$safeStore.'&amp;clicks='.$safeClicks.'">'.$i.'</a>';
                }
                ?>
            </div>
            <div class="key-row">
                <?php
                for ($i = 6; $i <= 9; $i++) {
                    echo '<a class="key" href="?key='.$i.'&amp;store='.$safeStore.'&amp;clicks='.$safeClicks.'">'.$i.'</a>';
                }
                echo '<a class="key" href="?key=0&amp;store='.$safeStore.'&amp;clicks='.$safeClicks.'">0</a>';
                ?>
            </div>
            <div class="key-row">
                <a class="key reset" href="?key=reset&amp;store=<?php echo $safeStore; ?>&amp;clicks=<?php echo $safeClicks; ?>">СБРОС</a>
            </div>
        </div>
    </section>
</main>

<footer>
    <div class="footer-inner">
        <div><?php echo htmlspecialchars($student, ENT_QUOTES, 'UTF-8'); ?> · <?php echo $group; ?></div>
        <div class="badge">Нажатий кнопок: <?php echo $safeClicks; ?></div>
    </div>
</footer>

</body>
</html>
