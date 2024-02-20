<?php
include_once 'helpUser.php';
function route($method, $urlList, $requestData)
{
    global $Link;
    switch ($method)
    {
        case 'POST':
            $password = $requestData->body->password;
            $login = $requestData->body->login;
            $fullName = $requestData->body->fullName;
            $user = $Link->query("SELECT id FROM users where login='$login'")->fetch_assoc();
            

            if (!validatePassword($password))
            {
                setHTTPStatus("400", "Password  is less then 6");
                return;
            }

            if (!validateEmail($login))
            {
                setHTTPStatus("400", "The Email field is not a valid e-mail address");
                return;
            }

            if (!validateFullName($fullName))
            {
                setHTTPStatus("400", "fullName is less then 1");
                return;
            }


            $password = hash("sha1", $requestData->body->password);
            

            $userInsertResult = $Link->query("INSERT INTO users(fullName,login, password) 
            VALUES('$fullName','$login','$password')");
            if (!$userInsertResult) {
                //400
                if ($Link->errno == 1062)
                {
                    setHTTPStatus("400", "user '$login' is taken");
                }               
            }
            else 
            {
                setHTTPStatus("201", "Login '$login' is succesfully created");
                
                $token = bin2hex(random_bytes(16));
                $tokenInsertResult = $Link->query("INSERT INTO tokens(value, userID) VALUES ('$token', '$Link->insert_id')");

                if (!$tokenInsertResult)
                {
                    //400
                    echo json_encode($Link->error);
                }
                else
                {
                    echo json_encode(['token' => $token]);
                }
            }


            break;
        default:
            setHTTPStatus("400", "You can only use POST to $urlList[1]");
            break;
    }

}