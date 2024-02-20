<?php
function route($method, $urlList, $requestData)
{
    if($method == "POST")
    {
        global $Link;
        $login = $requestData->body->login;
        $password = hash("sha1", $requestData->body->password);

        $user = $Link->query("SELECT id from users Where login='$login' AND password='$password'")->fetch_assoc();
        #вот тут  надо поменять на постгресс
        if (!is_null($user))
        {
            $token = bin2hex(random_bytes(16));
            $userID = $user['id'];
            $tokenInsertResult = $Link->query("INSERT INTO tokens(value, userID) VALUES ('$token', '$userID')");
            if (!$tokenInsertResult)
            {
                //400р
                echo json_encode($Link->error);
            }
            else
            {
                echo json_encode(['token' => $token]);
            }
        }
        else
        {
            setHTTPStatus("400", "input data incorrect");
        }
    }
    else
    {
        setHTTPStatus("400", "You can only use POST to $urlList[1]");
    }
}
?>