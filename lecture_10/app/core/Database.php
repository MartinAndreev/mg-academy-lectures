<?php

if (!defined('ROOT_PATH')) {
    exit('No direct access allowed.');
}

class Database {

    protected $_database;
    protected $_user;
    protected $_password;
    protected $_host;
    protected $_chartset;

    /**
     *
     * @var PDO
     */
    protected $_connection;

    function __construct($database, $user, $password, $host = 'localhost', $charset = 'utf8') {

        $this->_database = $database;
        $this->_user = $user;
        $this->_password = $password;
        $this->_host = $host;
        $this->_chartset = $charset;

        $this->_connect();
    }

    protected function _connect() {
        $this->_connection = new PDO("mysql:host={$this->_host};dbname={$this->_database};charset={$this->_chartset}", $this->_user, $this->_password);
    }

    public function __destruct() {
        $this->_connection = null;
    }

    /**
     * Executes the statement
     * @param type $sql
     * @param type $params
     * @return PDOStatement
     */
    public function query($sql, $params = []) {
        $stm = $this->_connection->prepare($sql);

        $stm->execute($params);
        return $stm;
    }

    /**
     * Does an insert statement
     * @param type $table
     * @param type $data
     * @return PDOStatement
     */
    public function insert($table, $data) {

        $table = strip_tags(addslashes($table));

        $values = [];

        foreach ($data as $key => $value) {
            $values[':' . $key] = $value;
        }

        $params = $values;

        $data = [];
        foreach ($params as $key => $value) {
            $data[str_replace(':', '', $key)] = $value;
        }


        $query = $this->query("INSERT INTO `{$table}` (" . implode(', ', array_keys($data)) . ") VALUES (" . implode(', ', array_keys($values)) . ")", $data);

        return $query;
    }

}
