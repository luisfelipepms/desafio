<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
$_SESSION['usuario'] = 1;
include_once '../config/database.php';
include_once '../objects/comment.php';
  
$database = new Database();
$db = $database->getConnection();
  
$usuario = $_GET['usuario'];//Usuário seria recuperado pela variavel de sessão $_SESSION['usuario']
$post = $_GET['post'];
$conteudo = $_GET['conteudo'];

$comment = new Comment($db);

$comment->user_id = $usuario; 
$comment->post_id = $post;
$comment->content = $conteudo;

  
if($comment->write()){
    http_response_code(200);
  
    echo json_encode(
        array("message" => "Sucesso")
    );
}else{
  
    http_response_code(404);
  
    echo json_encode(
        array("message" => "Erro ao comentar.")
    );
}
  