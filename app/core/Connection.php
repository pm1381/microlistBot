<?php
namespace Core;

use PDO;
use PDOException;

class Connection{
    private $password;
    private $username;
    private $host;
    private $db;
    protected $con;

    private $options  = 
    array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,);

    public function openConnection($inputHost,$inputUsername,$inputPassword,$inputDb){
        try{
            $this->host = $inputHost;
            $this->username=$inputUsername;
            $this->password=$inputPassword;
            $this->db=$inputDb;
            $server = "mysql:host=".$this->host.";dbname=".$this->db;
            
          $this->con = new PDO($server, $this->username,$this->password,$this->options);
          $this->con->exec("set names utf8");
          return $this->con;
        }catch(PDOException $e){
            echo "connection failed";
        }
    }

    public function closeFunction(){
        $this->con = null;
    }
}
?>