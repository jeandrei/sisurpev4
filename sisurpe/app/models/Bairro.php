<?php
    class Bairro {
        private $db;

        public function __construct(){
            //inicia a classe Database
            $this->db = new Database;
        }

        
     
         // Busca etapa por id
         public function getBairroByid($id){
            $this->db->query('SELECT * FROM bairros WHERE id = :id');
            // Bind value
            $this->db->bind(':id', $id);

            $row = $this->db->single();

            // Check row
            if($this->db->rowCount() > 0){
                return $row;
            } else {
                return false;
            }
        } 
        
        
        public function getBairros(){
          $this->db->query('SELECT * FROM bairros');            

            $result = $this->db->resultSet();

            // Check row
            if($this->db->rowCount() > 0){
                return $result;
            } else {
                return false;
            }
        }

    }//bairro
    
?>