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

function checkTime($time){
    switch(time)
    {
        case "1":
            if ('8:45' > date('H:i:s')){
                return true;
            }
            return false;
            break;
        case "2":
            if ('10:35' > date('H:i:s')){
                return true;
            }
            return false;
            break;
        case "3":
            if ('12:25' > date('H:i:s')){
                return true;
            }
            return false;
            break;
        case "4":
            if ('14:45' > date('H:i:s')){
                return true;
            }
            return false;
            break;
        case "5":
            if ('16:35' > date('H:i:s')){
                return true;
            }
            return false;
            break;
        case "6":
            if ('18:25' > date('H:i:s')){
                return true;
            }
            return false;
            break;
        case "7":
            if ('20:15' > date('H:i:s')){
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

            $headers = getallheaders();
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

            $checkExistKeyQuery = "SELECT id, room, building FROM keys WHERE id = '$id'";
            $checkExistKeyResult = pg_query($Link, $checkExistKeyQuery);
            if (pg_num_rows($checkExistKeyResult) === 0) {
                setHTTPStatus('400', "Key with id = '$id' is not exist");
                break;
            }

            $keyFromId = pg_fetch_assoc($checkExistKeyResult);
            $room = $keyFromId['room'];
            $building = $keyFromId['building'];


                       
            $userIDQuery = "SELECT userid, role, name FROM tokens WHERE token = '$token'";
            $userIDResult = pg_query($Link, $userIDQuery);

            $userFromToken = pg_fetch_assoc($userIDResult);
            if (!is_null($userFromToken)) {
                $userID = $userFromToken['userid'];
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
        
                if (!is_numeric($time) || $time < 1){
                    setHTTPStatus('400', 'Time is incorrect format');
                    break;
                }

                if ($date == date('Y-m-d') && (heckTime($time) == False)){
                    setHTTPStatus('400', 'The date must not be past');
                    break;
                }




            
                $keysQuery = "SELECT id FROM bids WHERE (userRole = administrator OR userRole = dean OR userRole = teacher) AND date = '$date' AND time = '$time'";
                $keysResult = pg_query($Link, $keysQuery);

                if (pg_num_rows($keysResult) === 0 || $userRole == 'teacher' || $userRole == 'dean' || $userRole == 'administrator') {


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



                    $insertQuery = "INSERT INTO bids (keyId, room, building,date,time,status,repeatable, userId, userName, userRole) VALUES ('$id', '$room', '$building', '$date', '$time', 'awaiting confirmation', 0, '$userID', '$userName', '$userRole')";
                    $insertResult = pg_query($Link, $insertQuery);
                    if ($updateResult) {
                        setHTTPStatus('200', 'Data is update');
                        break;
                    } else {
                        setHTTPStatus('500', 'Server Error');
                        break;
                    }
                } else {
                    setHTTPStatus('400', "This time is occupied by the teacher");
                    break;
                    
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