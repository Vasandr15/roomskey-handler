<?php

include_once 'helperFunctions/headers.php';
    
function validatePage($page, $count)
{
    if ($page <= $count or ($page == 1 and $count == 0))
    {
        return true;
    }
    return false;
}


function route($method, $urlList, $requestData)
{
    global $Link;
    $page = $_GET["page"] ?? 1;
    $size = $_GET["size"] ?? 10;
    $date = $_GET["date"] ?? date("Y-m-d", time());
    $room = $_GET["room"] ?? null;
    $building = $_GET["building"] ?? null;


    if (!is_numeric($page))
    {
        setHTTPStatus('400', 'Invalid value for attribute page');
        return;
    }

    if (!is_numeric($size))
    {
        setHTTPStatus('400', 'Invalid value for attribute size');
        return;
    }

    if (($timestamp = strtotime($date)) === false) {
        setHTTPStatus('400', 'Invalid value for attribute date');
        return;
    }

    $keysQuery = "SELECT 
    k.id AS key_id,
    k.room AS room,
    k.building AS building,
    COALESCE(sk.time, null) AS time,
    u.name AS name,
    u.role AS role,
    u.id AS user,
    ARRAY_AGG(
        JSON_BUILD_OBJECT(
            'name', u.name,
            'role', u.role,
            'userID', u.id,
            'time', sk.time
        ) ORDER BY sk.time
    ) AS bookedTime
    FROM 
        \"key\" k
    LEFT JOIN 
        (SELECT * FROM \"statusKey\" WHERE \"date\" = '$date') sk ON k.id = sk.\"idKey\"
    LEFT JOIN 
        \"user\" u ON sk.\"idUser\" = u.\"id\"
    WHERE 
        1 = 1
        " . ($room != NULL ? "AND k.room = '$room'" : "") . 
        ($building != NULL ? "AND k.building = '$building'" : "") .
    "GROUP BY
    k.id, k.room, k.building, sk.time, u.name, u.role, u.id";






    $keysResult = pg_query($Link, $keysQuery);

    // Проверяем, успешно ли выполнен запрос
    if ($keysResult) {
    // Массив для хранения ключей и их связанных данных
    $keys = array();
    // Обработка результатов запроса
    // Обработка результатов запроса
    while ($keyRow = pg_fetch_assoc($keysResult)) {
        $keyId = trim($keyRow['key_id']);

    
        // Если ключа еще нет в массиве, добавляем его
        if (!isset($keys[$keyId])) {
            $keys[$keyId] = array(
                'id' => $keyId,
                'room' => $keyRow['room'],
                'building' => $keyRow['building'],
                'bookedTime' => array()
            );
        }
    
        // Проверяем, есть ли данные о забронированном времени
        if (isset($keyRow['time'])) {
            // Добавляем данные о забронированном времени для текущего ключа
            $keys[$keyId]['bookedTime'][] = array(
                'name' => trim($keyRow['name']),
                'role' => trim($keyRow['role']),
                'userID' => trim($keyRow['user']),
                'time' => trim($keyRow['time'])
            );
        }
    }

    $paginatedKeys = array_chunk($keys, $size, true);

    // Получаем данные только для текущей страницы
    $currentKeys = $paginatedKeys[$page - 1];

    $count = count($keys);
    $pageCount = ceil($count / $size);

    if (!validatePage($page, $count))
    {
        setHTTPStatus('400', 'Invalid value for attribute page');
        return;
    }

    $output = array(
        'keys' => $currentKeys,
        'pagination' => array(
            'size' => $size,
            'count' => $pageCount,
            'current' => $page
        )
    );


    
    echo json_encode($output);
    } else {
    echo "Ошибка при выполнении запроса: " . pg_last_error($Link);
    }

}
?>