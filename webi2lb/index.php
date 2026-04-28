<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Столярж Степан Алексеевич 241-353 Лабораторная работа № А-2 Вариант 19</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    
<header>
    <div class="header-inner">
        <img src="img/logo.png" alt="Логотип университета">
        <div class="header-text">
            <div class="line1">Столярж Степан Алексеевич · 241-353 · Лабораторная работа № А-2 · Вариант 19</div>
            <div class="line2">Циклические алгоритмы, условия в алгоритмах, табулирование функции</div>
        </div>
    </div>
</header>

<main>
    <div class="hero">
        <h1>Табулирование функции f(x)</h1>
        <p> Результаты вычислений формируются PHP-кодом. Округление: до 3 знаков после запятой.</p>
    </div>

    <?php
    $student = 'Столярж Степан Алексеевич';
    $group = '241-353';
    $variant = 19;

    $start_value = -10;
    $encounting = 30;
    $step = 10;
    $min_value = -2000;
    $max_value = 2000;
    $type = 'E';

    $sum = 0;
    $count_numeric = 0;
    $minF = null;
    $maxF = null;

    function calcFunction($x) {
        if ($x <= 10) {
            return round($x * $x * ($x - 2) + 4, 3);
        } elseif ($x < 20) {
            return round(11 * $x - 55, 3);
        } else {
            $denominator = 100 - $x;
            if ($denominator == 0) {
                return 'error';
            }
            return round(((100 - $x) / $denominator) + ($x / 10) + 2, 3);
        }
    }

    function getTypeName($type) {
        switch ($type) {
            case 'A': return 'A — простая вёрстка текстом';
            case 'B': return 'B — маркированный список';
            case 'C': return 'C — нумерованный список';
            case 'D': return 'D — табличная вёрстка';
            case 'E': return 'E — блочная вёрстка';
            default: return 'Неизвестный тип вёрстки';
        }
    }

    echo '<section class="card">';

    if ($type === 'B') echo '<ul>';
    if ($type === 'C') echo '<ol>';
    if ($type === 'D') echo '<div class="table-wrap"><table><tr><th>№</th><th>x</th><th>f(x)</th></tr>';
    if ($type === 'E') echo '<div class="results-grid">';

    $x = $start_value;
    for ($i = 0; $i < $encounting; $i++, $x += $step) {
        $f = calcFunction($x);
        $line = 'f(' . $x . ')=' . $f;

        switch ($type) {
            case 'A':
                echo $line . '<br>';
                break;
            case 'B':
            case 'C':
                echo '<li>' . $line . '</li>';
                break;
            case 'D':
                echo '<tr><td>' . ($i + 1) . '</td><td>' . $x . '</td><td>' . $f . '</td></tr>';
                break;
            case 'E':
                echo '<div class="result-box">' . $line . '</div>';
                break;
            default:
                echo $line . '<br>';
        }

        if ($f !== 'error') {
            $sum += $f;
            $count_numeric++;

            if ($minF === null || $f < $minF) $minF = $f;
            if ($maxF === null || $f > $maxF) $maxF = $f;

            if ($f >= $max_value || $f < $min_value) {
                break;
            }
        }
    }

    if ($type === 'B') echo '</ul>';
    if ($type === 'C') echo '</ol>';
    if ($type === 'D') echo '</table></div>';
    if ($type === 'E') echo '</div>';

    echo '</section>';
    ?>

    <section class="card">
        <h2>Статистика по числовым значениям функции</h2>
        <?php if ($count_numeric > 0): ?>
            <p>Сумма: <?php echo round($sum, 3); ?></p>
            <p>Среднее арифметическое: <?php echo round($sum / $count_numeric, 3); ?></p>
            <p>Минимум: <?php echo $minF; ?></p>
            <p>Максимум: <?php echo $maxF; ?></p>
            <p>Количество числовых значений: <?php echo $count_numeric; ?></p>
        <?php else: ?>
            <p>Все вычисленные значения равны error.</p>
        <?php endif; ?>
    </section>
</main>

<footer>
    <div class="footer-inner">
        <div><?php echo $student . ' ' . $group; ?></div>
        <div><?php echo getTypeName($type); ?></div>
    </div>
</footer>
</body>
</html>
