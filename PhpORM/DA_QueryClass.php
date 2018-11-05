<?php
/**
 * Description of DA_LoginClass
 *
 * @author Nithin Devang
 */
require_once 'DA_DataBaseConnectionClass.php';
require_once 'DA_QueryInterface.php';

class QueryClass {

    private $tableName = array();
    private $fieldListArray = array();
    private $conditionArray = array();
    private $stringCondition = array();
    private $sql = "";
    private $dataBaseConnect;
    private $connection;

    public function __construct() {
        $db = new DA_DataBaseConnectionClass();
        $this->dataBaseConnect = $db;
        $this->connection = $db->getConnection();
    }
    public function QueryClass() { //Constructor
        self::__construct();
    }

    public function setTable($TableName_) {
        array_push($this->tableName, $TableName_);
    }

    public function addField($FieldValue_, $ColumnName_ = "0") {
        trigger_error("AddField function is depricated. Use setField(columnName, fieldValue) or setField(fieldValue) instead");
        if ($ColumnName_ == "0") {
            array_push($this->fieldListArray, $FieldValue_);
        } else {
            $this->fieldListArray[$ColumnName_] = $FieldValue_;
        }
    }

    public function setField() {
        if (func_num_args() > 2) {
            trigger_error('Expecting two arguments', E_USER_ERROR);
        }
        $args = func_get_args();
        if (func_num_args() == 1) {
            $filedValue = $args[0];
            array_push($this->fieldListArray, $filedValue);
        }
        if (func_num_args() == 2) {
            $columnName = $args[0];
            $filedValue = $args[1];
            $this->fieldListArray[$columnName] = $filedValue;
        }
    }

    public function addCondition($Key_, $Value_) {
        $this->conditionArray[$Key_] = $Value_;
    }

    public function addStringCondition($Condition) {
        if (null == $Condition)
            return;
        array_push($this->stringCondition, $Condition);
    }

    public function addMoreThanCondition($Key_, $Value_) {
        $this->MoreThanConditionArray[$Key_] = $Value_;
    }

    public function insert() {
        $Sql = "INSERT INTO " . $this->tableName[0] . " VALUES (";
        foreach ($this->fieldListArray as $FieldValueTemp) {
            if ($FieldValueTemp != 'CURRENT_TIMESTAMP') {
                $Sql = $Sql . "'" . $FieldValueTemp . "',";
            } else {
                $Sql = $Sql . $FieldValueTemp . ",";
            }
        }
        $Sql = substr($Sql, 0, strlen($Sql) - 1);
        $Sql = $Sql . ")";
        //echo $Sql;
        if ((mysqli_query($this->dataBaseConnect->getConnection(), $Sql)) == 1) {
            return true;
        } else
            return false;
    }

    public function select() {
        $Sql = "SELECT ";
        foreach ($this->fieldListArray as $FieldValueTemp) {
            $Sql = $Sql . $FieldValueTemp . ",";
        }
        $Sql = substr($Sql, 0, strlen($Sql) - 1);
        $Sql = $Sql . " FROM ";
        foreach ($this->tableName as $TempTableName) {
            $Sql = $Sql . $TempTableName . ",";
        }
        $Sql = substr($Sql, 0, strlen($Sql) - 1);
        if (count($this->conditionArray) > 0) {
            $Sql = $Sql . " WHERE ";
            foreach ($this->conditionArray as $Key_ => $Value_) {
                $Sql = $Sql . "$Key_ = '$Value_' AND ";
            }
            foreach ($this->stringCondition as $Value_) {
                $Sql = $Sql . "$Value_ AND ";
            }
            $Sql = substr($Sql, 0, strlen($Sql) - 4);
        }
        //echo $Sql;
        $Table = mysqli_query($this->dataBaseConnect->getConnection(), $Sql);
        $Json = array(); // Json is just an array variable and not in Json format
        while ($Row = mysqli_fetch_assoc($Table)) {
            array_push($Json, $Row);
        }
        return json_encode($Json);
    }

    public function update() {
        //UPDATE table_name SET column1=value, column2=value2 WHERE some_column=some_value
        $Sql = "UPDATE " . $this->tableName[0] . " SET ";
        foreach ($this->fieldListArray as $Key_ => $Value_) {
            $Sql = $Sql . "$Key_='$Value_', ";
        }
        $Sql = substr($Sql, 0, strlen($Sql) - 2);
        $Sql = $Sql . " WHERE ";
        foreach ($this->conditionArray as $Key_ => $Value_) {
            $Sql = $Sql . "$Key_='$Value_' AND ";
        }
        $Sql = substr($Sql, 0, strlen($Sql) - 4);
        //echo $Sql;
        return mysqli_query($this->dataBaseConnect->getConnection(), $Sql);
    }

    public function delete($TableName_, $FieldArray_, $ConditionArray_) {
        
    }

    public function count() {
        
    }

    public function descrite($Field_) {
        $Sql = "SELECT DISTINCT($Field_)";

        $Sql = $Sql . " FROM ";
        foreach ($this->tableName as $TempTableName) {
            $Sql = $Sql . $TempTableName . ",";
        }
        $Sql = substr($Sql, 0, strlen($Sql) - 1);
        if (count($this->conditionArray) > 0) {
            $Sql = $Sql . " WHERE ";
            foreach ($this->conditionArray as $Key_ => $Value_) {
                $Sql = $Sql . "$Key_ = '$Value_' AND ";
            }
            $Sql = substr($Sql, 0, strlen($Sql) - 4);
        }
        //echo $Sql;
        $Table = mysqli_query($this->dataBaseConnect->getConnection(), $Sql);
        $Json = array(); // Json is just an array variable and not in Json format
        while ($Row = mysqli_fetch_assoc($Table)) {
            array_push($Json, $Row);
        }
        return json_encode($Json);
    }

    public function startTransaction($TransactionName_) {
        return mysqli_autocommit($this->dataBaseConnect->getConnection(), FALSE);
    }

    public function endTransaction($TransactionName_) {
        mysqli_autocommit($this->dataBaseConnect->getConnection(), TRUE);
        mysqli_close($this->dataBaseConnect->getConnection());
    }

    public function rollbackTransaction($TransactionName_) {
        mysqli_rollback($this->dataBaseConnect->getConnection());
        mysqli_close($this->dataBaseConnect->getConnection());
    }

    public function selectQueryRun($Sql_) {
        $Sql = $Sql_;
        //echo $Sql;
        $Table = mysqli_query($this->dataBaseConnect->getConnection(), $Sql);
        $Json = array(); // Json is just an array variable and not in Json format
        while ($Row = mysqli_fetch_assoc($Table)) {
            array_push($Json, $Row);
        }
        return json_encode($Json);
    }

    public function getConnection() {
        return $this->dataBaseConnect->getConnection();
    }

    public function reset() {
        $this->tableName = array();
        $this->fieldListArray = array();
        $this->conditionArray = array();
        $this->stringCondition = array();
        $this->sql = "";
    }

}

/* $a=new DA_QueryClass;
  $a->SetTable("location");
  $a->AddField("location_id");
  $a->AddField("location_name");
  $a->Select(); */
?>
