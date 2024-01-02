<?php
    class Fuserformacoes extends Controller{
        public function __construct(){

            if((!isLoggedIn())){ 
              flash('message', 'Você deve efetuar o login para ter acesso a esta página', 'error'); 
              redirect('users/login');
              die();
            }
            //vai procurar na pasta model um arquivo chamado User.php e incluir
            $this->escolaModel = $this->model('Escola');
            $this->bairroModel = $this->model('Bairro');
            $this->fuserescolaModel = $this->model('Fuserescolaano');
            $this->fuserformacoesModel = $this->model('Fuserformacao');
        }

        public function index() { 
          
            //se o usuário ainda não adicionou nenhuma escola, faço essa verificação para evitar passar para próxima etapa pelo link sem ter adicionado uma escola
            if(!$this->fuserescolaModel->getEscolasUser($_SESSION[DB_NAME . '_user_id'])){
            flash('message', 'Você deve adicionar uma escola ao ano corrente primeiro!', 'error'); 
            redirect('fuserescolaanos/index');
            die();
            } 

            $formacoes = $this->fuserformacoesModel->getUserFormacoesById($_SESSION[DB_NAME . '_user_id']);
            $data = [
                'titulo' => 'Formação do usuário',
                'maiorEscolaridade' => $formacoes->maiorEscolaridade,
                'tipoEnsinoMedio' => $formacoes->tipoEnsinoMedio,
                'userId' => $_SESSION[DB_NAME . '_user_id'],
                'userformacao' => $this->fuserformacoesModel->getUserFormacoesById($_SESSION[DB_NAME . '_user_id']),
                'avancarLink' => ($formacoes->maiorEscolaridade == 'e_superior') ? URLROOT .'/fusercursosuperiores/index' : URLROOT .'/fuseroutroscursos/index'
            ]; 

            $this->view('fuserformacoes/index',$data);
        }

        public function add(){           
           
            // Check for POST            
            if($_SERVER['REQUEST_METHOD'] == 'POST'){ 

                // Sanitize POST data
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);   
                
                unset($data);
                $data = [
                    'titulo' => 'Formação do usuário',   
                    'userId' => $_SESSION[DB_NAME . '_user_id'],
                    'userformacao' => $this->fuserformacoesModel->getUserFormacoesById($_SESSION[DB_NAME . '_user_id']),
                    'maiorEscolaridade' => trim($_POST['maiorEscolaridade']),
                    'tipoEnsinoMedio' => trim($_POST['tipoEnsinoMedio']),
                    'avancarLink' => ($_POST['maiorEscolaridade'] == 'e_superior') ? URLROOT .'/fusercursosuperiores/index' : URLROOT .'/fuseroutroscursos/index'  
                ];                      
                
                // Valida maiorEscolaridade
                if(empty($data['maiorEscolaridade']) || ($data['maiorEscolaridade'] == 'null')){
                    $data['maiorEscolaridade_err'] = 'Por favor informe o nível de escolaridade.';
                }  
                
                // Valida tipoEnsinoMedio
                if(empty($data['tipoEnsinoMedio']) || ($data['tipoEnsinoMedio'] == 'null')){
                    $data['tipoEnsinoMedio_err'] = 'Por favor informe tipo de ensino médio cursado.';
                }           
                
                // Make sure errors are empty
                if(                    
                    empty($data['maiorEscolaridade_err'])&&
                    empty($data['tipoEnsinoMedio_err'])
                  ){
                        // Register maiorEscolaridade
                        try {

                            if($this->fuserformacoesModel->register($data)){
                                flash('message', 'Nível de escolaridade registrado com sucesso!','success');                        
                                redirect('fuserformacoes/index');
                            } else {                                
                                throw new Exception('Ops! Algo deu errado ao tentar gravar os dados! Tente novamente.');
                            } 

                        } catch (Exception $e) {
                            $erro = 'Erro: '.  $e->getMessage(); 
                            flash('message', $erro,'error');                       
                            //redirect('fuserformacoes/index'); 
                            $this->view('fuserformacoes/index',$data);       
                        }  
                    } else {                       
                        flash('message', 'Erro ao efetuar o cadastro, verifique os dados informados!','error');                     
                        $this->view('fuserformacoes/index', $data);
                    } 
            } 
        }
    
}   
?>