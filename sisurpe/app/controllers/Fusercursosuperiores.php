<?php
    class Fusercursosuperiores extends Controller{
        public function __construct(){

            if((!isLoggedIn())){ 
              flash('message', 'Você deve efetuar o login para ter acesso a esta página', 'error'); 
              redirect('users/login');
              die();
            }
            $this->escolaModel = $this->model('Escola');         
            $this->fuserescolaModel = $this->model('Fuserescolaano');
            $this->fuserformacoesModel = $this->model('Fuserformacao');
            $this->fareacursoModel = $this->model('Fareacurso');
            $this->fnivelcursoModel = $this->model('Fnivelcurso');
            $this->fcursossupModel = $this->model('Fcursossuperior');
            $this->fusercursossupModel = $this->model('Fusercursosuperior');
            $this->municipioModel = $this->model('Municipio');
            $this->regiaoModel = $this->model('Regiao');
            $this->estadoModel = $this->model('Estado');           
        }

        public function getUserCursosSup(){
          if($userCursosSup = $this->fusercursossupModel->getCursosUser($_SESSION[DB_NAME . '_user_id'])){
            foreach($userCursosSup as $row) {
              $userCursosSupArray[] = [
                'ucsId' => $row->ucsId,
                'areaId' => $row->areaId,
                'area' => $this->fareacursoModel->getAreaById($row->areaId)->area,
                'nivelId' => $row->nivelId,
                'nivel' => $this->fnivelcursoModel->getNivelById($row->nivelId)->nivel,
                'cursoId' => $row->cursoId,
                'curso' => $this->fcursossupModel->getCursoById($row->cursoId)->curso,
                'tipoInstituicao' => $row->tipoInstituicao,
                'instituicaoEnsino' => $row->instituicaoEnsino,
                'municipioInstituicao' => $this->municipioModel->getMunicipioById($row->municipioId)->nomeMunicipio,
                'file' => $row->file,
                'file_name' => $row->file_name,
                'file_type' => $row->file_type
              ];
            };
            return $userCursosSupArray;
          } else {
            return false;
          }
        }

        public function index() {  
          //se o usuário ainda não adicionou nenhuma escola, faço essa verificação para evitar passar para próxima etapa pelo link sem ter adicionado uma escola
          if(!$this->fuserformacoesModel->getUserFormacoesById($_SESSION[DB_NAME . '_user_id'])){
            flash('message', 'Você deve adicionar sua formação para informar os dados de curso superior!', 'error'); 
            redirect('fuserformacoes/index');
            die();
          }           
            
          $data = [
              'areasCurso' => $this->fareacursoModel->getAreasCurso(),
              'nivelCurso' => $this->fnivelcursoModel->getNivelCurso(),
              'cursosSuperiores' => $this->fcursossupModel->getCursosSup(),
              'tiposInstituicoes' => getTipoInstituicoes(),
              'userId' => $_SESSION[DB_NAME . '_user_id'],
              'titulo' => 'Curso superior',
              'regioes' => $this->regiaoModel->getRegioes(),
              'regiaoId' => html($_POST['regiaoId']),
              'estados' => $this->estadoModel->getEstadosRegiaoById($_POST['regiaoId']),
              'estadoId' => html($_POST['estadoId']),
              'estado' => $this->estadoModel->getEstadoById($_POST['estadoId']),
              'municipioId' => html($_POST['municipioId']),
              'municipio' => $this->municipioModel->getMunicipioById($_POST['municipioId']),
              'municipios' => $this->municipioModel->getMunicipiosEstadoById($_POST['estadoId']),
              'userCursosSup' => $this->getUserCursosSup()
          ];                           
                   
          $this->view('fusercursosuperiores/index',$data);  
        }

        public function add(){           
           
            // Check for POST            
            if($_SERVER['REQUEST_METHOD'] == 'POST'){ 

                // Sanitize POST data
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);    


                //init data
                unset($data);
                
                $data = [
                  'areaId' => trim($_POST['areaId']),
                  'nivelId' => trim($_POST['nivelId']),
                  'cursoId' => trim($_POST['cursoId']),
                  'userId' => $_SESSION[DB_NAME . '_user_id'],
                  'titulo' => 'Curso superior',
                  'regioes' => $this->regiaoModel->getRegioes(),
                  'regiaoId' => html($_POST['regiaoId']),
                  'estados' => $this->estadoModel->getEstadosRegiaoById($_POST['regiaoId']),
                  'estadoId' => html($_POST['estadoId']),
                  'estado' => $this->estadoModel->getEstadoById($_POST['estadoId']),
                  'municipioId' => html($_POST['municipioId']),
                  'municipio' => $this->municipioModel->getMunicipioById($_POST['municipioId']),
                  'municipios' => $this->municipioModel->getMunicipiosEstadoById($_POST['estadoId']),
                  'tipoInstituicao' => trim($_POST['tipoInstituicao']),
                  'anoConclusao' => html($_POST['anoConclusao']),
                  'instituicaoEnsino' => trim($_POST['instituicaoEnsino']),
                  'areasCurso' => $this->fareacursoModel->getAreasCurso(),
                  'nivelCurso' => $this->fnivelcursoModel->getNivelCurso(),
                  'cursosSuperiores' => $this->fcursossupModel->getCursosSup(),
                  'userCursosSup' => $this->getUserCursosSup(),
                  'tiposInstituicoes' => getTipoInstituicoes()                  
              ];  
                                  
                
                // Valida areaId
                if(empty($data['areaId']) || ($data['areaId'] == 'null')){
                    $data['areaId_err'] = 'Por favor informe a área do curso.';
                }  

                // Valida nivelId
                if(empty($data['nivelId']) || ($data['nivelId'] == 'null')){
                  $data['nivelId_err'] = 'Por favor informe o nível do curso.';
                } 

                // Valida cursoId
                if(empty($data['cursoId']) || ($data['cursoId'] == 'null')){
                  $data['cursoId_err'] = 'Por favor informe o curso.';
                }  

                // Valida regiaoId
                if(empty($data['regiaoId']) || ($data['regiaoId'] == 'null')){
                  $data['regiaoId_err'] = 'Por favor informe a região da instituição de ensino.';
                }  
                
                  // Valida estadoId
                  if(empty($data['estadoId']) || ($data['estadoId'] == 'null')){
                  $data['estadoId_err'] = 'Por favor informe o estado da instituição de ensino.';
                } 

                // Valida municipioId
                if(empty($data['municipioId']) || ($data['municipioId'] == 'null')){
                  $data['municipioId_err'] = 'Por favor informe o município da instituição de ensino.';
                } 

                // Valida tipoInstituicao
                if(empty($data['tipoInstituicao']) || ($data['tipoInstituicao'] == 'null')){
                    $data['tipoInstituicao_err'] = 'Por favor informe tipo da instituição.';
                } 

                 // Valida ano de conclusão
                 if(empty($data['anoConclusao']) || ($data['anoConclusao'] == 'null')){
                  $data['anoConclusao_err'] = 'Por favor informe o ano de conclusão.';
                }

                // Valida nstituicaoEnsino
                if(empty($data['instituicaoEnsino']) || ($data['instituicaoEnsino'] == '')){
                  $data['instituicaoEnsino_err'] = 'Por favor informe a instituição de ensino.';
                } 
                       
                 /**
                * Faz o upload do arquivo do input id=file_post 
                * Utilizando a função upload_file que está no arquivo helpers/functions
                * Se tiver erro vai retornar o erro em $file['error'];
                */            
               
                if(!empty($_FILES['file_post']['name'])){                  
                  $file = $this->fusercursossupModel->upload('file_post'); 
                  if(empty($file['erro'])){
                    $data['file_post_data'] = $file['data'];
                    $data['file_post_name'] = $file['nome'];
                    $data['file_post_type'] = $file['tipo'];
                    $data['file_post_err'] = '';
                  }  else {
                    $data['file_post_err'] = $file['message'];
                  }  
                }                              
               
                
                // Make sure errors are empty
                if(                    
                    empty($data['areaId_err'])&&
                    empty($data['nivelId_err'])&&
                    empty($data['cursoId_err'])&&
                    empty($data['tipoInstituicao_err'])&&
                    empty($data['regiaoId_err'])&&
                    empty($data['estadoId_err'])&&
                    empty($data['municipioId_err'])&&
                    empty($data['instituicaoEnsino_err']) && 
                    empty($data['anoConclusao_err']) && 
                    empty($data['file_post_err'])
                  ){
                        // Register maiorEscolaridade
                        try {                            
                            if($this->fusercursossupModel->register($data)){
                                flash('message', 'Curso superior registrado com sucesso!','success');                        
                                redirect('fusercursosuperiores/index');
                            } else {                                
                                throw new Exception('Ops! Algo deu errado ao tentar gravar os dados! Tente novamente.');
                            } 

                        } catch (Exception $e) {
                            $erro = 'Erro: '.  $e->getMessage(); 
                            flash('message', $erro,'error');                       
                            redirect('fusercursosuperiores/add');        
                        }  
                    } else {
                      // Load the view with errors
                        $this->view('fusercursosuperiores/add', $data);
                    }               

            
            } else {
              if(!$this->fuserformacoesModel->getUserFormacoesById($_SESSION[DB_NAME . '_user_id'])){
                flash('message', 'Você deve adicionar sua formação para informar os dados de curso superior!', 'error'); 
                redirect('fuserformacoes/index');
                die();
              }   
                
              $data = [
                  'areasCurso' => $this->fareacursoModel->getAreasCurso(),
                  'nivelCurso' => $this->fnivelcursoModel->getNivelCurso(),
                  'tiposInstituicoes' => getTipoInstituicoes(),
                  'userId' => $_SESSION[DB_NAME . '_user_id'],
                  'titulo' => 'Curso superior',
                  'regioes' => $this->regiaoModel->getRegioes()
              ];

              $this->view('fusercursosuperiores/add',$data);
            }
        }


        public function delete($_ucsId){          
          try {
            if($this->fusercursossupModel->delete($_ucsId)){           
                flash('message', 'Curso removido com sucesso!','success');                     
                redirect('fusercursosuperiores/index');
            } else {                        
                throw new Exception('Ops! Algo deu errado ao tentar excluir o curso!');
            }
          } catch (Exception $e) {                   
            $erro = 'Erro: '.  $e->getMessage();                      
            flash('message', $erro,'error');
            redirect('fusercursosuperiores/index');
          }
        }

        public function download($_ucsId){
          if(!$data = $this->fusercursossupModel->getFile($_ucsId)){
            $html = "<p>Erro ao tentar recuperar o anexo.</p>";
            return $html;
          } else {
            $this->view('fusercursosuperiores/download',$data);  
          }
        }    
}   
?>