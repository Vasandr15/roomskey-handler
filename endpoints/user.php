<?php
function route($method, $urlList, $requestData)
{
    if($method == "POST") {   
        switch ($urlList[1]) {
            case 'login':
                // echo "ento login!!!";
                global $Link;
                $phone = $requestData->body->phone;
                // $password = hash("sha1", $requestData->body->password);
                $password = $requestData->body->password;
                // echo $phone;
                // echo $password;

                $user = pg_fetch_assoc(pg_query($Link, "SELECT id FROM users Where phone='$phone' AND password='$password'"));
                if (!is_null($user)) {
                    // $token = bin2hex(random_bytes(16));
                    $userID = $user['id'];
                    // echo $userID;
                    $tokenInsertResult = pg_query($Link, "INSERT INTO tokens(iduser) VALUES ('$userID')");
                    $token = pg_fetch_assoc(pg_query($Link, "SELECT token FROM tokens WHERE iduser='$userID' ORDER BY createtime DESC LIMIT 1"));
                    if (!$tokenInsertResult) {
                        //400р
                        echo json_encode($Link->error);
                    } else {
                        echo json_encode($token['token']);
                    }
                } else {
                    setHTTPStatus("400", "input data incorrect");
                }
            break;

            case 'register':
                echo "ento register!!!";
                $password = $requestData->body->password;
                $login = $requestData->body->login;
                $name = $requestData->body->name;

                // $user = $Link->query("SELECT id FROM users where login='$login'")->fetch_assoc();
                $user = pg_fetch_assoc(pg_query($Link, "SELECT id FROM users where login='$login'"));
                

                if (!validatePassword($password)) {
                    setHTTPStatus("400", "Password  is less then 6");
                    return;
                }

                if (!validateEmail($login)) {
                    setHTTPStatus("400", "The Email field is not a valid e-mail address");
                    return;
                }

                if (!validateName($name)) {
                    setHTTPStatus("400", "name is less then 1");
                    return;
                }

                $password = hash("sha1", $requestData->body->password);

                // $userInsertResult = $Link->query("INSERT INTO users(fullName,login, password) 
                // VALUES('$fullName','$login','$password')");

                $userInsertResult = pg_query($Link, "INSERT INTO users(name, login, password) 
                VALUES('$name','$login','$password')");
                if (!$userInsertResult) {
                    //400
                    if ($Link->errno == 1062) {
                        setHTTPStatus("400", "user '$login' is taken");
                    }               
                } else {
                    setHTTPStatus("201", "Login '$login' is succesfully created");
                    
                    $token = bin2hex(random_bytes(16));
                    // $tokenInsertResult = $Link->query("INSERT INTO tokens(value, userID) VALUES ('$token', '$Link->insert_id')");

                    $tokenInsertResult = pg_query($Link, "INSERT INTO tokens(value, userID) VALUES ('$token', '$Link->insert_id')");
                    if (!$tokenInsertResult) {
                        //400
                        echo json_encode($Link->error);
                    } else {
                        echo json_encode(['token' => $token]);
                    }
                }
        }
        
    }
    else if ($method == "GET") {
        switch ($urlList[1]) {
            case 'profile':
                echo "ento GET profile";
                break;
            case '':
                echo "ento GET users list";
                break;
        }
    }
    else if ($method == "PATCH") {
        switch ($urlList[1]) {
            case '':
                echo "ento PATCH";
                break;
        }
    }
    else if ($method == "PUT") {
        if ($urlList[1] == "profile" && $urlList[2] == "update") {
            echo "ento PUT profile update";
        }
        // switch ($urlList[1]) {
        //     case 'profile':
        //         echo "ento PUT profile update";
        //         break;
        // }
    }
    else if ($method == "DELETE") {
        switch ($urlList[1]) {
            case 'logout':
                echo "ento DELETE logout";
                break;
        }
    } else {
        setHTTPStatus("400", "You can only use POST to $urlList[1]");
    }
}
?>