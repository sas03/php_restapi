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

// Get ID
$post->id = isset($_GET['id']) ? $_GET['id'] : die();//check if the id-parameter from the url(something.com?id=3) is set, and if it's set, we set the post-id to that, else cut everything off with nothing to display(die) 

// Get post
$post->read_single();

// Create array
$post_arr = array(
    'id' => $post->id,
    'title' => $post->title,
    'body' => $post->body,
    'author' => $post->author,
    'category_id' => $post->category_id,
    'category_name' => $post->category_name
);

// Make JSON
// Test in Postman: http://localhost/php/REST_API/api/post/read_single.php?id=3
print_r(json_encode($post_arr));// wrap array in Json data and print_r prints the array

?>