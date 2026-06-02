<?php
// Получаем из адресной строки выбранный тип верстки: TABLE или DIV.
$htmlType = $_GET['html_type'] ?? null;
// Получаем выбранную таблицу умножения: вся таблица или конкретное число от 2 до 9.
$content = $_GET['content'] ?? null;

// Проверяем тип верстки: если передано не DIV и не TABLE, оставляем значение по умолчанию.
if ($htmlType !== 'DIV') {
    $htmlType = ($htmlType === 'TABLE') ? 'TABLE' : null;
}

// Проверяем параметр content: разрешаем только числа от 2 до 9.
if ($content !== null && (!ctype_digit((string)$content) || (int)$content < 2 || (int)$content > 9)) {
    $content = null;
}

// Определяем текущий тип верстки. По умолчанию используется табличная верстка.
function currentHtmlType(): string
{
    return (isset($_GET['html_type']) && $_GET['html_type'] === 'DIV') ? 'DIV' : 'TABLE';
}

// Формирует ссылку с нужными GET-параметрами html_type и content.
function linkWithParams(?string $htmlType, ?int $content): string
{
    $params = [];
    if ($htmlType !== null) {
        $params['html_type'] = $htmlType;
    }
    if ($content !== null) {
        $params['content'] = $content;
    }
    return empty($params) ? 'index.php' : 'index.php?' . http_build_query($params);
}

// Превращает число от 2 до 9 в ссылку на соответствующую таблицу умножения.
function outNumAsLink(int $number): string
{
    if ($number >= 2 && $number <= 9) {
        return '<a class="num-link" href="index.php?content=' . $number . '">' . $number . '</a>';
    }
    return (string)$number;
}

// Формирует один столбец таблицы умножения на число $n.
function outRow(int $n): string
{
    $html = '';
    for ($i = 2; $i <= 9; $i++) {
        $html .= '<div class="multiply-line">' . outNumAsLink($n) . ' × ' . outNumAsLink($i) . ' = ' . outNumAsLink($n * $i) . '</div>';
    }
    return $html;
}

// Выводит таблицу умножения в табличной верстке.
function outTableForm(?int $content): void
{
    echo '<div class="table-wrap"><table class="multiply-table">';

    // Если конкретное число не выбрано, выводим всю таблицу от 2 до 9.
    if ($content === null) {
        echo '<tbody>';
        for ($row = 2; $row <= 9; $row++) {
            echo '<tr>';
            for ($col = 2; $col <= 9; $col++) {
                echo '<td>' . outNumAsLink($col) . ' × ' . outNumAsLink($row) . ' = ' . outNumAsLink($col * $row) . '</td>';
            }
            echo '</tr>';
        }
        echo '</tbody>';
    } else {
        // Если выбрано число, выводим только таблицу умножения на него.
        echo '<tbody>';
        for ($i = 2; $i <= 9; $i++) {
            echo '<tr><td class="single-cell">' . outNumAsLink($content) . ' × ' . outNumAsLink($i) . ' = ' . outNumAsLink($content * $i) . '</td></tr>';
        }
        echo '</tbody>';
    }

    echo '</table></div>';
}

// Выводит таблицу умножения в блочной верстке.
function outDivForm(?int $content): void
{
    // Если число не выбрано, выводим отдельную карточку для каждого числа от 2 до 9.
    if ($content === null) {
        echo '<div class="multiply-grid">';
        for ($n = 2; $n <= 9; $n++) {
            echo '<div class="multiply-card"><h2>На ' . $n . '</h2>' . outRow($n) . '</div>';
        }
        echo '</div>';
    } else {
        // Если число выбрано, выводим одну крупную карточку.
        echo '<div class="multiply-grid single-grid"><div class="multiply-card single-multiply"><h2>Таблица на ' . $content . '</h2>' . outRow($content) . '</div></div>';
    }
}

// Подготавливаем значения для заголовков, активных пунктов меню и подвала.
$activeType = currentHtmlType();
$contentNumber = $content === null ? null : (int)$content;
$contentTitle = $contentNumber === null ? 'Вся таблица умножения' : 'Таблица умножения на ' . $contentNumber;
$typeTitle = $activeType === 'TABLE' ? 'Табличная верстка' : 'Блочная верстка';
?>

<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Столярж Степан Алексеевич 241-353 ЛР А-5 Вариант 19</title>
    <link rel="stylesheet" href="css/style.css?v=5">
</head>
<body>
<header>
    <div class="header-inner">
        <img src="img/logo.png" alt="Логотип университета">
        <div class="header-text">
            <div class="line1">Столярж Степан Алексеевич · 241-353 · Лабораторная работа № А-5 · Вариант 19</div>
            <div class="line2">Динамическое формирование контента и меню. Таблица умножения.</div>
            <nav class="top-menu" aria-label="Выбор типа верстки">
                <a href="<?= linkWithParams('TABLE', $contentNumber) ?>" class="<?= isset($_GET['html_type']) && $_GET['html_type'] === 'TABLE' ? 'selected' : '' ?>">Табличная верстка</a>
                <a href="<?= linkWithParams('DIV', $contentNumber) ?>" class="<?= isset($_GET['html_type']) && $_GET['html_type'] === 'DIV' ? 'selected' : '' ?>">Блочная верстка</a>
            </nav>
        </div>
    </div>
</header>

<main>
    <section class="hero">
        <h1>Таблица умножения</h1>
        <p class="lead">Содержимое и тип отображения формируются динамически с помощью GET-параметров. Числа в примерах являются ссылками на соответствующую таблицу умножения.</p>
    </section>

    <div class="layout-card">
        <aside class="side-menu" aria-label="Выбор содержания таблицы">
            <h2>Меню</h2>
            <a href="<?= linkWithParams($activeType === 'DIV' ? 'DIV' : ($htmlType === 'TABLE' ? 'TABLE' : null), null) ?>" class="<?= $contentNumber === null ? 'selected' : '' ?>">Всё</a>
            <?php for ($i = 2; $i <= 9; $i++): ?>
                <a href="<?= linkWithParams($activeType === 'DIV' ? 'DIV' : ($htmlType === 'TABLE' ? 'TABLE' : null), $i) ?>" class="<?= $contentNumber === $i ? 'selected' : '' ?>"><?= $i ?></a>
            <?php endfor; ?>
        </aside>

        <section class="content-card">
            <div class="section-title">
                <h2><?= $contentTitle ?></h2>
                <span><?= $typeTitle ?></span>
            </div>
            <?php
            if ($activeType === 'DIV') {
                outDivForm($contentNumber);
            } else {
                outTableForm($contentNumber);
            }
            ?>
        </section>
    </div>
</main>

<footer>
    <div class="footer-inner">
        <div>Столярж Степан Алексеевич 241-353</div>
        <div class="badge"><?= $typeTitle ?> · <?= $contentTitle ?> · <?= date('d.m.Y H:i:s') ?></div>
    </div>
</footer>
</body>
</html>
