<?php
class Post{
    // DB Stuff
    private $conn;// connection property
    private $table = 'posts'; // table for model - Post

    // Post Properties
    public $id;
    public $category_id;
    public $category_name;// later use join in queries to combine tables together and get the category name of the post
    public $title;
    public $body;
    public $author;
    public $created_at;

    // Constructor with DB(method that runs automatically when you instanciate an object from a class)
    public function __construct($db){
        $this->conn = $db;// when we instanciate a new post, we pass in a DB object, so we set the connection of the class to that DB    
    }

    // Get Posts
    public function read() {
        // Create query
        $query = 'SELECT
            c.name as category_name,
            p.id, 
            p.category_id,
            p.title,
            p.body,
            p.author,
            p.created_at
          FROM
            ' . $this->table . ' p
          LEFT JOIN
            categories c ON p.category_id = c.id
          ORDER BY 
            p.created_at DESC';//query with aliases(p,c) assigned after the 'FROM' with selects from whatever the table is(in this case $this->table='posts') - LEFT JOIN to bring in another table(categories) as c, where the field category_id in post-table = field category_id in categories-table - order by field created_at in posts-table in descendant fashion

        // Prepare statement
        $stmt = $this->conn->prepare($query);//stmt for statement. Once you have the query, you prepare it(the query isn't executed yet)
        // Execute query
        $stmt->execute();

        return $stmt;
    }

    // Get Single Post
    public function read_single(){
        // Create query
        $query = 'SELECT
            c.name as category_name,
            p.id, 
            p.category_id,
            p.title,
            p.body,
            p.author,
            p.created_at
          FROM
            ' . $this->table . ' p
          LEFT JOIN
            categories c ON p.category_id = c.id
          WHERE 
            p.id = ?
          LIMIT 0,1';//? to later use PDO Bind params to the question mark's variable - Limit 0,1 to get a single record

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Bind ID
        $stmt->bindParam(1, $this->id);//Bind the first parameter(p.id = ?) to $this->id

        // Execute query
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);//fetch an associative array

        // Set properties
        $this->title = $row['title'];
        $this->body = $row['body'];
        $this->author = $row['author'];
        $this->category_id = $row['category_id'];
        $this->category_name = $row['category_name'];
    }

    // Create Post
    public function create(){
        // Create query - with named parameters defined later(:title, :body, etc)
        $query = 'INSERT INTO ' . $this->table . '
          SET
            title = :title,
            body = :body,
            author = :author,
            category_id = :category_id';

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Clean data
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->body = htmlspecialchars(strip_tags($this->body));
        $this->author = htmlspecialchars(strip_tags($this->author));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));

        // Bind data
        $stmt->bindParam(':title', $this->title); //bind with whatever title it's going to be thanks to the :title parameter
        $stmt->bindParam(':body', $this->body); //bind with whatever body it's going to be thanks to the :body parameter
        $stmt->bindParam(':author', $this->author); //bind with whatever author it's going to be thanks to the :author parameter
        $stmt->bindParam(':category_id', $this->category_id); //bind with whatever category_id it's going to be thanks to the :category_id parameter

        // Execute query - if it executes and everything is ok
        if($stmt->execute()){
            return true;
        }

        // Print error if somethings goes wrong
        printf("Error: %s.\n", $stmt->error);

        return false;
    }

    // Update Post
    public function update(){
        // Update query - with named parameters defined later(:title, :body, etc)
        $query = 'UPDATE ' . $this->table . '
            SET
            title = :title,
            body = :body,
            author = :author,
            category_id = :category_id
            WHERE 
            id = :id';

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Clean data
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->body = htmlspecialchars(strip_tags($this->body));
        $this->author = htmlspecialchars(strip_tags($this->author));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Bind data
        $stmt->bindParam(':title', $this->title); //bind with whatever title it's going to be thanks to the :title parameter
        $stmt->bindParam(':body', $this->body); //bind with whatever body it's going to be thanks to the :body parameter
        $stmt->bindParam(':author', $this->author); //bind with whatever author it's going to be thanks to the :author parameter
        $stmt->bindParam(':category_id', $this->category_id); //bind with whatever category_id it's going to be thanks to the :category_id parameter
        $stmt->bindParam(':id', $this->id); //bind with whatever id it's going to be thanks to the :id parameter

        // Execute query - if it executes and everything is ok
        if($stmt->execute()){
            return true;
        }

        // Print error if somethings goes wrong
        printf("Error: %s.\n", $stmt->error);

        return false;
    }

    // Delete Post
    public function delete(){
        // Create query
        $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // clean data
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Bind data
        $stmt->bindParam(':id', $this->id);

        // Execute query - if it executes and everything is ok
        if($stmt->execute()){
            return true;
        }

        // Print error if somethings goes wrong
        printf("Error: %s.\n", $stmt->error);

        return false;
    }
}

?>