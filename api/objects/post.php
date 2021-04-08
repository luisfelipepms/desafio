<?php
class Post{
  
    private $conn;
    private $table_name = "posts";

    public $id;
    public $user_id;
    public $content;
    public $date;
 
    public function __construct($db){
        $this->conn = $db;
    }

    function read(){

        $query = "SELECT*FROM ". $this->table_name;

        $stmt = $this->conn->prepare($query);

        $stmt->execute();

        return $stmt;
    }

    function write(){
        $query = "INSERT INTO posts(user_id, content, date) values(".$this->user_id.", ".$this->content.", NOW())";

        $stmt = $this->conn->prepare($query);

        if($stmt->execute()){
            return true;
        }

        return false;
    }
}
?>