<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';
include_once '../objects/post.php';
  
$database = new Database();
$db = $database->getConnection();
  

$post = new Post($db);

$stmt = $post->read();
$num = $stmt->rowCount();
  
if($num>0){
  
    $posts_arr=array();
  
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
  
        $post_item=array(
            "id" => $id,
            "user_id" => $user_id,
            "content" => $content,
            "date" => $date
        );
  
        array_push($posts_arr, $post_item);
    }
  
    http_response_code(200);
  
    echo json_encode($posts_arr);
}else{
  
    http_response_code(404);
  
    echo json_encode(
        array("message" => "Nenhum usuário encontrado.")
    );
}
  