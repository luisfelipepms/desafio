<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';
include_once '../objects/comment.php';
  
$database = new Database();
$db = $database->getConnection();
  

$comment = new comment($db);

if (isset($_GET['page'])) {
    $paginaAtual = $_GET['page'];
} else {
    $paginaAtual = 1;
}

$stmt = $comment->read($paginaAtual);
$num = $stmt->rowCount();
  
if($num>0){
  
    $comments_arr=array();
  
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){

        extract($row);

        if($permissoes == 'as')
            $assinante = "Sim";
        else
            $assinante = "Não";

        $comment_item=array(
            "id" => $id,
            "user_id" => $user_id,
            "content" => $content,
            "date" => $date,
            "login" => $login,
            "assinante" => $assinante
        );
  
        array_push($comments_arr, $comment_item);
    }
  
    http_response_code(200);
  
    echo json_encode($comments_arr);
}else{
  
    http_response_code(404);
  
    echo json_encode(
        array("message" => "Nenhum usuário encontrado.")
    );
}
  