<?php
if(!isset($CONNECT_CLASS_PHP_FILE)){
  $CONNECT_CLASS_PHP_FILE=0;
}
if($CONNECT_CLASS_PHP_FILE!=1){
  $CONNECT_CLASS_PHP_FILE=1;
  class Connect{
    var $dblink;
    var $m_id;
    var $m_pass;
    var $m_server;
    var $m_database;

    function Connect($Id, $Pass){
      $this->m_id = $Id;
      $this->m_pass = $Pass;
      $this->m_server = "localhost";
      $this->m_database = "VideoFiles";
    }

    function DBConnect(){
      $this->dblink=mysql_connect($this->m_server,$this->m_id,$this->m_pass);
      if (!$this->dblink)
      die("Unable to connect to Database");
    }

    function runQuery($SQL) {
      $this->DBConnect();
      mysql_select_db($this->m_database,$this->dblink) or die("Unable to select table".mysql_error());
      $Result=mysql_query($SQL,$this->dblink);
      if(mysql_errno()!=0) {
        echo mysql_error();
      }
      return($Result);
    }

    function closeDB() {
      mysql_close($this->dblink);
      echo mysql_error();
    }
  }
}
?>
