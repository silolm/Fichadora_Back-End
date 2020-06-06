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

function hasAuth($permisions, $callback)
{
    try {
        $data = JWT::decode(getallheaders()['Authorization'], MASTERKEY, array('HS256'));
    } catch (Exception $error) {
        return false;
    }
    $per = $callback($data);

    if ($per == Permisions::all) return true;
    if ($per == $permisions) return true;

    return false;
}

function selectAction($actions, $callback)
{
    $method = $_SERVER['REQUEST_METHOD'];
    switch ($method) {
        case "GET":
            if (hasAuth(Permisions::read, $callback)) {
                $actions['GET']();
                return;
            }
            break;
        case "POST":
            if (hasAuth(Permisions::write, $callback)) {
                $actions['POST']();
                return;
            }
            break;
        case "PUT":
            if (hasAuth(Permisions::write, $callback)) {
                $actions['PUT']();
                return;
            }
            break;
    }

    header("HTTP/1.1 401 Unauthorized");
    exit;
}