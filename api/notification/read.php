<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/database.php';
include_once '../objects/notification.php';
  
$database = new Database();
$db = $database->getConnection();
  

$notification = new Notification($db);

$stmt = $notification->read();
$num = $stmt->rowCount();
  
if($num>0){
  
    $notifications_arr=array();
  
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
  
        $notification_item=array(
            "id" => $id,
            "sender_id" => $sender_id,
            "receiver_id" => $receiver_id,
            "post_id" => $post_id,
            "date" => $date
        );
  
        array_push($notifications_arr, $notification_item);
    }
  
    http_response_code(200);
  
    echo json_encode($notifications_arr);
}else{
  
    http_response_code(404);
  
    echo json_encode(
        array("message" => "Nenhuma notificação.")
    );
}
  