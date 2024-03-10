<?php
function route($method, $urlList, $requestData) {
    include_once 'api/helperFunctions/helpUser.php';
    global $Link;
    if($method == "POST") {   
        switch ($urlList[2]) {
            case 'login':
                // echo "ento login!!!";
                $phone = $requestData->body->phone;
                $password = hash("sha1", $requestData->body->password);

                if ($phone !== "" && $requestData->body->password !== "") {
                    $user = pg_fetch_assoc(pg_query($Link, "SELECT id FROM users Where phone='$phone' AND password='$password'"));
                    if (!is_null($user) and $user !== false) {
                        $userID = $user['id'];
                        $tokenInsertResult = pg_query($Link, "INSERT INTO tokens(userid) VALUES ('$userID')");

                        
                        if (!$tokenInsertResult) {
                            setHTTPStatus("400", "Bad Request");
                        } else {
                            $token = pg_fetch_assoc(pg_query($Link, "SELECT token FROM tokens WHERE userid='$userID' ORDER BY createtime DESC LIMIT 1"));
                            http_response_code(200);
                            $response = array(
                                    "message" => "User logged in successfully",
                                    "token" => $token['token']
                            );
                            echo json_encode($response);
                        }
                    } else {
                        setHTTPStatus("400", "Input data incorrect");
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

                if ($email) {
                    if (!validateEmail($email)) {
                        setHTTPStatus("400", "The Email field is not a valid e-mail address");
                        return;
                    }
                }

                $password = hash("sha1", $password);
                
                $checkPhoneOnGiven = pg_fetch_assoc(pg_query($Link, "SELECT id FROM users Where phone='$phone'"));
                if ($checkPhoneOnGiven['id']) {
                    setHTTPStatus("400", "user '$phone' is taken"); 
                } else {
                    if (!$email) {
                        $userInsertResult = pg_query($Link, "INSERT INTO users(name, password, phone) VALUES ('$name', '$password', '$phone')");
                    } else {
                        $userInsertResult = pg_query($Link, "INSERT INTO users(name, email, password, phone) VALUES ('$name', '$email', '$password', '$phone')");
                    }

                    $user = pg_fetch_assoc(pg_query($Link, "SELECT id FROM users Where phone='$phone'"));
                    $userID = $user['id'];

                    $tokenInsertResult = pg_query($Link, "INSERT INTO tokens(userid) VALUES ('$userID')");
                    
                    $token = pg_fetch_assoc(pg_query($Link, "SELECT token FROM tokens WHERE userid='$userID' ORDER BY createtime DESC LIMIT 1"));

                    if (!$tokenInsertResult) {
                        setHTTPStatus("400", "Bad Request");
                    } else {
                        http_response_code(201);
                        $response = array(
                                "message" => "User registered successfully",
                                "token" => $token['token']
                        );
                        echo json_encode($response);
                    }

                }

            break;
        }
        
    }
    else if ($method == "GET") {
        switch ($urlList[2]) {
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
                $page = $_GET["page"] ?? 1;
                $size = $_GET["size"] ?? 10;
                $sort = $_GET["sort"];

                $token = substr(getallheaders()["Authorization"], 7);
                
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
                    // echo "132";
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
                    // echo "158";
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
                    // echo "184";
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
                    // echo "209";
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

            break;
        }
    }
    else if ($method == "PATCH") {
        switch ($urlList[2]) {
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
        if ($urlList[2] == "profile" && $urlList[3] == "update") {
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
        switch ($urlList[2]) {
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
        setHTTPStatus("400", "You can only use POST to $urlList[2]");
    }
}
?>