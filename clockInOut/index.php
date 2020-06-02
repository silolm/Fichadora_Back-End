<?php
include '../utils/db.php';

function get()
{
    $keep = dbQuery('SELECT * FROM clockinouts', function ($row) {
        return ([
            "id" => $row[0],
            "employee" => $row[1],
            "in" => $row[2],
            "out" => $row[3],
            "pauseIn" => $row[4],
            "pauseOut" => $row[5]
        ]);
    });
    echo json_encode($keep);
}

function post()
{
    $body = json_decode(file_get_contents("php://input"), true);
    if (!empty($body)) {
        $id = dbQuery("INSERT INTO clockinouts (`id`, `employee`, `in`, `out`, `pauseIn`, `pauseOut`) VALUES (NULL, '"
            . $body['employee'] . "','" . $body['in'] . "','" . $body['out'] . "','" . $body['pauseIn'] . "','" . $body['pauseOut'] . "')");

        echo json_encode(['id' => $id]);
    }
}

function put()
{
    $body = json_decode(file_get_contents("php://input"), true);
    if (!empty($body))
        dbQuery("UPDATE clockinouts SET `out` = '" . $body['out'] . "'," . "pauseIn" . " = '" . $body['pauseIn'] . "'," . "pauseOut" . " = '" . $body['pauseOut'] . "' WHERE id LIKE " . $body['id']);
}

$method = $_SERVER['REQUEST_METHOD'];
switch ($method) {
    case "GET":
        get();
        break;
    case "POST":
        post();
        break;
    case "PUT":
        put();

}
