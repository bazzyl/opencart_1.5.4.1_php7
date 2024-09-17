<?php
final class MySQL {
    private $link;

    public function __construct($hostname, $username, $password, $database) {
        $this->link = mysqli_connect($hostname, $username, $password);

        if (!$this->link) {
            trigger_error('Error: Could not make a database connection using ' . $username . '@' . $hostname);
        }

        if (!mysqli_select_db($this->link, $database)) {
            trigger_error('Error: Could not connect to database ' . $database);
        }

        mysqli_set_charset($this->link, 'utf8');
        mysqli_query($this->link, "SET CHARACTER_SET_CONNECTION=utf8");
        mysqli_query($this->link, "SET SQL_MODE = ''");
    }

    public function query($sql) {
        $resource = mysqli_query($this->link, $sql);

        if ($resource) {
            if ($resource instanceof mysqli_result) {
                $data = array();
                while ($result = mysqli_fetch_assoc($resource)) {
                    $data[] = $result;
                }

                mysqli_free_result($resource);

                $query = new stdClass();
                $query->row = isset($data[0]) ? $data[0] : array();
                $query->rows = $data;
                $query->num_rows = count($data);

                return $query;
            } else {
                return true;
            }
        } else {
            trigger_error('Error: ' . mysqli_error($this->link) . '<br />Error No: ' . mysqli_errno($this->link) . '<br />' . $sql);
            exit();
        }
    }

    public function escape($value) {
        return mysqli_real_escape_string($this->link, $value);
    }

    public function countAffected() {
        return mysqli_affected_rows($this->link);
    }

    public function getLastId() {
        return mysqli_insert_id($this->link);
    }

    public function __destruct() {
        mysqli_close($this->link);
    }
}

