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
    k.user_id AS currentKeeperID,  -- Добавляем идентификатор владельца ключа
    ku.name AS currentName,  -- Имя владельца ключа
    ku.role AS currentKeeperRole,  -- Роль владельца ключа
    ARRAY_AGG(
        JSON_BUILD_OBJECT(
            'name', u.name,
            'role', u.role,
            'userID', u.id,
            'time', sk.time
        ) ORDER BY sk.time
    ) AS bookedTime
    FROM 
        \"keys\" k
    LEFT JOIN 
        (SELECT * FROM \"keyStatusOLD\" WHERE \"date\" = '$date') sk ON k.id = sk.\"idKey\"
    LEFT JOIN 
        users u ON sk.\"idUser\" = u.\"id\"
    LEFT JOIN 
        users ku ON k.user_id = ku.id
    WHERE 
        1 = 1
        " . ($room != NULL ? "AND k.room = '$room'" : "") . 
        ($building != NULL ? "AND k.building = '$building'" : "") .
    "GROUP BY
    k.id, k.room, k.building, sk.time, u.name, u.role, u.id, ku.name, ku.role, k.user_id"; 


// Выполнение запроса
$keysResult = pg_query($Link, $keysQuery);

// Проверяем, успешно ли выполнен запрос
if ($keysResult) {
    // Массив для хранения ключей и их связанных данных
    $keys = array();
    // Обработка результатов запроса
    while ($keyRow = pg_fetch_assoc($keysResult)) {
        $keyId = trim($keyRow['key_id']);
    
        // Формирование ключа, если его еще нет в массиве
        if (!array_key_exists($keyId, $keys)) {
            $keys[$keyId] = array(
                'id' => $keyId,
                'room' => $keyRow['room'],
                'building' => $keyRow['building'],
                'currentName' => $keyRow['currentname'],
                'currentKeeperRole' => $keyRow['currentkeeperrole'],
                'currentKeeperID' => $keyRow['currentkeeperid'],
                'bookedTime' => array()
            );
        }
    
        // Проверяем наличие данных о забронированном времени
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

    $keys = array_values($keys);

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

//     $infoUserKeeper = "SELECT 
//     k.user_id,
//     u.name AS currentName,
//     u.role AS currentKeeperRole
//     FROM 
//         keys k
//     LEFT JOIN 
//         usersold u ON k.user_id = u.id";

//     // Выполнение нового запроса
//    // Выполнение нового запроса
//     // Выполнение нового запроса
//     $result = pg_query($Link, $infoUserKeeper);

//     if (!$result) {
//         echo "Ошибка выполнения запроса";
//     } else {
//         // Обработка результатов
//         while ($row = pg_fetch_assoc($result)) {
//             $user_id = $row['user_id'];
//             $currentName = $row['currentname'];
//             $currentKeeperRole = $row['currentkeeperrole'];
//             echo "User ID: " . $user_id . ", Current Name: " . $currentName . ", Current Role: " . $currentKeeperRole . "<br>";
//         }
//     }



    
    echo json_encode($output);
    } else {
    echo "Ошибка при выполнении запроса: " . pg_last_error($Link);
    }

//     $query = "SELECT u.*, sk.* 
//     FROM \"users\" u 
//     LEFT JOIN \"statusKey\" sk 
//     ON u.id = sk.\"idUser\"
//     WHERE sk.\"idUser\" IS NOT NULL";

//     // Выполнение запроса
//     $result = pg_query($Link, $query);

// if (!$result) {
//     echo "Ошибка выполнения запроса";
// } else {
//     // Обработка результатов
//     while ($row = pg_fetch_assoc($result)) {
//         // Вывод данных для отладки
//         var_dump($row);
//     }
// }

}
?>