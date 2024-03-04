<?php
function route($method, $urlList, $requestData)
{
    include_once 'helperFunctions/headers.php';
    date_default_timezone_set('Asia/Tomsk');
    global $Link;
    $page = $_GET["page"] ?? 1;
    $size = $_GET["size"] ?? 10;
    $date = $_GET["date"] ?? date("Y-m-d", time());



    $keysQuery = "SELECT 
    k.id AS key_id,
    k.room AS room,
    k.building AS building,
    sk.time AS time,
    u.name AS name,
    u.role AS role,
    u.id AS user
    FROM 
        \"key\" k
    JOIN 
        \"statusKey\" sk ON k.id = sk.\"idKey\"
    JOIN 
        \"user\" u ON sk.\"idUser\" = u.\"id\"";

    $keysResult = pg_query($Link, $keysQuery);

    // Проверяем, успешно ли выполнен запрос
    if ($keysResult) {
    // Массив для хранения ключей и их связанных данных
    $keys = array();

    // Обработка результатов запроса
    while ($keyRow = pg_fetch_assoc($keysResult)) {
        $keyId = trim($keyRow['key_id']); // Используйте trim() здесь
    
        // Если ключа еще нет в массиве, добавляем его
        if (!isset($keys[$keyId])) {
            $keys[$keyId] = array(
                'id' => $keyId,
                'room' => $keyRow['room'],
                'building' => $keyRow['building'],
                'bookedTime' => array()
            );
        }
    
        // Добавляем данные о забронированном времени для текущего ключа
        $keys[$keyId]['bookedTime'][] = array(
            'name' => trim($keyRow['name']), // Используйте trim() здесь
            'role' => trim($keyRow['role']), // Используйте trim() здесь
            'userID' => trim($keyRow['user']),
            'time' => trim($keyRow['time'])
        );
    }

    // Формируем структуру данных для вывода
    $output = array(
    'keys' => array_values($keys), // Преобразуем ключи в индексированный массив
    'pagination' => array(
    'size' => 0, // Размер пагинации, если это требуется
    'count' => count($keys), // Количество записей
    'current' => 0 // Текущая страница пагинации, если это требуется
    )
    );

    // Преобразуем массив в JSON и выводим его
    echo json_encode($output);
    } else {
    // Если произошла ошибка при выполнении запроса
    echo "Ошибка при выполнении запроса: " . pg_last_error($Link);
    }

}
?>