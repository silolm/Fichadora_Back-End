<?php
require_once '../utils/db.php';
require_once '../utils/auth.php';

function get()
{
    $employee = $_GET['employee'];
    $query = 'SELECT * FROM clockinouts';

    if (isset($employee))
        $query = $query . ' WHERE employee LIKE "' . $employee . '"';

    $keep = dbQuery($query, function ($row) {
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
        dbQuery("UPDATE clockinouts SET `out` = '" . $body['out'] . "'," . "pauseIn" . " = '" . $body['pauseIn']
            . "'," . "pauseOut" . " = '" . $body['pauseOut'] . "' WHERE id LIKE " . $body['id']);
}

selectAction(['GET' => get, 'POST' => post, 'PUT' => put], function ($jwtData) {
    if ($jwtData->role === 'admin') return Permisions::all;
    if ($jwtData->role === 'clocker') return Permisions::write;

    if (isset($_GET)) {
        $employee = $_GET['employee'];

        if (isset($employee) && $employee === $jwtData->DNI) return Permisions::all;
    }
    return Permisions::none;
});