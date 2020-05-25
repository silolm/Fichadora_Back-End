<?php
$host = "127.0.0.1";
$username = "root";
$password = "";
$db = "backend";

function dbReq($query, $callback)
{
    # Crear conexión
    $conn = mysqli_connect($GLOBALS["host"], $GLOBALS["username"], $GLOBALS["password"], $GLOBALS["db"]);
    # Comprobar conexión
    if (!$conn)
        die("Conexi&ocacuten fallida: " . mysqli_connect_error());

    $result = mysqli_query($conn, $query);
    $warehouse = [];

    while ($row = mysqli_fetch_array($result)) {
        array_push($warehouse, $callback($row));
    }

    mysqli_free_result($result);
    mysqli_close($conn);
    return $warehouse;
}