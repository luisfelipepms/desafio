<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';
include_once '../objects/comment.php';
  
$database = new Database();
$db = $database->getConnection();
  
$comment = new comment($db);

$postId = $_GET['postId'];

$comment->post_id = $postId;
  
$stmt = $comment->readPostComment();
$num = $stmt->rowCount();

if($num>0){
  
    $comments_arr=array();
  
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
  
        $comment_item=array(
            "id" => $id,
            "user_id" => $user_id,
            "post_id" => $postId,
            "content" => $content,
            "date" => $date
        );
  
        array_push($comments_arr, $comment_item);
    }
  
    http_response_code(200);
  
    echo json_encode($comments_arr);
}else{
  
    http_response_code(404);
  
    echo json_encode(
        array("message" => "Nenhum comentário para esse post.")
    );
}
  