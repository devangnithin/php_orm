<?php

require_once 'DA_DataBaseConnectionClass.php';
require_once 'DA_QueryInterface.php';

class DA_QueryClass {

    private $TableName = array();
    private $FieldListArray = array();
    private $ConditionArray = array();
    private $StringCondition = array();
    private $Sql = "";
    private $DataBaseConnect;
    private $connection;

    public function DA_QueryClass() { //Constructor
        $db = new DA_DataBaseConnectionClass();
        $this->DataBaseConnect = $db;
        $this->connection = $db->getConnection();
    }

    public function SetTable($TableName_) {
        array_push($this->TableName, $TableName_);
    }

    public function AddField($FieldValue_, $ColumnName_ = "0") {
        trigger_error("AddField function is depricated. Use setField(columnName, fieldValue) or setField(fieldValue) instead", E_USER_NOTICE);
        if ($ColumnName_ == "0") {
            array_push($this->FieldListArray, $FieldValue_);
        } else {
            $this->FieldListArray[$ColumnName_] = $FieldValue_;
        }
    }

    public function setField() {
        if (func_num_args() > 2) {
            trigger_error('Expecting two arguments', E_USER_ERROR);
        }
        $args = func_get_args();
        if (func_num_args() == 1) {
            $filedValue = $args[0];
            array_push($this->FieldListArray, $filedValue);
        }
        if (func_num_args() == 2) {
            $columnName = $args[0];
            $filedValue = $args[1];
            $this->FieldListArray[$columnName] = $filedValue;
        }
        
        if ($ColumnName_ == "0") {
            array_push($this->FieldListArray, $FieldValue_);
        } else {
            $this->FieldListArray[$ColumnName_] = $FieldValue_;
        }
    }

    public function AddCondition($Key_, $Value_) {
        $this->ConditionArray[$Key_] = $Value_;
    }

    public function AddStringCondition($Condition) {
        if (null == $Condition)
            return;
        array_push($this->StringCondition, $Condition);
    }

    public function AddMoreThanCondition($Key_, $Value_) {
        $this->MoreThanConditionArray[$Key_] = $Value_;
    }

    public function Insert() {
        $Sql = "INSERT INTO " . $this->TableName[0] . " VALUES (";
        foreach ($this->FieldListArray as $FieldValueTemp) {
            if ($FieldValueTemp != 'CURRENT_TIMESTAMP') {
                $Sql = $Sql . "'" . $FieldValueTemp . "',";
            } else {
                $Sql = $Sql . $FieldValueTemp . ",";
            }
        }
        $Sql = substr($Sql, 0, strlen($Sql) - 1);
        $Sql = $Sql . ")";
        //echo $Sql;
        if ((mysqli_query($this->DataBaseConnect->getConnection(), $Sql)) == 1) {
            return true;
        } else
            return false;
    }

    public function Select() {
        $Sql = "SELECT ";
        foreach ($this->FieldListArray as $FieldValueTemp) {
            $Sql = $Sql . $FieldValueTemp . ",";
        }
        $Sql = substr($Sql, 0, strlen($Sql) - 1);
        $Sql = $Sql . " FROM ";
        foreach ($this->TableName as $TempTableName) {
            $Sql = $Sql . $TempTableName . ",";
        }
        $Sql = substr($Sql, 0, strlen($Sql) - 1);
        if (count($this->ConditionArray) > 0) {
            $Sql = $Sql . " WHERE ";
            foreach ($this->ConditionArray as $Key_ => $Value_) {
                $Sql = $Sql . "$Key_ = '$Value_' AND ";
            }
            foreach ($this->StringCondition as $Value_) {
                $Sql = $Sql . "$Value_ AND ";
            }
            $Sql = substr($Sql, 0, strlen($Sql) - 4);
        }
        //echo $Sql;
        $Table = mysqli_query($this->DataBaseConnect->getConnection(), $Sql);
        $Json = array(); // Json is just an array variable and not in Json format
        while ($Row = mysqli_fetch_assoc($Table)) {
            array_push($Json, $Row);
        }
        return json_encode($Json);
    }

    public function Update() {
        //UPDATE table_name SET column1=value, column2=value2 WHERE some_column=some_value
        $Sql = "UPDATE " . $this->TableName[0] . " SET ";
        foreach ($this->FieldListArray as $Key_ => $Value_) {
            $Sql = $Sql . "$Key_='$Value_', ";
        }
        $Sql = substr($Sql, 0, strlen($Sql) - 2);
        $Sql = $Sql . " WHERE ";
        foreach ($this->ConditionArray as $Key_ => $Value_) {
            $Sql = $Sql . "$Key_='$Value_' AND ";
        }
        $Sql = substr($Sql, 0, strlen($Sql) - 4);
        //echo $Sql;
        return mysqli_query($this->DataBaseConnect->getConnection(), $Sql);
    }

    public function Delete($TableName_, $FieldArray_, $ConditionArray_) {
        
    }

    public function Count() {
        
    }

    public function descrite($Field_) {
        $Sql = "SELECT DISTINCT($Field_)";

        $Sql = $Sql . " FROM ";
        foreach ($this->TableName as $TempTableName) {
            $Sql = $Sql . $TempTableName . ",";
        }
        $Sql = substr($Sql, 0, strlen($Sql) - 1);
        if (count($this->ConditionArray) > 0) {
            $Sql = $Sql . " WHERE ";
            foreach ($this->ConditionArray as $Key_ => $Value_) {
                $Sql = $Sql . "$Key_ = '$Value_' AND ";
            }
            $Sql = substr($Sql, 0, strlen($Sql) - 4);
        }
        //echo $Sql;
        $Table = mysqli_query($this->DataBaseConnect->getConnection(), $Sql);
        $Json = array(); // Json is just an array variable and not in Json format
        while ($Row = mysqli_fetch_assoc($Table)) {
            array_push($Json, $Row);
        }
        return json_encode($Json);
    }

    public function StartTransaction($TransactionName_) {
        return mysqli_autocommit($this->DataBaseConnect->getConnection(), FALSE);
    }

    public function EndTransaction($TransactionName_) {
        mysqli_autocommit($this->DataBaseConnect->getConnection(), TRUE);
        mysqli_close($this->DataBaseConnect->getConnection());
    }

    public function RollbackTransaction($TransactionName_) {
        mysqli_rollback($this->DataBaseConnect->getConnection());
        mysqli_close($this->DataBaseConnect->getConnection());
    }

    public function SelectQueryRun($Sql_) {
        $Sql = $Sql_;
        //echo $Sql;
        $Table = mysqli_query($this->DataBaseConnect->getConnection(), $Sql);
        $Json = array(); // Json is just an array variable and not in Json format
        while ($Row = mysqli_fetch_assoc($Table)) {
            array_push($Json, $Row);
        }
        return json_encode($Json);
    }

    public function getConnection() {
        return $this->DataBaseConnect->getConnection();
    }

    public function reset() {
        $this->TableName = array();
        $this->FieldListArray = array();
        $this->ConditionArray = array();
        $this->StringCondition = array();
        $this->Sql = "";
    }

}

/* $a=new DA_QueryClass;
  $a->SetTable("location");
  $a->AddField("location_id");
  $a->AddField("location_name");
  $a->Select(); */
?>
