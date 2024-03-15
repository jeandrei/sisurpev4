<?php
	class Userscertificado {
		private $db;

		public function __construct(){			
			$this->db = new Database;
		}

		public function getPresencasByUsuarioId($user_id){
			$this->db->query("	
        SELECT 
          ap.id, 
          ap.inscricoes_id as inscricoes_id 
        FROM 
          abre_presenca ap 
        WHERE 
          EXISTS(SELECT 
                  p.id, 
                  p.abre_presenca_id, 
                  p.user_id 
                FROM 
                  presenca p 
                WHERE 
                  ap.id = p.abre_presenca_id 
                AND 
                  p.user_id = :user_id
                )        
			"); 
			$this->db->bind(':user_id',$user_id);  			
			$result = $this->db->resultSet(); 
			if($this->db->rowCount() > 0){					
				return $result;
			} else {
				return false;
			}           
    }

	}
?>