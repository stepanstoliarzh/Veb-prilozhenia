<?php
// Безопасный вывод текста на страницу
function h($value){
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

// Приводим число к нормальному виду: запятую заменяем на точку
function normalizeNumber($value){
    return str_replace(',', '.', trim((string)$value));
}

// Преобразуем массив в строку для красивого вывода
function arrayToString($arr){
    $parts = [];
    foreach($arr as $value){
        $parts[] = h($value);
    }
    return '[' . implode(', ', $parts) . ']';
}

// Выводим номер итерации и текущее состояние массива
function showIteration($number, $arr){
    echo '<tr><td>' . $number . '</td><td><div class="array-line">' . arrayToString($arr) . '</div></td></tr>';
}

// Возвращаем нормальное название алгоритма по его коду
function algorithmName($code){
    $names = [
        'selection' => 'Сортировка выбором',
        'bubble' => 'Пузырьковый алгоритм',
        'shell' => 'Алгоритм Шелла',
        'gnome' => 'Алгоритм садового гнома',
        'quick' => 'Быстрая сортировка',
        'php_sort' => 'Встроенная функция PHP sort()'
    ];
    return $names[$code] ?? 'Сортировка выбором';
}

// Сортировка выбором: ищем минимальный элемент и ставим его на нужное место
function selectionSort($arr){
    $iteration = 0;
    $n = count($arr);
    for($i = 0; $i < $n - 1; $i++){
        $min = $i;
        for($j = $i + 1; $j < $n; $j++){
            if($arr[$j] < $arr[$min]){
                $min = $j;
            }
        }
        if($min != $i){
            $temp = $arr[$i];
            $arr[$i] = $arr[$min];
            $arr[$min] = $temp;
        }
        $iteration++;
        showIteration($iteration, $arr);
    }
    return $iteration;
}

// Пузырьковая сортировка: сравниваем соседние элементы и меняем их местами
function bubbleSort($arr){
    $iteration = 0;
    $n = count($arr);
    for($i = 0; $i < $n - 1; $i++){
        for($j = 0; $j < $n - $i - 1; $j++){
            if($arr[$j] > $arr[$j + 1]){
                $temp = $arr[$j];
                $arr[$j] = $arr[$j + 1];
                $arr[$j + 1] = $temp;
            }
            $iteration++;
            showIteration($iteration, $arr);
        }
    }
    return $iteration;
}

// Сортировка Шелла: сортируем элементы с постепенно уменьшающимся шагом
function shellSort($arr){
    $iteration = 0;
    $n = count($arr);
    for($gap = intdiv($n, 2); $gap > 0; $gap = intdiv($gap, 2)){
        for($i = $gap; $i < $n; $i++){
            $temp = $arr[$i];
            $j = $i;
            while($j >= $gap && $arr[$j - $gap] > $temp){
                $arr[$j] = $arr[$j - $gap];
                $j -= $gap;
                $iteration++;
                showIteration($iteration, $arr);
            }
            $arr[$j] = $temp;
            $iteration++;
            showIteration($iteration, $arr);
        }
    }
    return $iteration;
}

// Сортировка гномом: двигаемся по массиву и меняем соседние элементы при нарушении порядка
function gnomeSort($arr){
    $iteration = 0;
    $i = 0;
    $n = count($arr);
    while($i < $n){
        if($i == 0 || $arr[$i] >= $arr[$i - 1]){
            $i++;
        } else {
            $temp = $arr[$i];
            $arr[$i] = $arr[$i - 1];
            $arr[$i - 1] = $temp;
            $i--;
        }
        $iteration++;
        showIteration($iteration, $arr);
    }
    return $iteration;
}

// Внутренняя часть быстрой сортировки, работает с участком массива
function quickSortInner(&$arr, $left, $right, &$iteration){
    if($left >= $right){
        return;
    }
    $i = $left;
    $j = $right;
    $pivot = $arr[intdiv($left + $right, 2)];

    while($i <= $j){
        while($arr[$i] < $pivot){ $i++; }
        while($arr[$j] > $pivot){ $j--; }
        if($i <= $j){
            $temp = $arr[$i];
            $arr[$i] = $arr[$j];
            $arr[$j] = $temp;
            $i++;
            $j--;
            $iteration++;
            showIteration($iteration, $arr);
        }
    }
    if($left < $j){ quickSortInner($arr, $left, $j, $iteration); }
    if($i < $right){ quickSortInner($arr, $i, $right, $iteration); }
}

// Запуск быстрой сортировки
function quickSort($arr){
    $iteration = 0;
    if(count($arr) > 1){
        quickSortInner($arr, 0, count($arr) - 1, $iteration);
    }
    return $iteration;
}

// Сортировка встроенной функцией PHP sort()
function phpSortAlgorithm($arr){
    $iteration = 0;
    showIteration(++$iteration, $arr);
    sort($arr, SORT_NUMERIC);
    showIteration(++$iteration, $arr);
    return $iteration;
}

// Получаем выбранный алгоритм и длину массива из формы
$algorithm = $_POST['algorithm'] ?? 'selection';
$length = isset($_POST['arrLength']) ? (int)$_POST['arrLength'] : 0;
$values = [];
$errors = [];
$rawValues = [];

// Собираем элементы массива из POST и проверяем, что они являются числами
for($i = 0; $i < $length; $i++){
    $key = 'element' . $i;
    $raw = $_POST[$key] ?? '';
    $rawValues[] = $raw;
    $normalized = normalizeNumber($raw);
    if($normalized === '' || !is_numeric($normalized)){
        $errors[] = 'Элемент №' . ($i + 1) . ' «' . h($raw) . '» не является числом';
    } else {
        $values[] = (float)$normalized;
    }
}
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Столярж Степан Алексеевич · 241-353 · Результат ЛР А-7</title>
    <link rel="stylesheet" href="css/style.css?v=7">
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
        <h1>Результат сортировки массива</h1>
        <p class="lead">Алгоритм: <strong><?php echo h(algorithmName($algorithm)); ?></strong></p>
    </section>

    <section class="card lab-card">
        <h2>Входные данные</h2>
        <p><strong>Исходный массив:</strong> <?php echo arrayToString($rawValues); ?></p>

        <?php if($length <= 0 || count($rawValues) === 0): ?>
            <div class="message error">Массив не задан, сортировка невозможна.</div>
        <?php elseif(count($errors) > 0): ?>
            <div class="message error">
                <strong>Ошибка валидации входных данных.</strong><br>
                <?php echo implode('<br>', $errors); ?><br>
                Сортировка не выполняется.
            </div>
        <?php else: ?>
            <div class="message success">Массив проверен: все элементы являются числами. Сортировка возможна.</div>

            <h2>Ход выполнения алгоритма</h2>
            <div class="table-wrap">
                <table class="sort-table">
                    <tr><th>Итерация</th><th>Текущее состояние массива</th></tr>
                    <?php
                    // Засекаем время начала сортировки
                    $timeStart = microtime(true);
                    // Выбираем нужный алгоритм сортировки
                    switch($algorithm){
                        case 'bubble':
                            $iterations = bubbleSort($values);
                            break;
                        case 'shell':
                            $iterations = shellSort($values);
                            break;
                        case 'gnome':
                            $iterations = gnomeSort($values);
                            break;
                        case 'quick':
                            $iterations = quickSort($values);
                            break;
                        case 'php_sort':
                            $iterations = phpSortAlgorithm($values);
                            break;
                        case 'selection':
                        default:
                            $iterations = selectionSort($values);
                            break;
                    }
                    // Считаем время выполнения сортировки
                    $timeEnd = microtime(true);
                    $time = round($timeEnd - $timeStart, 6);
                    ?>
                </table>
            </div>

            <div class="message success finish-message">
                Сортировка завершена, проведено <?php echo $iterations; ?> итераций. Сортировка заняла <?php echo $time; ?> секунд.
            </div>
        <?php endif; ?>

        <a class="btn-link" href="index.php">Вернуться к вводу массива</a>
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
