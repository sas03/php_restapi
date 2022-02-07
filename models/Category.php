<?php
class Category{
    // DB Stff
    private $conn;
    private $table = 'categories';

    // Properties
    public $id;
    public $name;
    public $created_at;

    // Constructor with DB(method that runs automatically when you instanciate an object from a class)
    public function __construct($db){
        $this->conn = $db;// when we instanciate a new post, we pass in a DB object, so we set the connection of the class to that DB    
    }

    // Get categories
    public function read(){
        // Create query
        $query = 'SELECT
          id,
          name,
          created_at
        FROM
          ' . $this->table . '
        ORDER BY
          created_at DESC';
        
        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Execute query
        $stmt->execute();

        return $stmt;
    }
}

?>