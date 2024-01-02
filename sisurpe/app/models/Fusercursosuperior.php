<?php
    class Fusercursosuperior {
        private $db;

        public function __construct(){
            //inicia a classe Database
            $this->db = new Database;
        }


        public function getCursosUser($_userId){            
            $this->db->query('SELECT fucs.ucsId,fucs.userId as userId, fucs.areaId as areaId, fucs.nivelId as nivelId, fucs.cursoId as cursoId, fucs.tipoInstituicao as tipoInstituicao, fucs.instituicaoEnsino as instituicaoEnsino, fucs.municipioId as municipioId, fucs.file as file, fucs.file_name as file_name, fucs.file_type as file_type, fucs.anoConclusao as anoConclusao  FROM f_user_curso_superior fucs WHERE fucs.userId = :userId ORDER BY fucs.instituicaoEnsino ASC');
            $this->db->bind(':userId',$_userId);
            $result = $this->db->resultSet();
            if($this->db->rowCount() > 0){
                return $result;
            } else {
                return false;
            }
        }

        // Registra um curso na tabela f_user_curso_superior
        public function register($data){             
            if($data['file_post_data']){                
                $sql = 'INSERT INTO f_user_curso_superior (userId, areaId,nivelId, cursoId,tipoInstituicao,instituicaoEnsino,municipioId,file,file_name,file_type,anoConclusao) VALUES (:userId, :areaId,:nivelId,:cursoId,:tipoInstituicao,:instituicaoEnsino,:municipioId,:file,:file_name,:file_type,:anoConclusao)';
            } else {                
                $sql = 'INSERT INTO f_user_curso_superior (userId, areaId,nivelId, cursoId,tipoInstituicao,instituicaoEnsino,municipioId,anoConclusao) VALUES (:userId, :areaId,:nivelId,:cursoId,:tipoInstituicao,:instituicaoEnsino,:municipioId,:anoConclusao)';
            }
                       
            $this->db->query($sql);          
            // Bind values
            $this->db->bind(':userId',$data['userId']);
            $this->db->bind(':areaId',$data['areaId']);
            $this->db->bind(':nivelId',$data['nivelId']);
            $this->db->bind(':cursoId',$data['cursoId']);
            $this->db->bind(':tipoInstituicao',$data['tipoInstituicao']);
            $this->db->bind(':anoConclusao',$data['anoConclusao']);
            $this->db->bind(':instituicaoEnsino',$data['instituicaoEnsino']);
            $this->db->bind(':municipioId',$data['municipioId']);
            if($data['file_post_data']){
                $this->db->bind(':file',$data['file_post_data']);
                $this->db->bind(':file_name',$data['file_post_name']);
                $this->db->bind(':file_type',$data['file_post_type']);
            }
           
            // Execute
            if($this->db->execute()){
                return $this->db->lastId;
            } else {
                return false;
            }
        }


        public function getUserFormacoesById($_userId){            
            $this->db->query('SELECT fuf.userId as userId, fuf.maiorEscolaridade as maiorEscolaridade, fuf.tipoEnsinoMedio as tipoEnsinoMedio FROM f_user_formacao fuf WHERE fuf.userId = :userId');
            $this->db->bind(':userId',$_userId);
            $row = $this->db->single();
            if($this->db->rowCount() > 0){
                return $row;
            } else {
                return false;
            }
        }


        public function delete($_ucsId){
            $this->db->query('DELETE FROM f_user_curso_superior WHERE ucsId = :ucsId');
            $this->db->bind(':ucsId', $_ucsId);
            $row = $this->db->execute();
            if($this->db->rowCount() > 0){
                return true;
            } else {
                return false;
            }        
        }

        public function upload($file){  
            try {                    
                if (
                    !isset($_FILES[$file]['error']) ||
                    is_array($_FILES[$file]['error'])
                ) {
                    throw new RuntimeException('Parâmetros inválidos.');
                }
            
                // Check $_FILES['upfile']['error'] value.
                switch ($_FILES[$file]['error']) {
                    case UPLOAD_ERR_OK:
                        break;
                    case UPLOAD_ERR_NO_FILE:
                        throw new RuntimeException('Nenhum arquivo enviado.');
                    case UPLOAD_ERR_INI_SIZE:
                    case UPLOAD_ERR_FORM_SIZE:
                        throw new RuntimeException('Arquivo excede o tamenho permitido.');
                    default:
                        throw new RuntimeException('Erro desconhecido.');
                }
            
                // You should also check filesize here. 
                //o tamanho é em bytes então tem que fazer 20*1024*1024 para
                //para 20mb
                if ($_FILES[$file]['size'] > 20971520) {
                    throw new RuntimeException('Arquivo excede o tamenho permitido.');
                }
            
                // DO NOT TRUST $_FILES['upfile']['mime'] VALUE !!
                // Check MIME Type by yourself.
                $finfo = new finfo(FILEINFO_MIME_TYPE);
                if (false === $ext = array_search(
                    $finfo->file($_FILES[$file]['tmp_name']),
                    array(
                        'jpg' => 'image/jpeg',
                        'png' => 'image/png',                        
                        'pdf' => 'application/pdf',
                    ),
                    true
                )) {
                    throw new RuntimeException('Formato inválido.');
                }               
                if (!$file = $this->db->uploadFile($file)) {
                    throw new RuntimeException('Falha ao tentar fazer o upload do arquivo.');
                }
            
            
            } catch (RuntimeException $e) {
                $file = [
                    'erro' => true,
                    'message' => $e->getMessage()
                ];            
            }
            
            return $file;
        }

        public function getFile($_ucsId){            
            $this->db->query("SELECT file,file_name,file_type FROM f_user_curso_superior WHERE  ucsId = :ucsId");
            $this->db->bind(':ucsId',$_ucsId); 
            $row = $this->db->single(); 
            if($this->db->rowCount() > 0){
                return $row;
            } else {
                return false;
            }   
        }

        
        
    }
    
?>