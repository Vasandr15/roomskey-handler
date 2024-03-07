<?php
function route($method, $urlList, $requestData) {
    include_once 'endpoints/helperFunctions/helpUser.php';
    global $Link;
    if($method == "POST") {   
        switch ($urlList[1]) {
            case 'login':
                // echo "ento login!!!";
                $phone = $requestData->body->phone;
                $password = hash("sha1", $requestData->body->password);

                $user = pg_fetch_assoc(pg_query($Link, "SELECT id FROM users Where phone='$phone' AND password='$password'"));
                if (!is_null($user)) {
                    $userID = $user['id'];
                    $tokenInsertResult = pg_query($Link, "INSERT INTO tokens(iduser) VALUES ('$userID')");

                    $token = pg_fetch_assoc(pg_query($Link, "SELECT token FROM tokens WHERE iduser='$userID' ORDER BY createtime DESC LIMIT 1"));
                    if (!$tokenInsertResult) {
                        //400р не работает))
                        echo json_encode(pg_last_error());
                    } else {
                        echo json_encode($token['token']);
                    }
                } else {
                    setHTTPStatus("400", "input data incorrect");
                }
            break;

            case 'register':
                // echo "ento register!!!";
                $name = $requestData->body->name;
                $password = $requestData->body->password;
                $phone = $requestData->body->phone;
                $role = $requestData->body->role;
                $email = $requestData->body->email;
                
                if (!validateName($name)) {
                    setHTTPStatus("400", "Name is less then 1");
                    return;
                }

                if (!validatePassword($password)) {
                    setHTTPStatus("400", "Password  is less then 6");
                    return;
                }

                if (!validatePhone($phone)) {
                    setHTTPStatus("400", "Phone is contains invalid characters");
                    return;
                }

                // Сделать валидацию почты на фронте
                if ($email) {
                    if (!validateEmail($email)) {
                        setHTTPStatus("400", "The Email field is not a valid e-mail address");
                        return;
                    }
                }

                $password = hash("sha1", $password);
                
                if (!$email) {
                    $userInsertResult = pg_query($Link, "INSERT INTO users(name, password, phone, role) VALUES ('$name', '$password', '$phone', '$role')");
                } else {
                    $userInsertResult = pg_query($Link, "INSERT INTO users(name, email, password, phone, role) VALUES ('$name', '$email', '$password', '$phone', '$role')");
                }
                
                $user = pg_fetch_assoc(pg_query($Link, "SELECT id FROM users Where phone='$phone'"));
                $userID = $user['id'];

                if (!$userInsertResult) {
                    //400 не работает
                    if (pg_last_error() == 1062) { 
                        setHTTPStatus("400", "user '$name' is taken");
                    }               
                } else {
                    

                    $tokenInsertResult = pg_query($Link, "INSERT INTO tokens(iduser) VALUES ('$userID')");
                    
                    $token = pg_fetch_assoc(pg_query($Link, "SELECT token FROM tokens WHERE iduser='$userID' ORDER BY createtime DESC LIMIT 1"));

                    if (!$tokenInsertResult) {
                        //400 не работает
                        echo json_encode(pg_last_error());
                    } else {
                        setHTTPStatus("201", "Login '$name' is succesfully created. Token = " . $token['token']);
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