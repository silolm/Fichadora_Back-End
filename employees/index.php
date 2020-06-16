<?php
require_once '../utils/db.php';
require_once '../utils/auth.php';
require_once '../utils/constants.php';

function get($data)
{
    $query = 'SELECT * FROM employees';
    $keep = dbQuery($query, function ($row) {
        return ([
            "DNI" => $row[0],
            "name" => $row[1],
            "lastName" => $row[2],
        ]);
    });
    
    return json_encode($keep);
}

function post($data)
{
    if (!empty($data)) {

        $query = "INSERT INTO `employees` (`DNI`, `name`, `lastName`) VALUES ('" . $data['DNI'] . "', '" . $data['name'] . "', '" . $data['lastName'] . "');";
        $payload = ["DNI" => $data['DNI'], "role" => $data['role'], "password" => $data['password']];

        dbQuery($query);

        // Llamamos al servicio auth para que el nuevo usuario pueda logearse
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, URLAUTH);
        curl_setopt($ch, CURLOPT_POST, true);
        $payload = json_encode( $payload );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec ($ch);
        curl_close ($ch);        
    }
}

selectAction(['GET' => get, 'POST' => post, 'PUT' => put], function ($jwtData, $data) {
    if ($jwtData->role === 'admin') return Permisions::all;
    return Permisions::none;
});