<?php
// Headers to access through http
header('Access-Control-Allow-Origin: *');//* for public access(everybody)
header('Content-Type: application/json');//for json content

include_once '../../config/Database.php';
include_once '../../models/Category.php';


// Instantiate DB & connect
$database = new Database();//variable database set to a new db object
$db = $database->connect();//variable db set to db connect

// Instantiate category object
$category = new Category($db);//the constructor from the Category takes a variable $db, added to the connection, to allow queries

// Category read query
$result = $category->read();// call the read method inside the Category class to give the result of the query

// Get row count
$num = $result->rowCount();

// Check if any categories
if($num > 0){
    // Category array
    $cat_arr = array();// initialize an array
    $cat_arr['data'] = array();//to return json array with value in case you want to add things like pagination, etc / avoid a simple return of json array with data - $cat_arr['data'] is where the actual data is going to go

    // Loop through the result - fetch as an associative array
    while($row = $result->fetch(PDO::FETCH_ASSOC)){
        extract($row);//extract $row variables($id, $title, $body, $author, etc) to avoid typing $row['title'], $row['body'], etc

        $cat_item = array(
            'id' => $id,
            'name' => $name
        );

        // Push to "data"
        array_push($cat_arr['data'], $cat_item);
    }

    // Turn to JSON & output
    echo json_encode($cat_arr);
} else{
    // No Categories
    echo json_encode(
        array('message' => 'No Categories Found')
    );
}
// Test Get Method in Postman: http://localhost/php/REST_API/api/category/read.php
//json object which contains a value named "data", which is a key of data with value as an array of our categories
?>