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
                    $tokenInsertResult = pg_query($Link, "INSERT INTO tokens(userid) VALUES ('$userID')");

                    $token = pg_fetch_assoc(pg_query($Link, "SELECT token FROM tokens WHERE userid='$userID' ORDER BY createtime DESC LIMIT 1"));
                    if (!$tokenInsertResult) {
                        //400р не работает))
                        echo json_encode(pg_last_error());
                    } else {
                        echo json_encode($token['token']);
                    }
                } else {
                    setHTTPStatus("400", "Input data incorrect");
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
                    

                    $tokenInsertResult = pg_query($Link, "INSERT INTO tokens(userid) VALUES ('$userID')");
                    
                    $token = pg_fetch_assoc(pg_query($Link, "SELECT token FROM tokens WHERE userid='$userID' ORDER BY createtime DESC LIMIT 1"));

                    if (!$tokenInsertResult) {
                        //400 не работает
                        echo json_encode(pg_last_error());
                    } else {
                        setHTTPStatus("201", "Login '$name' is succesfully created. Token = " . $token['token']);
                    }
                }
            break;
        }
        
    }
    else if ($method == "GET") {
        switch ($urlList[1]) {
            case 'profile':
                // echo "ento GET profile";
                $token = substr(getallheaders()["Authorization"], 7);
                
                $userId = pg_fetch_assoc(pg_query($Link, "SELECT userid FROM tokens Where token='$token'"))['userid'];
                $user = pg_fetch_assoc(pg_query($Link, "SELECT name, email, phone, role, avatarLink FROM users Where id='$userId'"));
                
                echo json_encode($user);
            break;
            case '':
                // echo "ento GET users list";
                $name = $_GET["name"];
                $roles = $_GET["roles"];
                $page = $_GET["page"];
                $size = $_GET["size"];
                $sort = $_GET["sort"];

                $token = substr(getallheaders()["Authorization"], 7);
                if ((is_null($name) AND is_null($roles) AND is_null($page) AND is_null($size) AND is_null($sort))) {
                    echo "114";
                    $page = 1;
                    $size = 10;

                    $usersInDb = pg_query($Link, "SELECT id FROM users ORDER BY id");
                    $users = [];
                    while ($row = pg_fetch_assoc($usersInDb)) {
                        $userId = $row['id'];
                        $userInDb = pg_fetch_assoc(pg_query($Link, "SELECT name, email, phone, role, avatarLink FROM users Where id='$userId'"));;
                        array_push($users, $userInDb);
                    }

                    $pagination = [
                        "size" => $size,
                        "count" => ceil(count($users)/$size),
                        "current" => $page
                    ];

                    if ($page < 0 OR $page > ceil(count($users)/$size)) {
                        setHTTPStatus("400", "Incorrect page");
                    }
                    else {
                        $responseBody = [
                            // "users" => array_slice($users, ($page-1)*$size, $size),
                            "users" => $users,
                            "pagination" => $pagination
                        ];
                        echo json_encode($responseBody);
                    }

                } else {
                    
                    if ($size === "" OR is_null($size)) {
                        $size = 10;
                    }

                    if ($page === "" OR is_null($page)) {
                        $page = 1;
                    }

                    if ($sort !== "") {
                        if ($sort[6] === "D") {
                            $sort = "DESC";
                        } else {
                            $sort = "ASC";
                        }
                    }

                    if ($name === "") {
                        $name = null;
                    }

                    if ($roles === "") {
                        $roles = null;
                    }

                    if (!is_null($name) AND !is_null($roles)) {
                        echo "167";
                        $usersInDb = pg_query($Link, "SELECT id FROM users WHERE name LIKE '%$name%' and role='$roles' ORDER BY id $sort");
                        $users = [];
                        while ($row = pg_fetch_assoc($usersInDb)) {
                            $userId = $row['id'];
                            $userInDb = pg_fetch_assoc(pg_query($Link, "SELECT name, email, phone, role, avatarLink FROM users Where id='$userId'"));;
                            array_push($users, $userInDb);
                        }

                        $pagination = [
                            "size" => $size,
                            "count" => ceil(count($users)/$size),
                            "current" => $page
                        ];

                        if ($page < 0 OR $page > ceil(count($users)/$size)) {
                            setHTTPStatus("400", "Incorrect page");
                        } else {
                            $responseBody = [
                                "users" => array_slice($users, ($page-1)*$size, $size),
                                "pagination" => $pagination
                            ];
                            echo json_encode($responseBody);
                        }
                    }
                    else if (is_null($name) AND !is_null($roles)) {
                        echo "193";
                        $usersInDb = pg_query($Link, "SELECT id FROM users WHERE role='$roles' ORDER BY id $sort");
                        $users = [];
                        while ($row = pg_fetch_assoc($usersInDb)) {
                            $userId = $row['id'];
                            $userInDb = pg_fetch_assoc(pg_query($Link, "SELECT name, email, phone, role, avatarLink FROM users Where id='$userId'"));;
                            array_push($users, $userInDb);
                        }

                        $pagination = [
                            "size" => $size,
                            "count" => ceil(count($users)/$size),
                            "current" => $page
                        ];

                        if ($page < 0 OR $page > ceil(count($users)/$size)) {
                            setHTTPStatus("400", "Incorrect page");
                        } else {
                            $responseBody = [
                                "users" => array_slice($users, ($page-1)*$size, $size),
                                "pagination" => $pagination
                            ];
                            echo json_encode($responseBody);
                        }
                    }
                    else if (!is_null($name) AND is_null($roles)) {
                        echo "219";
                        $usersInDb = pg_query($Link, "SELECT id FROM users WHERE name LIKE '%$name%' ORDER BY id $sort");
                        $users = [];
                        while ($row = pg_fetch_assoc($usersInDb)) {
                            $userId = $row['id'];
                            $userInDb = pg_fetch_assoc(pg_query($Link, "SELECT name, email, phone, role, avatarLink FROM users Where id='$userId'"));;
                            array_push($users, $userInDb);
                        }

                        $pagination = [
                            "size" => $size,
                            "count" => ceil(count($users)/$size),
                            "current" => $page
                        ];

                        if ($page < 0 OR $page > ceil(count($users)/$size)) {
                            setHTTPStatus("400", "Incorrect page");
                        } else {
                            $responseBody = [
                                "users" => array_slice($users, ($page-1)*$size, $size),
                                "pagination" => $pagination
                            ];
                            echo json_encode($responseBody);
                        }
                    } else {
                        echo "244";
                        $usersInDb = pg_query($Link, "SELECT id FROM users ORDER BY id $sort");
                        $users = [];
                        while ($row = pg_fetch_assoc($usersInDb)) {
                            $userId = $row['id'];
                            $userInDb = pg_fetch_assoc(pg_query($Link, "SELECT name, email, phone, role, avatarLink FROM users Where id='$userId'"));;
                            array_push($users, $userInDb);
                        }

                        $pagination = [
                            "size" => $size,
                            "count" => ceil(count($users)/$size),
                            "current" => $page
                        ];

                        if ($page < 0 OR $page > ceil(count($users)/$size)) {
                            setHTTPStatus("400", "Incorrect page");
                        } else {
                            $responseBody = [
                                "users" => array_slice($users, ($page-1)*$size, $size),
                                "pagination" => $pagination
                            ];
                            echo json_encode($responseBody);
                        }
                    }
                    
                }

            break;
        }
    }
    else if ($method == "PATCH") {
        switch ($urlList[1]) {
            case '':
                // echo "ento PATCH";
                $token = substr(getallheaders()["Authorization"], 7);
                
                $userId = $_GET["id"];
                $role = $requestData->body->role;

                $roleUpdateRoleResult = pg_query($Link, "UPDATE users SET role = '$role' Where id='$userId'");
                
                if (!$roleUpdateRoleResult) {
                    setHTTPStatus("400", "input data incorrect");
                } else {
                    setHTTPStatus("200", "The role has been updated");
                }

            break;
        }
    }
    else if ($method == "PUT") {
        if ($urlList[1] == "profile" && $urlList[2] == "update") {
            // echo "ento PUT profile update";

            $name = $requestData->body->name;
            $email = $requestData->body->email;
            $phone = $requestData->body->phone;
            $avatarLink = $requestData->body->avatarLink;
           
            $token = substr(getallheaders()["Authorization"], 7);

            $userId = pg_fetch_assoc(pg_query($Link, "SELECT userid FROM tokens Where token='$token'"))['userid'];

            $userUpdateInfoResult = pg_query($Link, "UPDATE users SET name = '$name', email = '$email', phone = '$phone', avatarlink = '$avatarLink' Where id='$userId'");

            if (!$userUpdateInfoResult) {
                setHTTPStatus("400", "Input data incorrect");
            } else {
                setHTTPStatus("200", "User updated successfully");
            }
        }
    }
    else if ($method == "DELETE") {
        switch ($urlList[1]) {
            case 'logout':
                // echo "ento DELETE logout";

                $token = substr(getallheaders()["Authorization"], 7);
                $findToken = pg_fetch_assoc(pg_query($Link, "SELECT token FROM tokens Where token='$token'"))['token'];

                if (!$findToken) {
                    setHTTPStatus("401", "Unauthorized");
                } else {
                    $logoutUserResult = pg_query($Link, "DELETE FROM tokens Where token='$token'");
                    setHTTPStatus("200", "User logged out successfully");
                }
            break;
        }
    } else {
        setHTTPStatus("400", "You can only use POST to $urlList[1]");
    }
}
?>