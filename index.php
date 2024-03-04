<?php
    // include_once 'helpers/validation.php';
    include_once 'roomskeyBackend/helperFunctions/headers.php';
    // include_once 'helpers/searchAdres.php';
    
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

    header('Content-type: application/json');

    $host = 'localhost';
    $dbname = 'db';
    $username = 'postgres';
    $password = 'postgres';

    $conn_string = "host=$host dbname=$dbname user=$username password=$password";

    $Link = pg_connect($conn_string);

    if (!$Link) {
        setHTTPStatus("500", "Ошибка подключения к базе данных.\n");
        exit;
    }

    $url = isset($_GET['q']) ? $_GET['q'] : '';
    
    $url = rtrim($url, '/');
    $urlList = explode('/', $url);

    $object = $urlList[1];
    $router = $urlList[2];
    $requestData = getData(getMethod());
    $method = getMethod();


    if (file_exists(realpath(dirname(__FILE__)).'/'.$url.'.php'))
    {       
        include_once $url.'.php';
        route($method,$urlList,$requestData);
    }
    else if (file_exists(realpath(dirname(__FILE__)).'/API/'.$object.'.php'))
    {
        include_once 'API/'.$object.'.php';
        route($method,$urlList,$requestData);
    }
    else
    {
        setHTTPStatus("404", "Not Found");
    }

    pg_close($Link);
    return;
