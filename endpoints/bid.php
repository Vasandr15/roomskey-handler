<?php
    function route($method, $urlList, $requestData) {
        include_once 'endpoints/helperFunctions/helpUser.php';
        global $Link;
        if ($method == "PATCH") {
            switch ($urlList[1]) {
                case '':
                    echo "ento PATCH";
                    $token = substr(getallheaders()["Authorization"], 7);

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
                    echo "ento GET bid list";
                break;
            }
        }
    }
?>