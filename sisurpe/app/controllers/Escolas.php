<?php
	class Escolas extends Controller{
		
		public function __construct(){
			if((!isLoggedIn())){ 
				flash('message', 'Você deve efetuar o login para ter acesso a esta página', 'error'); 
				redirect('users/login');
				die();
			} else if ((!isAdmin())){                
				flash('message', 'Você não tem permissão de acesso a esta página', 'error'); 
				redirect('pages/sistem'); 
				die();
			}  
				$this->escolaModel = $this->model('Escola');
				$this->bairroModel = $this->model('Bairro');
		}

		public function index() {  				
			if($escolas = $this->escolaModel->getEscolas()){													
				foreach($escolas as $row){                    
					$data[] = [
						'id' => $row->id,
						'nome' => $row->nome,
						'bairro_id' => $row->bairro_id,
						'bairro' => $this->bairroModel->getBairroById($row->bairro_id)->nome,
						'logradouro' => $row->logradouro,                    
						'numero' => ($row->numero) ? $row->numero : '',
						'emAtividade' => ($row->emAtividade == 1) ? 'Sim' : 'Não'
					];       
				}               
				$this->view('escolas/index', $data);
			} else {                                 
				$this->view('escolas/index');
			}   
		}

		public function new(){  
			if($_SERVER['REQUEST_METHOD'] == 'POST'){
				$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING); 
				$data = [
					'nome' => trim($_POST['nome']),
					'bairro_id' => $_POST['bairro_id'],
					'logradouro' => trim($_POST['logradouro']),                    
					'numero' => ($_POST['numero']) ? trim($_POST['numero']) : null,
					'emAtividade' => trim($_POST['emAtividade']),
					'bairros' => $this->bairroModel->getBairros(),
					'nome_err' => '',
					'bairro_id_err' => '',
					'logradouro_err' => '',                   
					'emAtividade_err' => ''                    
				];    				

				// Valida nome
				if(empty($data['nome'])){
					$data['nome_err'] = 'Por favor informe o nome da escola';
				} 

				// Valida logradouro
				if(empty($data['logradouro'])){
					$data['logradouro_err'] = 'Por favor informe o logradouro';
				} 

				// Valida bairro
				if((empty($data['bairro_id'])) || ($data['bairro_id'] == 'null')){                    
					$data['bairro_id_err'] = 'Por favor informe o bairro';
				} 				

				// Valida emAtividade
				if((($data['emAtividade'])=="") || ($data['emAtividade'] <> '0') && ($data['emAtividade'] <> '1')){
					$data['emAtividade_err'] = 'Por favor informe se deseja manter a escola disponível para inscrições';
				} 
			
				if(                    
					empty($data['nome_err']) &&
					empty($data['logradouro_err']) && 
					empty($data['bairro_id_err']) &&  
					empty($data['emAtividade_err'])
				){							
					if($this->escolaModel->register($data)){						
						flash('message', 'Escola registrada com sucesso!','success');                        
						redirect('escolas/index');
					} else {
						die('Ops! Algo deu errado.');
					}								
				} else {					
					if(!empty($data['erro'])){
						flash('message', $data['erro'], 'error');
					}
					$this->view('escolas/new', $data);
				}       			
			} else {					
				$data = [
					'nome' => '',
					'bairro_id' => '',
					'bairros' => $this->bairroModel->getBairros(),
					'logradouro' => '',
					'numero' => '',
					'emAtividade' => '',
					'nome_err' => '',
					'bairro_id_err' => '',
					'logradouro_err' => '',                   
					'emAtividade_err' => ''                    
				];					
				$this->view('escolas/new', $data);
			} 
		}

		public function edit($id){ 
			if($_SERVER['REQUEST_METHOD'] == 'POST'){	
				$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);  					
				$data = [
					'id' => $id,
					'nome' => trim($_POST['nome']),
					'bairro_id' => $_POST['bairro_id'],
					'bairros' => $this->bairroModel->getBairros(),
					'logradouro' => trim($_POST['logradouro']),                    
					'numero' => ($_POST['numero']) ? trim($_POST['numero']) : null,
					'emAtividade' => trim($_POST['emAtividade']),
					'nome_err' => '',
					'bairro_id_err' => '',
					'logradouro_err' => '',                    
					'emAtividade_err' => ''                    
				];          
					
				// Valida nome
				if(empty($data['nome'])){
					$data['nome_err'] = 'Por favor informe o nome da escola';
				} 

				// Valida logradouro
				if(empty($data['logradouro'])){
					$data['logradouro_err'] = 'Por favor informe o logradouro';
				} 

				// Valida bairro
				if((empty($data['bairro_id'])) || ($data['bairro_id'] == 'null')){                    
					$data['bairro_id_err'] = 'Por favor informe o bairro';
				} 

				// Valida emAtividade
				if((($data['emAtividade'])=="") || ($data['emAtividade'] <> '0') && ($data['emAtividade'] <> '1')){
					$data['emAtividade_err'] = 'Por favor informe se deseja manter a escola disponível para inscrições';
				}  				
					
				if(                    
					empty($data['nome_err']) &&
					empty($data['logradouro_err']) && 
					empty($data['bairro_id_err']) && 
					empty($data['emAtividade_err'])
				){							
					if($this->escolaModel->update($data)){						
						flash('message', 'Escola atualizada com sucesso!','success');                        
						redirect('escolas/index');
					} else {
						die('Ops! Algo deu errado.');
					}		
				} else {					
					if(!empty($data['erro'])){
						flash('message', $data['erro'], 'error');
					}
					$this->view('escolas/edit', $data);
				}     			
			} else {					
				$escola = $this->escolaModel->getEscolaByid($id);		
				$data = [
					'id' => $id,
					'nome' => $escola->nome,
					'bairro_id' => $escola->bairro_id,
					'bairros' => $this->bairroModel->getBairros(),
					'logradouro' => $escola->logradouro,
					'numero' => ($escola->numero) ? $escola->numero : '',
					'emAtividade' => $escola->emAtividade,
					'nome_err' => '',
					'bairro_id_err' => '',
					'logradouro_err' => '',                   
					'emAtividade_err' => ''                    
				]; 
				$this->view('escolas/edit', $data);
			} 
		}   
		public function delete($id){ 	
			//VALIDAÇÃO DO ID
			if(!is_numeric($id)){
					$erro = 'ID Inválido!'; 
			} else if (!$data['escola'] = $this->escolaModel->getEscolaById($id)){
					$erro = 'ID inexistente';
			}	
			if($escolas = $this->escolaModel->getEscolas()){													
				foreach($escolas as $row){                    
					$data[] = [
						'id' => $row->id,
						'nome' => $row->nome,
						'bairro_id' => $row->bairro_id,
						'bairro' => $this->bairroModel->getBairroById($row->bairro_id)->nome,
						'logradouro' => $row->logradouro,                    
						'numero' => ($row->numero) ? $row->numero : '',
						'emAtividade' => ($row->emAtividade == 1) ? 'Sim' : 'Não'
					];       
				}  
			}
			//esse $_POST['delete'] vem lá do view('confirma');
			if(isset($_POST['delete'])){  
				if($erro){
					flash('message', $erro , 'error');                     
					$this->view('escolas/index',$data);
					die();
				}   
				try { 
					if($this->escolaModel->delete($id)){                        
						flash('message', 'Registro excluido com sucesso!', 'success'); 
						redirect('escolas/index');
					} else {
						throw new Exception('Ops! Algo deu errado ao tentar excluir os dados!');
					}
				} catch (Exception $e) {                    
					$erro = 'Erro: '.  $e->getMessage();                   
					flash('message', $erro,'error');                    
					redirect('escolas/index',$data);
					die();
				}                
			} else {                  
				$this->view('escolas/confirma',$data);
				die();
			}                 
		}

		public function atualizasituacao(){ 
			try{
				if($this->escolaModel->atualizaSituacao($_POST['escolaId'],$_POST['situacao'])){
					//para acessar esses valores no jquery
					//exemplo responseObj.message
					$json_ret = array(
						'classe'=>'success', 
						'message'=>'Dados gravados com sucesso',
						'error'=>false
					);       
					echo json_encode($json_ret); 
				}     
			} catch (Exception $e) {
				$json_ret = array(
					'classe'=>'error', 
					'message'=>'Erro ao gravar os dados',
					'error'=>$data
				);                     
				echo json_encode($json_ret); 
			}
		}        
	}   
?>