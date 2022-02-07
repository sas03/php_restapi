<?php
// Headers to access through http
header('Access-Control-Allow-Origin: *');//* for public access(everybody)
header('Content-Type: application/json');//for json content
header('Access-Control-Allow-Methods: POST');//allowed method(POST)
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../config/Database.php';
include_once '../../models/Post.php';


// Instantiate DB & connect
$database = new Database();//variable database set to a new db object
$db = $database->connect();//variable db set to db connect

// Instantiate blog post object
$post = new Post($db);//the constructor from the Post takes a variable $db, added to the connection, to allow queries

// Get raw posted data
$data = json_decode(file_get_contents("php://input"));//get whatever is submitted and decode the json format of the data to raw data

$post->title = $data->title;
$post->body = $data->body;
$post->author = $data->author;
$post->category_id = $data->category_id;

// Create post - encode json content, where we pass an array with key value
// Test in Postman: http://localhost/php/REST_API/api/post/create.php
if($post->create()){
    echo json_encode(
        array('message' => 'Post Created')
    );
} else {
    echo json_encode(
        array('message' => 'Post not Created')
    );
}
