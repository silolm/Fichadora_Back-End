<?php
$host = "127.0.0.1";
$username = "root";
$password = "";
$db = "backend";

function dbQuery($query, $callback = null)
{
    # Crear conexión
    $conn = mysqli_connect($GLOBALS["host"], $GLOBALS["username"], $GLOBALS["password"], $GLOBALS["db"]);
    # Comprobar conexión
    if (!$conn)
        die("Conexi&ocacuten fallida: " . mysqli_connect_error());

    $result = mysqli_query($conn, $query) or trigger_error("Query Failed! SQL: $query - Error: " . mysqli_error($conn), E_USER_ERROR);
    $warehouse = [];

    if ($callback !== null) {
        while ($row = mysqli_fetch_array($result)) {
            array_push($warehouse, $callback($row));
        }
    } else return $conn->insert_id;


    mysqli_free_result($result);
    mysqli_close($conn);
    return $warehouse;
}