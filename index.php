<?php
    include_once 'api/helperFunctions/headers.php';
    
    global $Link;

    function getData($method)
    {
        $data = new stdClass();
        if ($method != "GET")
        {
            $data -> body = json_decode(file_get_contents('php://input'));
        }
        $data->paramaters = [];
            $dataGet = $_GET;
            foreach ($dataGet as $key => $value)
            {
                if ($key != "q")
                {
                    $data->paramatersq[$key] = $value;
                }
            }
            return $data;
    }
    function getMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }
    header('content-type: application/json');

    $host = '79.133.183.21';
    $dbname = 'keysHandler';
    $username = 'postgres';
    $password = 'bezdar123';


    $conn_string = "host=$host dbname=$dbname user=$username password=$password";

    $Link = pg_connect($conn_string);

    if (!$Link) {
        setHTTPStatus("500", "Ошибка подключения к базе данных.\n");
        exit;
    }

    $url = isset($_GET['q']) ? $_GET['q'] : '';
    
    $url = rtrim($url, '/');
    $urlList = explode('/', $url);

    $object = $urlList[0] . '/';
    $router = $urlList[1];
    // echo realpath(dirname(__FILE__)) . '/api/' . $router . '.php';
    // echo $url ;
    // echo 'api/' . $router . '.php';
    $requestData = getData(getMethod());
    $method = getMethod();

    if (file_exists(realpath(dirname(__FILE__)) . '/' . $object .  $router . '.php'))
    {   
        // echo $object . $router . '.php';
        include_once $object . $router . '.php';
        route($method, $urlList, $requestData);
    }
    else
    {
        setHTTPStatus("404", "Not Found");
    }

    pg_close($Link);
    return;
?>