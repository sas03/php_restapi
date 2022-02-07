<?php
// Headers to access through http
header('Access-Control-Allow-Origin: *');//* for public access(everybody)
header('Content-Type: application/json');//for json content

include_once '../../config/Database.php';
include_once '../../models/Post.php';


// Instantiate DB & connect
$database = new Database();//variable database set to a new db object
$db = $database->connect();//variable db set to db connect

// Instantiate blog post object
$post = new Post($db);//the constructor from the Post takes a variable $db, added to the connection, to allow queries

// Blog post query
$result = $post->read();// call the read method inside the Post class to give the result of the query

// Get row count
$num = $result->rowCount();

// Check if any posts
if($num > 0){
    // Post array
    $posts_arr = array();// initialize an array
    $posts_arr['data'] = array();//to return json array with value in case you want to add things like pagination, etc / avoid a simple return of json array with data - $posts_arr['data'] is where the actual data is going to go

    // Loop through the result - fetch as an associative array
    while($row = $result->fetch(PDO::FETCH_ASSOC)){
        extract($row);//extract $row variables($id, $title, $body, $author, etc) to avoid typing $row['title'], $row['body'], etc

        $post_item = array(
            'id' => $id,
            'title' => $title,
            'body' => html_entity_decode($body),//$body can have html in, so better to wrap this in a html function
            'author' => $author,
            'category_id' => $category_id,
            'category_name' => $category_name
        );

        // Push to "data"
        array_push($posts_arr['data'], $post_item);
    }

    // Turn to JSON & output
    echo json_encode($posts_arr);
} else{
    // No Posts
    echo json_encode(
        array('message' => 'No Posts Found')
    );
}
// Test Get Method in Postman: http://localhost/php/REST_API/api/post/read.php
//json object which contains a value named "data", which is a key of data with value as an array of our posts
?>