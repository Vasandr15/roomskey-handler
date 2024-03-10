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
        case 'POST':

            $headers = apache_request_headers();
            $headers = array_change_key_case($headers, CASE_LOWER);

            $authHeader = isset($headers['authorization']) ? $headers['authorization'] : null;
            $token = substr($authHeader, 7);
            $token = substr($headers['Authorization'], 7);
            if (!$token){
                setHTTPStatus("401", "Unauthorized");
                break;
            }

            $id = $_GET["id"];
            if (!$id){
                setHTTPStatus('400', 'ID is required attribute');
                break;
            }

            if (!is_numeric($id) || $id < 1){
                setHTTPStatus('400', 'key ID incorrect format');
                break;
            }
                       
            $userIDQuery = "SELECT userid FROM tokens WHERE token = '$token'";
            $userIDResult = pg_query($Link, $userIDQuery);

            $userFromToken = pg_fetch_assoc($userIDResult);
            if (!is_null($userFromToken)) {
                $userID = $userFromToken['userid'];
            
                $keysQuery = "SELECT id FROM keys WHERE user_id = '$userID' AND keys.id = '$id'";
                $keysResult = pg_query($Link, $keysQuery);

                if (pg_num_rows($keysResult) === 0) {
                    setHTTPStatus('403', "You have not key with id = '$id'");
                    break;
                } else {
                    $nextKeeperId = $requestData->body->nextKeeperId;
                    if (!$nextKeeperId){
                        setHTTPStatus('400', 'nextKeeperId is required attribute');
                        break;
                    }

                    if (!is_numeric($nextKeeperId) || $nextKeeperId < 1){
                        setHTTPStatus('400', 'nextKeeperId is incorrect format');
                        break;
                    }

                    $KeeperIdQuery = "SELECT id FROM users WHERE id = '$nextKeeperId'";
                    $KeeperIdResult = pg_query($Link, $KeeperIdQuery);

                    if ($KeeperIdResult == False){
                        echo "Ошибка при выполнении запроса: " . pg_last_error($Link);
                    } else{
                        if (pg_num_rows($KeeperIdResult) === 0) {
                            setHTTPStatus('400', 'nextKeeperId is undefined');
                            break;
                        }
                    }



                    $updateKeeperKey = "UPDATE keys SET user_id = '$nextKeeperId' WHERE id = '$id'";
                    $updateResult = pg_query($Link, $updateKeeperKey);
                    if ($updateResult) {
                        setHTTPStatus('200', 'Data is update');
                        break;
                    } else {
                        setHTTPStatus('500', 'Server Error');
                        break;
                    }
                }
            }
                

        break;
    default:
        setHTTPStatus("400", "You can only use GET");
        break;
    }

}
?>