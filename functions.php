<?php

require 'connection.php';

class Functions {

    public static function getConnection() {

        $connection = new Connection(Constants::DB_SERVER, Constants::DB_USERNAME, Constants::DB_PASSWORD, Constants::DB_NAME);
        return $connection->getConnection();
    }

    public static function cleanString($string) {

        $cleanString = trim($string);
        $cleanString = stripslashes($string);
        $cleanString = htmlspecialchars($string);

        return $cleanString;
    }
}