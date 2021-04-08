<?php
class Notification{

    private $conn;
    private $table_name = "notifications";
  
    public $id;
    public $sender_id;
    public $receiver_id;
    public $post_id;
    public $date;

    public function __construct($db){
        $this->conn = $db;
    }

    function read(){

        $tempo = 2;

        $query = "SELECT*FROM ".$this->table_name." where date >= DATE_SUB(NOW(), INTERVAL $tempo hour)";

        $stmt = $this->conn->prepare($query);

        $stmt->execute();

        return $stmt;
    }
}
?>