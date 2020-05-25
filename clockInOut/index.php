<?php
include '../utils/db.php';

$keep =  dbReq('SELECT * FROM clockinouts', function ($row) {
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