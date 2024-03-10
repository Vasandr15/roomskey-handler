<?php
    function route($method, $urlList, $requestData) {
        global $Link;

        if ($method == "PATCH") {
            switch ($urlList[2]) {
                case '':
                    // echo "ento PATCH";
                    $token = substr(getallheaders()["Authorization"], 7);
                    $bidIdForChange = $_GET["id"];
                    $status = $requestData->body->status;
                    
                    $statusUpdateResult = pg_query($Link, "UPDATE keystatus SET status = '$status' Where id='$bidIdForChange'");
                    
                    if (!$statusUpdateResult) {
                        setHTTPStatus("400", "Bad Request");
                    } else {
                        setHTTPStatus("200", "Application confirmed successfully");
                    }

                break;
            }
        }
        else if ($method == "POST") {
            // TODO сделать чтобы адекватно можно было работать с url
            if (is_string('') && $urlList[3] == "repeat") {
                echo "ento POST";
                echo $urlList[2];
            }
        }
        else if ($method == "GET") {
            switch ($urlList[2]) {
                case '':

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

                    $bidInDB = pg_query($Link, "SELECT id, iduser, idkey FROM keystatus ORDER BY date $sort");
                    $bids = [];
                    while ($row = pg_fetch_assoc($bidInDB)) {
                        $bidId = $row['id'];
                        $bidUserId = $row['iduser'];
                        $bidKeyId = $row['idkey'];
                        
                        $buildingFromTheRequest = pg_fetch_assoc(pg_query($Link, "SELECT room, building FROM keys Where id='$bidKeyId'"));
                        
                        $bidFromTheRequest = pg_fetch_assoc(pg_query($Link, "SELECT date, time, status FROM keystatus Where id='$bidId'"));
                        $userFromTheRequest = pg_fetch_assoc(pg_query($Link, "SELECT name, role FROM users Where id='$bidUserId'"));

                        $time = $bidFromTheRequest['time'];
                        $date = $bidFromTheRequest['date'];
                        $repeatableCheck = pg_fetch_array(pg_query($Link, "SELECT id FROM keystatus Where iduser='$bidUserId' and idkey='$bidKeyId' and time='$time' and date<>'$date'"));

                        if (is_iterable($repeatableCheck)) {
                            $repeatable = true;
                        } else {
                            $repeatable = false;
                        }

                        $bid = [
                            "room" => $buildingFromTheRequest['room'],
                            "building" => $buildingFromTheRequest['building'],
                            "date" => $bidFromTheRequest['date'],
                            "time" => $bidFromTheRequest['time'],
                            "status" => $bidFromTheRequest['status'],
                            "keyId" => $bidKeyId,
                            "repeatable" => $repeatable,
                            "userId" => $bidUserId,
                            "userName" => $userFromTheRequest['name'],
                            "role" => $userFromTheRequest['role']
                        ];
                        array_push($bids, $bid);
                    }

                    $pagination = [
                        "size" => $size,
                        "count" => ceil(count($bids)/$size),
                        "current" => $page
                    ];

                    if ($page < 0 OR $page > ceil(count($bids)/$size)) {
                        setHTTPStatus("400", "Incorrect page");
                    } else {
                        $responseBody = [
                            "bids" => array_slice($bids, ($page-1)*$size, $size),
                            "pagination" => $pagination
                        ];
                        echo json_encode($responseBody);
                    }

                break;
            }
        }
    }
?>