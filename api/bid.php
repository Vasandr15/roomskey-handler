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
                $token = substr(getallheaders()["Authorization"], 7);
                $bidId = $_GET["id"];

                $userId = pg_fetch_assoc(pg_query($Link, "SELECT userid FROM tokens Where token='$token'"))['userid'];
                $roleUser = pg_fetch_assoc(pg_query($Link, "SELECT role FROM users Where id='$userId'"))['role'];
                $repeatableCheck = pg_fetch_assoc(pg_query($Link, "SELECT repeatable FROM keystatus Where id='$bidId'"))['repeatable'];
                echo $roleUser; 
                
                $infoAboutBid = pg_fetch_assoc(pg_query($Link, "SELECT idkey, time, date, iduser FROM keystatus Where id='$bidId'"));
                $idkey = $infoAboutBid['idkey'];
                $time = $infoAboutBid['time'];

                $idUserWhoMakeBid = $infoAboutBid['iduser'];
                $roleUserWhoMakeBid = pg_fetch_assoc(pg_query($Link, "SELECT role FROM users Where id='$idUserWhoMakeBid'"))['role'];

                $date = strtotime($infoAboutBid['date']);
                $date = strtotime("+7 day", $date);
                $date =  date('Y-m-d', $date);

                $infoBidOnNextWeek = pg_fetch_assoc(pg_query($Link, "SELECT iduser, id FROM keystatus Where date='$date' and time='$time' and idkey='$idkey'"));
                echo $infoBidOnNextWeek['id'];
                $idBidOnNextWeek = $infoBidOnNextWeek['id'];
                $idUserWhoHaveBidOnNextWeek = $infoBidOnNextWeek['iduser'];
                $roleUserWhoHaveBidOnNextWeek = pg_fetch_assoc(pg_query($Link, "SELECT role FROM users Where id='$idUserWhoHaveBidOnNextWeek'"))['role'];
                
                echo json_encode($roleUserWhoHaveBidOnNextWeek);
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
                    // сделать показ заявок под определенные роли, учитель получает только свои, студент только свои, админ и деканат все
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
                        
                        $bidFromTheRequest = pg_fetch_assoc(pg_query($Link, "SELECT date, time, status, repeatable FROM keystatus Where id='$bidId'"));
                        $userFromTheRequest = pg_fetch_assoc(pg_query($Link, "SELECT name, role FROM users Where id='$bidUserId'"));

                        $bid = [
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

                break;
            }
        }
    }
?>