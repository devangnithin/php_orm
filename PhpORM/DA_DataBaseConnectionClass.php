<?php
require_once 'DA_DataBaseConnectionInterface.php';
class DA_DataBaseConnectionClass implements DA_DataBaseConnectionInterface {
    private static $UserName = "root";
    private static $Password = "";
    private static $DataBase = "ambera5m_eintegral";
    private static $Server = "localhost";
    private static $connection;

    public static function getConnection()
    {
        return self::$connection;
    }

    public function Disconnect()
    {

    }
    public function TransactionBegin($TransactionName)
    {
        mysql_query('$TransactionName');
    }
    public function EndTransaction()
    {
        mysql_query("commit");
    }
    public function DA_DataBaseConnectionClass()
    {
        self::$connection = mysqli_connect(self::$Server,self::$UserName,self::$Password, self::$DataBase);
    }

    /*public function DA_DataBaseConnectionClass($Server_,$Database_)
    {
        mysql_connect($Server_,$this->user_name,$this->password);
        $this->SelectDatabase($DataBase_);
    }*/
}
?>
