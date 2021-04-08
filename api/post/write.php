<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
  
include_once '../config/database.php';
include_once '../objects/post.php';
  
$database = new Database();
$db = $database->getConnection();
  
$usuario = $_GET['usuario'];
$conteudo = $_GET['conteudo'];

$post = new Post($db);

$post->user_id = $usuario;
$post->content = $conteudo;

  
if($post->write()){
    http_response_code(200);
  
    echo json_encode(
        array("message" => "Sucesso")
    );
}else{
  
    http_response_code(404);
  
    echo json_encode(
        array("message" => "Erro ao cadastrar.")
    );
}
  