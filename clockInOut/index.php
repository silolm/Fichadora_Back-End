<?php
require_once '../utils/db.php';
require_once '../utils/auth.php';

function get($data)
{
    $employee = $data['employee'];
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
    
    return json_encode($keep);
}

function post($body)
{
    if (!empty($body)) {
        $out = $_GET['out'] ? "'" . $_GET['out'] . "'" : 'NULL'; // set the default value
        $pauseIn = $_GET['pauseIn'] ? "'" . $_GET['pauseIn'] . "'" : 'NULL';
        $pauseOut = $_GET['pauseOut'] ? "'" . $_GET['pauseOut'] . "'" : 'NULL';

        $id = dbQuery("INSERT INTO clockinouts (`id`, `employee`, `in`, `out`, `pauseIn`, `pauseOut`) VALUES (NULL, '"
            . $body['employee'] . "','" . $body['in'] . "', $out , $pauseIn, $pauseOut)");


        return json_encode(['id' => $id]);
    }
}

function put()
{
    $body = json_decode(file_get_contents("php://input"), true);
    if (!empty($body))
        dbQuery("UPDATE clockinouts SET `out` = '" . $body['out'] . "'," . "pauseIn" . " = '" . $body['pauseIn']
            . "'," . "pauseOut" . " = '" . $body['pauseOut'] . "' WHERE id LIKE " . $body['id']);
}

selectAction(['GET' => get, 'POST' => post, 'PUT' => put], function ($jwtData, $data) {
    if ($jwtData->role === 'admin') return Permisions::all;
    if ($jwtData->role === 'clocker') return Permisions::write;
    if (isset($data['employee']) && $data['employee'] === $jwtData->DNI) return Permisions::all;
    return Permisions::none;
});
