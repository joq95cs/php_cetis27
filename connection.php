<?php

class Connection {

    private $connection;

    public function __construct($db_server, $db_username, $db_password, $db_name) {

        $this->connection = new mysqli($db_server, $db_username, $db_password, $db_name);

        if($this->connection->connect_errno) {

            die('Error');
        }
    }

    public function getConnection() {

        return $this->connection;
    }
}