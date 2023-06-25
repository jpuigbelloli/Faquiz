<?php
class MySqlDatabase {
    private $connection;

    public function __construct($serverName, $userName, $password, $databaseName) {
        $this->connection = mysqli_connect(
            $serverName,
            $userName,
            $password,
            $databaseName);

        if (!$this->connection) {
            die('Connection failed: ' . mysqli_connect_error());
        }
    }

    public function __destruct() {
        mysqli_close($this->connection);
    }

    public function query_assoc($sql) {
        $result = mysqli_query($this->connection, $sql);
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    public function query_num($sql) {
        $result = mysqli_query($this->connection, $sql);
        return $result->num_rows;
    }
    public function query($sql) {
        return mysqli_query($this->connection, $sql);
    }
    public function query_fetch_assoc($sql){
        $result = mysqli_query($this->connection, $sql);
        return mysqli_fetch_assoc($result);
    }

    public function query_ultimo_id($sql){
        mysqli_query($this->connection, $sql);
        $ultimoID = mysqli_insert_id($this->connection);
        return $ultimoID;
    }
}