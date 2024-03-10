<?php

include_once 'helperFunctions/headers.php';
    
function validatePage($page, $count)
{
    if ($page <= $count || ($page == 1 && $count == 0))
    {
        return true;
    }
    return false;
}


function route($method, $urlList, $requestData)
{
    global $Link;
    switch($method)
    {
        case 'GET':

            $page = $_GET["page"] ?? 1;
            $size = $_GET["size"] ?? 10;

            if (!is_numeric($page) or $page < 1)
            {
                setHTTPStatus('400', 'Invalid value for attribute page');
                return;
            }
    
            if (!is_numeric($size) || $size < 1)
            {
                setHTTPStatus('400', 'Invalid value for attribute size');
                return;
            }

            $headers = apache_request_headers();
            $headers = array_change_key_case($headers, CASE_LOWER);

            $authHeader = isset($headers['authorization']) ? $headers['authorization'] : null;
            $token = substr($authHeader, 7);
            if (!$token){
                setHTTPStatus("401", "Unauthorized");
                break;
            }
            
            // Подключение к базе данных
            
            $userIDQuery = "SELECT userid FROM tokens WHERE token = '$token'";
            $userIDResult = pg_query($Link, $userIDQuery);

                    
            // Получение ID пользователя из результата запроса
            $userFromToken = pg_fetch_assoc($userIDResult);
            if (!is_null($userFromToken)) {
                $userID = $userFromToken['userid'];
            
                $keysQuery = "SELECT id,room,building FROM keys WHERE user_id = '$userID'";
                $keysResult = pg_query($Link, $keysQuery);
                

                $keys = array();
                if ($keysResult) {
       
                    while ($keyRow = pg_fetch_assoc($keysResult)) {
                        $keyId = trim($keyRow['id']);
                    
                        // Формирование ключа, если его еще нет в массиве
                        if (!array_key_exists($keyId, $keys)) {
                            $keys[$keyId] = array(
                                'id' => $keyId,
                                'room' => $keyRow['room'],
                                'building' => $keyRow['building']
                            );
                        }
                    }

                    $keys = array_values($keys);

                    $paginatedKeys = array_chunk($keys, $size, true);

                    // Получаем данные только для текущей страницы
                    $currentKeys = $paginatedKeys[$page - 1];

                    $count = count($keys);
                    $pageCount = ceil($count / $size);

                    if (!validatePage($page, $pageCount))
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
            else{
                setHTTPStatus("401", "Unauthorized");
            }

            break;

        break;
    default:
        setHTTPStatus("400", "You can only use GET");
        break;
    }

}
?>