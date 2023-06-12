<?php
class Operations
{
    public $servername;
    public $username;
    public $password;
    public $db;
    public $conn;
    public function __construct($server, $username, $password, $db)
    {

        $this->servername = $server;
        $this->username = $username;
        $this->password = $password;
        $this->db = $db;

        try {
            $this->conn = new PDO("mysql:host=$server;dbname=$db", $username, $password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // echo"connected..";
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    //select Query : 
    public function select($table_name, $arg = "*", $joins = null, $joinon = null, $where_case_col = null, $where_case_val = null, $limitstart = null, $limitend = null)
    {

        if (is_array($arg)) {
            $arg = implode(",", $arg);
        }
        $sql = "select $arg from ";

        if ($joins != null) {
            if (count($joinon) == 1) {
                echo "At least two table require for joins..";
            } else {
                $sql .= "" . $table_name[0] . " A " . " $joins " . $table_name[1] . " B " . " on  A." . $joinon[0] . " = B." . $joinon[1];
            }
        } else {
            $sql .= $table_name;
        }
        $wherecase = false;
        if ($where_case_col != null) {
            $sql .=  " where $where_case_col ";
            $sql .= "=  :where_case_val";
            $wherecase = true;
        }
        if ($limitstart != null) {
            $sql .= " limit $limitstart";
        }
        if ($limitend != null) {
            $sql .= " , $limitend";
        }
        $query = $this->conn->prepare($sql);
        if ($wherecase) {
            $query->bindParam(":where_case_val", $where_case_val);
        }
        $query->execute();
        $row = $query->fetchAll(PDO::FETCH_ASSOC);
        return $row;
        // while($row = $query->Fetch(PDO::FETCH_ASSOC)){
        //     echo "<pre>";
        //     print_r($row);
        //     echo "<pre";
        // };
    }

    // insert function..
    public function insert($tablename, $col_name, $values)
    {
        // to make select of columns to string   
        $cols = "`" . implode("`,`", $col_name) . "`";
        //to make select of values of colums to string
        $col_values = ":" . implode(",:", $col_name);
        // $sql = "insert into :tablename() values (:values)";
        $sql = "insert into $tablename($cols)";
        $sql2 = $sql . "values($col_values)";

        $query = $this->conn->prepare($sql2);
        if (count($col_name) == count($values)) {
            foreach ($col_name as $keys => $value) {
                $query->bindParam(":$value", $values[$keys]);
            }
            $query->execute();
        } else {
            echo "Number of Column name and Values does not matched ..";
        }
        return $this->conn->lastInsertId();
    }

    //delete function to perform delete operetions
    public function delete($table_name, $where_col_name, $where_value)
    {
        $sql = "delete from $table_name where $where_col_name = :$where_col_name";
        $query = $this->conn->prepare($sql);
        $query->bindParam(":$where_col_name", $where_value);
        $query->execute();
    }

    public function update($table_name, $col_name, $values, $where_col, $where_value)
    {

        $val = array_combine($col_name, $values);
        $val2 = array();
        foreach ($val as $key => $value) {
            $str = " `$key` = :$key";
            array_push($val2, $str);
        }
        $str = implode(" , ", $val2);

        $sql = "Update $table_name set $str where `$where_col` = :$where_col";
        $query = $this->conn->prepare($sql);
        foreach ($col_name as $key => $value) {
            $query->bindParam(":$value", $val[$value]);
        }
        $query->bindParam(":$where_col", $where_value);



        $query->execute();
    }
    function __destruct()
    {
        $this->conn = null;
    }
}
