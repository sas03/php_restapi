<?php
class Database {
    // DB Params(class properties) to connect
    private $host = 'localhost';// can only be accessed in this class
    private $db_name = 'myblog';// database name
    private $username = 'root';// database username
    private $password = '';// database password
    private $conn;//property to represent our connection

    // DB Connect
    public function connect(){
        $this->conn = null;// connection property set to null - property accessed via basic object oriented programming with $this->conn

        // The try where we try to connect
        try{
            $this->conn = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->db_name, $this->username, $this->password);//connection property set to a new PDO Object, which takes parameters(DSN=Database type, Host, DB-name, DB-password) needed to connect
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);// set the error mode for error details by using PDO Attributes
        } catch(PDOException $e){
            echo 'Connection Error: ' . $e->getMessage();//if something goes wrong, echo ''. The exception's variable contains a method called getMessage, which tells us exactly what's going on
        }

        return $this->conn;
    }
}

?>