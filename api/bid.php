<?php
    function route($method, $urlList, $requestData) {
        global $Link;

        if ($method == "PATCH") {
            switch ($urlList[2]) {
                case '':
                    // echo "ento PATCH";
                    $headers = apache_request_headers();
                    $headers = array_change_key_case($headers, CASE_LOWER);

                    $authHeader = isset($headers['authorization']) ? $headers['authorization'] : null;
                    $token = substr($authHeader, 7);

                    $bidIdForChange = $_GET["id"];
                    $status = $requestData->body->status;
                    $userId = pg_fetch_assoc(pg_query($Link, "SELECT userid FROM tokens Where token='$token'"))['userid'];
                    $roleUser = pg_fetch_assoc(pg_query($Link, "SELECT role FROM users Where id='$userId'"));

                    echo $roleUser['role'];
                    if ($roleUser['role'] === "teacher") {
                        $statusUpdateResult = pg_query($Link, "UPDATE keystatus SET status = '$status', repeatable = true Where id='$bidIdForChange'");
                        if (!$statusUpdateResult) {
                            setHTTPStatus("400", "Bad Request");
                        } else {
                            setHTTPStatus("200", "Application confirmed successfully");
                        }
                    } else {
                        $statusUpdateResult = pg_query($Link, "UPDATE keystatus SET status = '$status' Where id='$bidIdForChange'");
                        if (!$statusUpdateResult) {
                            setHTTPStatus("400", "Bad Request");
                        } else {
                            setHTTPStatus("200", "Application confirmed successfully");
                    }
                    }
                    // при изменение статуса заявки от препода снатовится рупитб
                    

                break;
            }
        }
        else if ($method == "POST") {
            if ($urlList[2] == "repeat") {
                // echo "ento POST";
                $headers = apache_request_headers();
                $headers = array_change_key_case($headers, CASE_LOWER);

                $authHeader = isset($headers['authorization']) ? $headers['authorization'] : null;
                $token = substr($authHeader, 7);

                $bidId = $_GET["id"];

                $userId = pg_fetch_assoc(pg_query($Link, "SELECT userid FROM tokens Where token='$token'"))['userid'];
                $roleUser = pg_fetch_assoc(pg_query($Link, "SELECT role FROM users Where id='$userId'"))['role'];
                $repeatableCheck = pg_fetch_assoc(pg_query($Link, "SELECT repeatable FROM keystatus Where id='$bidId'"))['repeatable'];
                
                $infoAboutBid = pg_fetch_assoc(pg_query($Link, "SELECT idkey, time, date, iduser FROM keystatus Where id='$bidId'"));
                $idkey = $infoAboutBid['idkey'];
                $time = $infoAboutBid['time'];

                $idUserWhoMakeBid = $infoAboutBid['iduser'];
                $roleUserWhoMakeBid = pg_fetch_assoc(pg_query($Link, "SELECT role FROM users Where id='$idUserWhoMakeBid'"))['role'];

                $date = strtotime($infoAboutBid['date']);
                $date = strtotime("+7 day", $date);
                $date =  date('Y-m-d', $date);

                $infoBidOnNextWeek = pg_fetch_assoc(pg_query($Link, "SELECT iduser, id FROM keystatus Where date='$date' and time='$time' and idkey='$idkey'"));
                $idBidOnNextWeek = $infoBidOnNextWeek['id'];
                $idUserWhoHaveBidOnNextWeek = $infoBidOnNextWeek['iduser'];
                $roleUserWhoHaveBidOnNextWeek = pg_fetch_assoc(pg_query($Link, "SELECT role FROM users Where id='$idUserWhoHaveBidOnNextWeek'"))['role'];
                
                if ($roleUserWhoHaveBidOnNextWeek === '' || $roleUserWhoHaveBidOnNextWeek === null) {
                    if ($roleUser === "admin" || $roleUser === "dean") {
                        if ($repeatableCheck === 't') {
                            
                            $statusUpdateResult = pg_query($Link, "INSERT INTO keystatus(idkey, iduser, time, date, status) VALUES ('$idkey', '$idUserWhoMakeBid', '$time', '$date', 'accepted')");
        
                            if (!$statusUpdateResult) {
                                setHTTPStatus("400", "Bad Request");
                            } else {
                                setHTTPStatus("200", "Bid successfully repeat");
                            }
                        } else {
                            $statusUpdateResult = pg_query($Link, "INSERT INTO keystatus(idkey, iduser, time, date) VALUES ('$idkey', '$idUserWhoMakeBid', '$time', '$date')");
        
                            if (!$statusUpdateResult) {
                                setHTTPStatus("400", "Bad Request");
                            } else {
                                setHTTPStatus("200", "Bid successfully repeat");
                            }
                        } 
                        
                    } else {
                        setHTTPStatus("400", "Bad Request");
                    }
                } else if ($roleUserWhoHaveBidOnNextWeek === "student" || $roleUserWhoHaveBidOnNextWeek === 'public') {
                    if ($roleUser === "admin" || $roleUser === "dean") {
                        if ($repeatableCheck === 't') {
                            
                            $dporStatusOldBid = pg_query($Link, "UPDATE keystatus SET status = 'refused' Where id='$idBidOnNextWeek'");
                            $statusUpdateResult = pg_query($Link, "INSERT INTO keystatus(idkey, iduser, time, date, status) VALUES ('$idkey', '$idUserWhoMakeBid', '$time', '$date', 'accepted')");
                            
                            if (!$statusUpdateResult) {
                                setHTTPStatus("400", "Bad Request");
                            } else {
                                setHTTPStatus("200", "Bid successfully repeat");
                            }
                        } else {

                            $dporStatusOldBid = pg_query($Link, "UPDATE keystatus SET status = 'refused' Where id='$idBidOnNextWeek'");
                            $statusUpdateResult = pg_query($Link, "INSERT INTO keystatus(idkey, iduser, time, date) VALUES ('$idkey', '$idUserWhoMakeBid', '$time', '$date')");
        
                            if (!$statusUpdateResult) {
                                setHTTPStatus("400", "Bad Request");
                            } else {
                                setHTTPStatus("200", "Bid successfully repeat");
                            }
                        }
                    } else {
                        setHTTPStatus("400", "Bad Request");
                    }
                } else if ($roleUserWhoHaveBidOnNextWeek === "teacher") {
                    if ($roleUser === "admin" || $roleUser === "dean") {
                            
                        $statusUpdateResult = pg_query($Link, "INSERT INTO keystatus(idkey, iduser, time, date) VALUES ('$idkey', '$idUserWhoMakeBid', '$time', '$date')");
                        
                        if (!$statusUpdateResult) {
                            setHTTPStatus("400", "Bad Request");
                        } else {
                            setHTTPStatus("200", "Bid successfully repeat");
                        }
                    } else {
                        setHTTPStatus("400", "Bad Request");
                    }
                }

                
                
                
                
            }
        }
        else if ($method == "GET") {
            switch ($urlList[2]) {
                case '':
                    $headers = apache_request_headers();
                    $headers = array_change_key_case($headers, CASE_LOWER);

                    $authHeader = isset($headers['authorization']) ? $headers['authorization'] : null;
                    $token = substr($authHeader, 7);
                    $userId = pg_fetch_assoc(pg_query($Link, "SELECT userid FROM tokens Where token='$token'"))['userid'];
                    $role = pg_fetch_assoc(pg_query($Link, "SELECT role FROM users Where id='$userId'"))['role'];
                    
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
                    if ($role === "admin" || $role === "dean") {
                        $bidInDB = pg_query($Link, "SELECT id, iduser, idkey FROM keystatus ORDER BY date $sort");
                        $bids = [];
                        while ($row = pg_fetch_assoc($bidInDB)) {
                            $bidId = $row['id'];
                            $bidUserId = $row['iduser'];
                            $bidKeyId = $row['idkey'];
                            
                            $buildingFromTheRequest = pg_fetch_assoc(pg_query($Link, "SELECT id, room, building FROM keys Where id='$bidKeyId'"));
                            
                            $bidFromTheRequest = pg_fetch_assoc(pg_query($Link, "SELECT date, time, status, repeatable FROM keystatus Where id='$bidId'"));
                            $userFromTheRequest = pg_fetch_assoc(pg_query($Link, "SELECT name, role FROM users Where id='$bidUserId'"));

                            $bid = [
                                "id" => $bidId,
                                "room" => $buildingFromTheRequest['room'],
                                "building" => $buildingFromTheRequest['building'],
                                "date" => $bidFromTheRequest['date'],
                                "time" => $bidFromTheRequest['time'],
                                "status" => $bidFromTheRequest['status'],
                                "keyId" => $bidKeyId,
                                "repeatable" => $bidFromTheRequest['repeatable'],
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
                    } else {
                        $bidInDB = pg_query($Link, "SELECT id, iduser, idkey FROM keystatus WHERE iduser='$userId' ORDER BY date $sort");
                        $bids = [];
                        while ($row = pg_fetch_assoc($bidInDB)) {
                            $bidId = $row['id'];
                            $bidUserId = $row['iduser'];
                            $bidKeyId = $row['idkey'];
                            
                            $buildingFromTheRequest = pg_fetch_assoc(pg_query($Link, "SELECT room, building FROM keys Where id='$bidKeyId'"));
                            
                            $bidFromTheRequest = pg_fetch_assoc(pg_query($Link, "SELECT id, date, time, status, repeatable FROM keystatus Where id='$bidId'"));
                            $userFromTheRequest = pg_fetch_assoc(pg_query($Link, "SELECT name, role FROM users Where id='$bidUserId'"));

                            $bid = [
                                "id" => $bidId,
                                "room" => $buildingFromTheRequest['room'],
                                "building" => $buildingFromTheRequest['building'],
                                "date" => $bidFromTheRequest['date'],
                                "time" => $bidFromTheRequest['time'],
                                "status" => $bidFromTheRequest['status'],
                                "keyId" => $bidKeyId,
                                "repeatable" => $bidFromTheRequest['repeatable'],
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
                    }
                    

                break;
                case 'forVitya':
                    $headers = apache_request_headers();
                    $headers = array_change_key_case($headers, CASE_LOWER);

                    $authHeader = isset($headers['authorization']) ? $headers['authorization'] : null;
                    $token = substr($authHeader, 7);

                    $date = $_GET["date"];
                    $time = $_GET["time"];

                    $scheduleInDb = pg_fetch_assoc(pg_query($Link, "SELECT iduser FROM keystatus WHERE time='$time' and date='$date'"))['iduser'];
                    $voidKeys = [];

                    if (is_null($scheduleInDb)) {
                        $allKeysInDB = pg_query($Link, "SELECT room, building FROM keys ORDER BY room ASC");
                        while ($row = pg_fetch_assoc($allKeysInDB)) {
                            $checkKeyOnGiven = pg_fetch_assoc(pg_query($Link, "SELECT idkey FROM keystatus WHERE time='$time' and date='$date' and status<>'accepted'"))['idkey'];
                            if (is_null($checkKeyOnGiven)) {
                                $room = [
                                    "room" => $row['room'],
                                    "building" => $row['building']
                                ];
                                array_push($voidKeys, $room);
                            }
                        }
                        echo json_encode($voidKeys);
                    } else {
                        http_response_code(200);
                        $response = array(
                                "iduser" => "$scheduleInDb"
                        );
                        echo json_encode($response);
                    }
                break;
            }
        }
    }
?>