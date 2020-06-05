<?php
require_once 'constants.php';

require_once '../vendor/autoload.php';
use Firebase\JWT\JWT;

function decryptToken($jwt)
{
    return JWT::decode($jwt, MASTERKEY, array('HS256'));
}
