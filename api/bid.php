<?php
    function route($method, $urlList, $requestData) {
        global $Link;

        if ($method == "PATCH") {
            switch ($urlList[1]) {
                case '':
                    echo "ento PATCH";
                    $token = substr(getallheaders()["Authorization"], 7);

                    $bidInDB = pg_query($Link, "SELECT iduser FROM keystatus ORDER BY date $sort");

                break;
            }
        }
        else if ($method == "POST") {
            // TODO сделать чтобы адекватно можно было работать с url
            if (is_string('') && $urlList[2] == "repeat") {
                echo "ento POST";
                echo $urlList[1];
            }
        }
        else if ($method == "GET") {
            switch ($urlList[1]) {
                case '':
                    // НЕ РАБОТАЕТ!!!!!!!!!!!!!
                    // echo "ento GET bid list";

                    $page = $_GET["page"] ?? 1;
                    $size = $_GET["size"] ?? 10;
                    $sort = $_GET["sort"] ?? 'ASC';
                    
                    if ($sort !== "") {
                        if ($sort[6] === "D") {
                            $sort = "DESC";
                        } else {
                            $sort = "ASC";
                        }
                    }

                    $bidInDB = pg_query($Link, "SELECT iduser FROM keystatus ORDER BY date $sort");
                    // echo json_decode($bidInDB);
                    $application = [];
                    while ($row = pg_fetch_assoc($bidInDB)) {
                        $userId = $row['iduser'] . ' ';
                        
                        $buildingFromTheRequest = pg_fetch_assoc(pg_query($Link, "SELECT room, building FROM keys Where user_id='$userId'"));
                        
                        $bidFromTheRequest = pg_fetch_assoc(pg_query($Link, "SELECT date, time, status, idkey FROM keystatus Where iduser='$userId'"));
                        echo $bidFromTheRequest['date'];
                        echo json_encode($bidFromTheRequest);
                        $userFromTheRequest = pg_fetch_assoc(pg_query($Link, "SELECT id, name FROM users Where id='$userId'"));

                        // $idkey = $bidFromTheRequest['idkey'];
                        // $time = $bidFromTheRequest['time'];
                        // $repeatableCheck = pg_query($Link, "SELECT idkey FROM keystatus Where iduser='$userId' and idkey='$idkey' and time='$time'");
                        
                        // if ($repeatableCheck) {
                        //     $repeatable = true;
                        // } else {
                        //     $repeatable = false;
                        // }

                        $bid = [
                            "room" => $buildingFromTheRequest['room'],
                            "building" => $buildingFromTheRequest['building'],
                            "date" => $bidFromTheRequest['date'],
                            "time" => $bidFromTheRequest['time'],
                            "status" => $bidFromTheRequest['status'],
                            "keyId" => $bidFromTheRequest['idkey'],
                            "repeatable" => $repeatable,
                            "userId" => $userFromTheRequest['id'],
                            "userName" => $userFromTheRequest['name']
                        ];
                        array_push($application, $bid);
                    }

                    $pagination = [
                        "size" => $size,
                        "count" => ceil(count($application)/$size),
                        "current" => $page
                    ];

                    if ($page < 0 OR $page > 3) {
                        setHTTPStatus("400", "Incorrect page");
                    } else {
                        $responseBody = [
                            "application" => array_slice($application, ($page-1)*$size, $size),
                            "pagination" => $pagination
                        ];
                        echo json_encode($responseBody);
                    }

                break;
            }
        }
    }
?>