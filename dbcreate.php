<?php
class DBCreate
{
    private $hostname = 'localhost'; //your db host
    private $username = 'root'; //your db username
    private $password = ''; //your db password
    private $db_name = 'dbphp'; //your db name
    private $mysqli;
    private $result = [];
    private $conn = false;
    protected $columns = [];

    public function __construct()
    {

        if (!$this->conn) {
            $this->mysqli = new mysqli($this->hostname, $this->username, $this->password, $this->db_name);
            $this->conn = true;
            if ($this->mysqli->connect_error) {
                array_push($this->result, $this->mysqli->connect_error);
                // die("Connection failed: " . $this->mysqli->connect_error);
                return false;
            }
        } else {
            return true;
        }
    }


    public function sql($sql)
    {
        $query = $this->mysqli->query($sql);
        if ($query) {
            // $result = $query->fetch_all(MYSQLI_ASSOC);
            return $query;
        } else {
            array_push($this->result, $this->mysqli->error);
            return false;
        }
    }

    private function tableExsits($table)
    {
        $sql = "SHOW TABLES FROM $this->db_name LIKE '$table'";
        $tableInDb = $this->mysqli->query($sql);
        if ($tableInDb) {
            if ($tableInDb->num_rows == 1) {
                return true;
            } else {
                array_push($this->result, $table . "dose not exsits in this database!");
                return false;
            }
        }
    }

    public function getResult()
    {
        $val = $this->result;
        $this->result = [];
        return $val;
    }
    function delete_table($tableName)
    {
        if ($this->tableExsits($tableName)) {

            $sql = "DROP TABLE $tableName";

            $query = $this->mysqli->query($sql);
            if ($query) {
                // $result = $query->fetch_all(MYSQLI_ASSOC);
                return "Table '$tableName' has been deleted successfully!";
            } else {
                array_push($this->result, $this->mysqli->error);
                return false;
            }
        } else {
            return "Table '$tableName' not exsits!";
        }
    }



    // try to create dynamic table start

    public function primaryKey($name = 'id')
    {
        $this->columns[] = "$name INT(11) AUTO_INCREMENT PRIMARY KEY";
        return $this;
    }

    public function string($columnName, $length = 255)
    {
        $this->columns[] = "$columnName VARCHAR($length)";
        return $this;
    }
    public function text($columnName)
    {
        $this->columns[] = "$columnName TEXT";
        return $this;
    }
    public function boolean($columnName)
    {
        $this->columns[] = "$columnName BOOLEAN";
        return $this;
    }
    public function decimal($name, $precision = 8, $scale = 2)
    {
        $this->columns[] = "$name DECIMAL($precision, $scale)";
        return $this;
    }

    public function integer($columnName)
    {
        $this->columns[] = "$columnName INT";
        return $this;
    }


    public function timestamp($columnName)
    {
        $this->columns[] = "$columnName TIMESTAMP";
        return $this;
    }

    public function nullable()
    {
        $lastColumnIndex = count($this->columns) - 1;
        if ($lastColumnIndex >= 0) {
            $this->columns[$lastColumnIndex] .= ' NULL';
        }
        return $this;
    }

    public function default($value)
    {
        $lastColumnIndex = count($this->columns) - 1;
        if ($lastColumnIndex >= 0) {
            $this->columns[$lastColumnIndex] .= " DEFAULT $value";
        }
        return $this;
    }

    public function build($tableName)
    {
        $query = "CREATE TABLE IF NOT EXISTS $tableName (" . implode(', ', $this->columns) . ")";
        // Execute the query or return it for later use
        return $query;
    }



    // try to create dynamic table end
    public function __call($name, $arguments)
    {
        echo "this is pirvet or non exsisting method:" . $name . "</br>";
        if ($arguments) {
            echo "<pre>";
            print_r($arguments);
            echo "</pre>";
        }
    }
    public function __destruct()
    {
        if ($this->conn) {
            if ($this->mysqli->close()) {
                $this->conn = false;
                return true;
            }
        } else {
            return false;
        }
    }
}
