<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Столярж Степан Алексеевич · 241-353 · ЛР А-7 · Вариант 19</title>
    <link rel="stylesheet" href="css/style.css?v=7">
    <script>
        // Функция добавляет новое поле для ввода элемента массива без перезагрузки страницы
        function addElement(){
            // Получаем таблицу, в которую добавляются поля
            const table = document.getElementById('elements');
            // Индекс нового элемента равен текущему количеству строк
            const index = table.rows.length;
            // Добавляем новую строку в таблицу
            const row = table.insertRow(index);

            const numberCell = row.insertCell(0);
            numberCell.className = 'element-number';
            // Выводим номер элемента слева от поля
            numberCell.textContent = 'Элемент ' + (index + 1);

            const inputCell = row.insertCell(1);
            inputCell.className = 'element-input';
            // Создаем новое поле ввода с уникальным именем element0, element1 и т.д.
            inputCell.innerHTML = '<input type="text" name="element' + index + '" placeholder="Введите число">';

            // Запоминаем количество элементов массива в скрытом поле
            document.getElementById('arrLength').value = table.rows.length;
        }
    </script>
</head>
<body>
<header>
    <div class="header-inner">
        <img class="logo" src="img/logo.png" alt="Логотип университета">
        <div class="header-text">
            <div class="line1">Столярж Степан Алексеевич · 241-353 · Лабораторная работа № А-7 · Вариант 19</div>
            <div class="line2">Основы использования массивов. Ввод данных и сортировка массивов</div>
        </div>
    </div>
</header>

<main>
    <section class="hero">
        <h1>Сортировка массива</h1>
        <p class="lead">Введите элементы массива, выберите алгоритм сортировки и нажмите кнопку «Сортировать массив».</p>
    </section>

    <section class="card lab-card">
        <form method="post" action="result.php" target="_blank" class="sort-form">
            <input type="hidden" id="arrLength" name="arrLength" value="1">

            <div class="form-row">
                <label for="algorithm">Алгоритм сортировки</label>
                <select id="algorithm" name="algorithm">
                    <option value="selection">Сортировка выбором</option>
                    <option value="bubble">Пузырьковый алгоритм</option>
                    <option value="shell">Алгоритм Шелла</option>
                    <option value="gnome">Алгоритм садового гнома</option>
                    <option value="quick">Быстрая сортировка</option>
                    <option value="php_sort">Встроенная функция PHP sort()</option>
                </select>
            </div>

            <h2>Элементы массива</h2>
            <div class="table-wrap compact-wrap">
                <table id="elements" class="input-table">
                    <tr>
                        <td class="element-number">Элемент 1</td>
                        <td class="element-input"><input type="text" name="element0" placeholder="Введите число"></td>
                    </tr>
                </table>
            </div>

            <div class="form-actions">
                <button type="button" class="btn secondary" onclick="addElement()">Добавить ещё один элемент</button>
                <button type="submit" class="btn primary">Сортировать массив</button>
            </div>
        </form>
    </section>
</main>

<footer>
    <div class="footer-inner">
        <div>Столярж Степан Алексеевич · 241-353</div>
        <div class="badge">Лабораторная работа № А-7</div>
    </div>
</footer>
</body>
</html>
