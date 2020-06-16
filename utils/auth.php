<?php
require_once 'constants.php';
require_once '../vendor/autoload.php';

use Firebase\JWT\JWT;

abstract class Permisions
{
    const read = 444;
    const write = 755;
    const none = 000;
    const all = 777;
}

function hasAuth($permisions, $callback, $data)
{
    try {
        $jwtData = JWT::decode(getallheaders()['Authorization'], MASTERKEY, array('HS256'));
    } catch (Exception $error) {
        return false;
    }
    $per = $callback($jwtData, $data);

    if ($per == Permisions::all) return true;
    if ($per == $permisions) return true;

    return false;
}

function selectAction($actions, $callback)
{
    $method = $_SERVER['REQUEST_METHOD'];
    $data = NULL;

    try {
        switch ($method) {
            case "GET":
                $data = $_GET;
                if (hasAuth(Permisions::read, $callback, $data)) {
                    $result = $actions['GET']($data);
                    header("HTTP/1.1 200 Ok");
                    echo $result;
                    return;
                }
                break;
            case "POST":
                $data = json_decode(file_get_contents("php://input"), true); 
                if (hasAuth(Permisions::write, $callback, $data)) {
                    $result = $actions['POST']($data);
                    header("HTTP/1.1 204 No Content");
                    echo $result;
                    return;
                }
                break;
            case "PUT":
                $data = json_decode(file_get_contents("php://input"), true);
                if (hasAuth(Permisions::write, $callback, $data)) {
                    $result = $actions['PUT']($data);
                    header("HTTP/1.1 204 No Content");
                    echo $result;
                    return;
                }
                break;
        }
    }
    catch(Exception $error) {
        header("HTTP/1.1 400 Bad Request");
        exit;
    }

    header("HTTP/1.1 401 Unauthorized");
    exit;
}
