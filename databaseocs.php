<?php

class Databaseocs{

    private $host;
    private $db;
    private $user;
    private $password;
    private $charset;
    private $port;

    public function __construct(){
        $this->host = 'localhost';
        $this->db = 'ocsweb';
        $this->user = 'root';
        $this->password = '';
        $this->charset = 'utf8mb4';
        $this->port = '3306';
        
        /*$this->host = 'localhost';
        $this->db = 'ocsweb';
        $this->user = 'cdiags';
        $this->password = 'remyzero0';
        $this->charset = 'utf8mb4';
        $this->port = '3306';*/
        
    }

    function connect(){
        try{
            $connection = "mysql:host=" . $this->host . ";dbname=" . $this->db . ";charset=" . $this->charset . ";port=" . $this->port;
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            
            $pdo = new PDO($connection, $this->user, $this->password, $options);
    
            return $pdo;
        }catch(PDOException $e){
            print_r('Error connection: ' . $e->getMessage());
        }
    }

}

?>