<?php
namespace aprendamos\persistence;

class ConnectionFactory
{
    public static function getConnection()
    {
        // host, username, password, database, (port)
        $conn = mysqli_connect('localhost', 'aprendamos', 'aprendamos', 'aprendamos', 3306);
        if ($conn->connect_error) {
            die('Connect Error (' . $conn->connect_errno . ') ' . $conn->connect_error);
        }
        return $conn;
    }
}
