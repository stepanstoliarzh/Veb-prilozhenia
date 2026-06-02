<?php
function h($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function splitChars($text) {
    return preg_split('//u', $text, -1, PREG_SPLIT_NO_EMPTY);
}

function displaySymbol($symbol) {
    if ($symbol === ' ') return 'пробел';
    if ($symbol === "\n") return 'перевод строки';
    if ($symbol === "\r") return 'возврат каретки';
    if ($symbol === "\t") return 'табуляция';
    return $symbol;
}

function analyzeText($text) {
    $chars = splitChars($text);
    $lowerText = function_exists('mb_strtolower') ? mb_strtolower($text, 'UTF-8') : strtolower($text);
    $lowerChars = splitChars($lowerText);

    $symbolCounts = [];
    foreach ($lowerChars as $ch) {
        $symbolCounts[$ch] = ($symbolCounts[$ch] ?? 0) + 1;
    }
    ksort($symbolCounts, SORT_STRING);

    $letters = 0;
    $lower = 0;
    $upper = 0;
    $digits = 0;
    $punct = 0;

    foreach ($chars as $ch) {
        if (preg_match('/\p{L}/u', $ch)) {
            $letters++;
            if (preg_match('/\p{Lu}/u', $ch)) {
                $upper++;
            } else {
                $lower++;
            }
        } elseif (preg_match('/\p{N}/u', $ch)) {
            $digits++;
        } elseif (preg_match('/[\p{P}]/u', $ch)) {
            $punct++;
        }
    }

    preg_match_all('/[\p{L}\p{N}]+/u', $lowerText, $matches);
    $words = [];
    foreach ($matches[0] as $word) {
        $words[$word] = ($words[$word] ?? 0) + 1;
    }
    ksort($words, SORT_STRING);

    return [
        'chars_count' => count($chars),
        'letters' => $letters,
        'lower' => $lower,
        'upper' => $upper,
        'punct' => $punct,
        'digits' => $digits,
        'words_count' => count($matches[0]),
        'symbol_counts' => $symbolCounts,
        'word_counts' => $words,
    ];
}

$text = isset($_POST['data']) ? trim((string)$_POST['data']) : '';
$hasText = $text !== '';
$analysis = $hasText ? analyzeText($text) : null;
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Столярж Степан Алексеевич 241-353 ЛР А-8 Вариант 19 — Результат</title>
    <link rel="stylesheet" href="css/style.css?v=8">
</head>
<body>
<header>
    <div class="header-inner">
        <img src="img/logo.png" alt="Логотип университета" class="logo">
        <div class="header-text">
            <div class="line1">Столярж Степан Алексеевич · 241-353 · Лабораторная работа № А-8 · Вариант 19</div>
            <div class="line2">Основы работы со строковыми данными в PHP. Кодировка. Анализ текста</div>
        </div>
    </div>
</header>

<main>
    <section class="hero">
        <h1>Результат анализа текста</h1>
        <p class="lead">Программа анализирует текст в кодировке UTF-8 и корректно работает с русскими и английскими символами.</p>
    </section>

    <?php if ($hasText): ?>
        <section class="card">
            <h2>Исходный текст</h2>
            <div class="src-text"><?= nl2br(h($text)) ?></div>
        </section>

        <section class="card">
            <h2>Информация о тексте</h2>
            <div class="table-wrap">
                <table class="lab-table">
                    <tr><th>Параметр</th><th>Значение</th></tr>
                    <tr><td>Количество символов, включая пробелы</td><td><?= $analysis['chars_count'] ?></td></tr>
                    <tr><td>Количество букв</td><td><?= $analysis['letters'] ?></td></tr>
                    <tr><td>Количество строчных букв</td><td><?= $analysis['lower'] ?></td></tr>
                    <tr><td>Количество заглавных букв</td><td><?= $analysis['upper'] ?></td></tr>
                    <tr><td>Количество знаков препинания</td><td><?= $analysis['punct'] ?></td></tr>
                    <tr><td>Количество цифр</td><td><?= $analysis['digits'] ?></td></tr>
                    <tr><td>Количество слов</td><td><?= $analysis['words_count'] ?></td></tr>
                </table>
            </div>
        </section>

        <section class="card">
            <h2>Вхождения каждого символа</h2>
            <div class="table-wrap">
                <table class="lab-table">
                    <tr><th>Символ</th><th>Количество</th></tr>
                    <?php foreach ($analysis['symbol_counts'] as $symbol => $count): ?>
                        <tr><td><?= h(displaySymbol($symbol)) ?></td><td><?= $count ?></td></tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </section>

        <section class="card">
            <h2>Список слов по алфавиту</h2>
            <div class="table-wrap">
                <table class="lab-table">
                    <tr><th>Слово</th><th>Количество вхождений</th></tr>
                    <?php foreach ($analysis['word_counts'] as $word => $count): ?>
                        <tr><td><?= h($word) ?></td><td><?= $count ?></td></tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </section>
    <?php else: ?>
        <section class="card">
            <div class="message error">Нет текста для анализа</div>
        </section>
    <?php endif; ?>

    <a href="index.php" class="btn back-btn">Другой анализ</a>
</main>

<footer>
    <div class="footer-inner">
        <div>Столярж Степан Алексеевич · 241-353</div>
        <div class="badge">Лабораторная работа № А-8</div>
    </div>
</footer>
</body>
</html>
