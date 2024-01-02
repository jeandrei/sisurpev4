<?php
    class Fusercomplementacao {
        private $db;

        public function __construct(){
            //inicia a classe Database
            $this->db = new Database;
        }


        //retorna se já tem no banco um usuário com uma complementação cadastrada
        public function getUserComplementacao($_userId,$_cpId){             
            $this->db->query('SELECT * FROM f_user_complementacao_pedagogica WHERE userId = :userId AND cpId = :cpId');
            // Bind value
            $this->db->bind(':userId', $_userId);
            $this->db->bind(':cpId', $_cpId);                     
            $row = $this->db->single(); 

            // Check row
            if($this->db->rowCount() > 0){
                return $row;
            } else {
                return false;
            }
        }

        //Retorna todas as formações/complementações do usuário
        public function getUserComplementacoes($_userId){             
            $this->db->query('SELECT fucp.fucpId as fucpId, fucp.cpId as cpId, fucp.userId as userId, fcp.complementacao as complementacao FROM f_user_complementacao_pedagogica fucp, f_complementacao_pedagogica fcp WHERE fucp.cpId = fcp.cpId AND userId = :userId ORDER BY fcp.complementacao ASC');
            // Bind value
            $this->db->bind(':userId', $_userId);                              
            $result = $this->db->resultSet();
            // Check row
            if($this->db->rowCount() > 0){
                return $result;
            } else {
                return false;
            }
        }

               

        // Registra 
        public function register($data){            
            $this->db->query('INSERT INTO f_user_complementacao_pedagogica (cpId, userId) VALUES (:cpId, :userId)');
            // Bind values
            $this->db->bind(':cpId',$data['cpId']);
            $this->db->bind(':userId',$data['userId']);

            // Execute
            if($this->db->execute()){
                return $this->db->lastId;
            } else {
                return false;
            }
        }


        // Deleta um registro da tabela f_user_complementacao_pedagogica
        public function delete($_fucpId,$_userId){ 
            $this->db->query('DELETE FROM f_user_complementacao_pedagogica WHERE fucpId = :fucpId AND userId = :userId');
            $this->db->bind(':fucpId', $_fucpId);
            $this->db->bind(':userId', $_userId);
            $row = $this->db->execute();        
            if($this->db->rowCount() > 0){
                return true;
            } else {
                return false;
            }
        }         

    }//etapa
    
?>