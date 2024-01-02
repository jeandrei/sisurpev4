<?php
  class Presenca {
    private $db;

    public function __construct(){
        $this->db = new Database;        
    }
  
    public function register($data){       
      $this->db->query('INSERT INTO presenca (abre_presenca_id, user_id) VALUES (:abre_presenca_id, :user_id)');
      // Bind values
      $this->db->bind(':abre_presenca_id',$data['abre_presenca_id']);
      $this->db->bind(':user_id',$data['user_id']);       
      
      // Execute
      if($this->db->execute()){
          return  true;              
      } else {
          return false;
      }
  }


  public function jaRegistrado($data){
    $this->db->query('
                        SELECT 
                          * 
                        FROM 
                          presenca 
                        WHERE 
                          abre_presenca_id = :abre_presenca_id
                        AND 
                          user_id = :user_id                                   
                      ');
        $this->db->bind(':abre_presenca_id',$data['abre_presenca_id']);
        $this->db->bind(':user_id',$data['user_id']);

       $row = $this->db->single();        
        if($this->db->rowCount() > 0){
            return true;
        } else {
            return false;
        }
  }



  public function getPresencas($inscricoes_id=null){
    $this->db->query('
                      SELECT 
                        users.name, 
                        users.cpf, 
                        presenca.registro  
                      FROM 
                        inscricoes, 
                        abre_presenca, 
                        presenca, 
                        users 
                      WHERE 
                        inscricoes.id = abre_presenca.inscricoes_id 
                      AND  
                        presenca.abre_presenca_id = abre_presenca.id 
                      AND 
                        users.id = presenca.user_id 
                      AND 
                        inscricoes.id = :id
                    '); 
    $this->db->bind(':id',$inscricoes_id);  
    $result = $this->db->resultSet(); 
    if($this->db->rowCount() > 0){
        return $result;        
    } else {
        return false;
    }           
}


public function presente($abre_presenca_id,$user_id){
  $this->db->query('
                    SELECT 
                      * 
                    FROM 
                      presenca 
                    WHERE 
                      abre_presenca_id = :abre_presenca_id
                    AND 
                      user_id = :user_id                                   
                  ');
    $this->db->bind(':abre_presenca_id',$abre_presenca_id);
    $this->db->bind(':user_id',$user_id);

    $row = $this->db->single();        
    if($this->db->rowCount() > 0){
        return true;
    } else {
        return false;
    }
}

public function removePresenca($abre_presenca_id,$user_id){
  $this->db->query('
                        DELETE FROM presenca WHERE
                        abre_presenca_id = :abre_presenca_id
                        AND 
                          user_id = :user_id             
    ');
    $this->db->bind(':abre_presenca_id',$abre_presenca_id);
    $this->db->bind(':user_id',$user_id);    
    if($this->db->execute()){
        return true;
    } else {
        return false;
    }
}


    
}