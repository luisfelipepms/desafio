<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
class Comment{
  
    private $conn;
    private $table_name = "comments";
  
    public $id;
    public $user_id;
    public $post_id;
    public $content;
    public $date;
  

    public function __construct($db){
        $this->conn = $db;
    }

    function read($paginaAtual){// Função que leva como parâmetro a página atual

        $no_of_records_per_page = 5; // Variável que recebe a quantidade de comentários por página
        $offset = ($paginaAtual-1) * $no_of_records_per_page;      
        $total_pages_sql = "SELECT COUNT(*) as qtd FROM comments";
      
        $stmt = $this->conn->prepare($total_pages_sql);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $totalReg = $row['qtd'];

        $total_pages = ceil($totalReg / $no_of_records_per_page);

        $sqlPag = "SELECT c.*, u.login, u.permissoes FROM ".$this->table_name." c, users u where c.user_id = u.id order by c.date desc LIMIT $offset, $no_of_records_per_page";

        $stmtP = $this->conn->prepare($sqlPag);

        $stmtP->execute();

        return $stmtP;

    }

    function readPostComment(){
        $query = "SELECT*FROM ".$this->table_name." where post_id = ".$this->post_id;

        $stmt = $this->conn->prepare($query);

        $stmt->execute();

        return $stmt;
    }

    function write(){
        // a função checkAssinante verifica se o usuário tem permissão para comentar
        // a função checkFrequencia recebe como parametro o tempo e a qtd de comentários possiveis dentro do tempo
        if($this->checkAssinante() and $this->checkFrequencia(15, 3)){
            $query = "INSERT INTO comments(user_id, post_id, content, date) values(".$this->user_id.",".$this->post_id.", ".$this->content.", NOW())";

            $stmt = $this->conn->prepare($query);

            if($stmt->execute()){
                if($this->gerarNotificacao())
                    return true;
                else
                    return false;
            }
        }
        return false;
    }

    function checkAssinante(){
        $query = "SELECT permissoes FROM users WHERE id = ?";

        $stmt = $this->conn->prepare( $query );

        $stmt->bindParam(1, $this->user_id);

        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $permissao = $row['permissoes'];

        if($permissao == 'as')
            return true;
        else
            return false;
    }

    function checkFrequencia($tempo, $qtd){

        $sql = "SELECT count(*) as qtd from comments where date >= DATE_SUB(NOW(), INTERVAL ? second)";

        $stmt = $this->conn->prepare( $sql );

        $stmt->bindParam(1, $tempo);

        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $qtdLinhas = $row['qtd'];

        if($qtdLinhas >= $qtd)
            return false;
        else
            return true;
    }

    function gerarNotificacao(){

        $queryG = "INSERT INTO notifications(sender_id, receiver_id, post_id, date) values(
            ".$this->user_id.", 
            (select user_id from posts where id = ".$this->post_id."),
            ".$this->post_id.",
            now())";

        $stmtG = $this->conn->prepare($queryG);

        if($stmtG->execute()){
            return true;
        }else{
            return false;
        }

    }
}
?>