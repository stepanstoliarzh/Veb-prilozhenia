<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Столярж Степан Алексеевич 241-353 ЛР А-8 Вариант 19</title>
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
        <h1>Введите текст для анализа</h1>
        <p class="lead">Введите русский или английский текст. После отправки программа подсчитает символы, буквы, цифры, слова и частоту повторений.</p>
    </section>

    <section class="card form-card">
        <form method="post" action="result.php" class="analysis-form">
            <label for="data">Текст для анализа</label>
            <textarea id="data" name="data" rows="10" placeholder="Например: Привет, мир! Hello, world! 123"></textarea>
            <button type="submit" class="btn">Анализировать</button>
        </form>
    </section>
</main>

<footer>
    <div class="footer-inner">
        <div>Столярж Степан Алексеевич · 241-353</div>
        <div class="badge">Лабораторная работа № А-8</div>
    </div>
</footer>
</body>
</html>
