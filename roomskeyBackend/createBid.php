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

function checkTime($curTime){
    switch($curTime)
    {
        case "1":
            if ('08:45' > date('H:i')){
                return true;
            }
            return false;
            break;
        case "2":
            if ('10:35' > date('H:i')){
                return true;
            }
            return false;
            break;
        case "3":
            if ('12:25' > date('H:i')){
                return true;
            }
            return false;
            break;
        case "4":
            if ('14:45' > date('H:i')){
                return true;
            }
            return false;
            break;
        case "5":
            if ('16:35' > date('H:i')){
                return true;
            }
            return false;
            break;
        case "6":
            if ('18:25' > date('H:i')){
                return true;
            }
            return false;
            break;
        case "7":
            if ('20:15' > date('H:i')){
                return true;
            }
            return false;
            break;
        default:
            return false;
            break;
    }
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

            $checkExistKeyQuery = "SELECT id, room, building FROM keys WHERE id = '$id'";
            $checkExistKeyResult = pg_query($Link, $checkExistKeyQuery);
            if (pg_num_rows($checkExistKeyResult) === 0) {
                setHTTPStatus('400', "Key with id = '$id' is not exist");
                break;
            }

            $keyFromId = pg_fetch_assoc($checkExistKeyResult);
            $room = $keyFromId['room'];
            $building = $keyFromId['building'];


                       
            $userDataQuery = "SELECT u.id, u.role, u.name FROM tokens t 
                  JOIN users u ON u.id = t.userid 
                  WHERE t.token = '$token'";
            $userIDResult = pg_query($Link, $userDataQuery);

            $userFromToken = pg_fetch_assoc($userIDResult);
            if (!is_null($userFromToken)) {
                $userID = $userFromToken['id'];
                $userRole = $userFromToken['role'];
                $userName = $userFromToken['name'];

                $date = $requestData->body->date;
                $time = $requestData->body->time;


                if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
                    setHTTPStatus('400', 'Date is incorrect format');
                    break;
                }

                if ($date < date('Y-m-d')){
                    setHTTPStatus('400', 'The date must not be past');
                    break;
                }
        
                if (!is_numeric($time) || $time < 1 || $time > 7){
                    setHTTPStatus('400', 'Time is incorrect format');
                    break;
                }

                if ($date == date('Y-m-d') && (checkTime($time) == False)){
                    setHTTPStatus('400', 'The date must not be past');
                    break;
                }

            
                // Исправленный запрос
                $keysQuery = "SELECT iduser FROM keystatus WHERE date = '$date' AND time = '$time' AND status = 'accepted'";
                $keysResult = pg_query($Link, $keysQuery);

                if (pg_num_rows($keysResult) === 0) {

                    $insertQuery = "INSERT INTO keystatus (idkey,date,time,status,repeatable, iduser) VALUES ('$id', '$date', '$time', 'awaiting confirmation', False, '$userID')";
                    $insertResult = pg_query($Link, $insertQuery);
                    if ($insertResult) {
                        setHTTPStatus('201', 'The bid created successfully');
                        break;
                    } else {
                        setHTTPStatus('500', 'Server Error');
                        break;
                    }
                } else{
                    $arrayKeysResult = pg_fetch_assoc($keysResult);
                    $acceptedId = $arrayKeysResult['iduser'];

                    $roleAcceptedQuerry = "SELECT role FROM users WHERE id = '$acceptedId'";
                    $roleAcceptedResult = pg_query($Link, $roleAcceptedQuerry);

                    $roleResult = pg_fetch_assoc($roleAcceptedResult);
                    $resultRole = $roleResult['role'];

                    if ($resultRole == 'student' || $userRole == 'teacher' || $userRole == 'dean' || $userRole == 'administrator'){

                        $insertQuery = "INSERT INTO keystatus (idkey,date,time,status,repeatable, iduser) VALUES ('$id', '$date', '$time', 'awaiting confirmation', False, '$userID')";
                        $insertResult = pg_query($Link, $insertQuery);
                        if ($insertResult) {
                            setHTTPStatus('201', 'The bid created successfully');
                            break;
                        } else {
                            setHTTPStatus('500', 'Server Error');
                            break;
                        }
                    }else {
                        setHTTPStatus('400', "This time is occupied by the teacher");
                        break;
                    }
                } 
            } else{
                setHTTPStatus("401", "Unauthorized");
                break;
            }
                

        break;
    default:
        setHTTPStatus("400", "You can only use POST");
        break;
    }

}
?>