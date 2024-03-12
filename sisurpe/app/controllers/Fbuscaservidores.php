<?php 
	class Fbuscaservidores extends Controller{

		public function __construct(){
			if((!isLoggedIn())){ 
				flash('message', 'Você deve efetuar o login para ter acesso a esta página', 'error'); 
				redirect('pages/index');
				die();
			} else if ((!isAdmin()) && (!isSec())){                
				flash('message', 'Você não tem permissão de acesso a esta página', 'error'); 
				redirect('pages/index'); 
				die();
			} 
			$this->userModel = $this->model('User');            
			$this->escolaModel = $this->model('Escola');
			$this->fposModel = $this->model('Fpo');
			$this->buscaServidorModel = $this->model('Fbuscaservidor');
			$this->fuserEscolaAnoModel = $this->model('Fuserescolaano');
			$this->fuserFormacaoModel = $this->model('Fuserformacao');
			$this->fusercursosSupModel = $this->model('Fusercursosuperior');
			$this->fuserposModel = $this->model('Fuserpo');
			$this->fuseroutroscurModel = $this->model('Fuseroutroscurso');
			$this->fareaModel = $this->model('Fareacurso');
			$this->fnivelModel = $this->model('Fnivelcurso');
			$this->fcursosModel = $this->model('Fcursossuperior');
			$this->municipioModel = $this->model('Municipio');
			$this->foutroscursosModel = $this->model('Foutroscurso');
			$this->fusercomplementacaoModel = $this->model('Fusercomplementacao');
			$this->fcomplementacaoModel = $this->model('Fcomplementacao');
			$this->fuserEspCursosModel = $this->model('Fusercursoespecializacao');
		}     

		public function index(){ 				
			$limit = 10;
			$data = [
				'title' => 'Busca por Servidor',
				'description' => 'Busca por registros de Servidores'          
			];
			if(isset($_GET['page'])){  
				$page = $_GET['page'];  
			} else {  
				$page = 1;  
			}  
			if(!isset($_GET['cpf'])){$_GET['cpf'] = '';}
			if(!isset($_GET['name'])){$_GET['name'] = '';}
			if(!isset($_GET['escolaId'])){$_GET['escolaId'] = '';}
			if(!isset($_GET['maiorEscolaridade'])){$_GET['maiorEscolaridade'] = '';}
			if(!isset($_GET['tipoEnsinoMedio'])){$_GET['tipoEnsinoMedio'] = '';}
			if(!isset($_GET['posId'])){$_GET['posId'] = '';}
			$options = array(
					'results_per_page' => 10,
					'url' => URLROOT . '/fbuscaservidores/index.php?page=*VAR*&cpf=' . $_GET['cpf'] .'&name=' . $_GET['name'] . '&escolaId=' . $_GET['escolaId'] . '&maiorEscolaridade=' . $_GET['maiorEscolaridade'] . '&tipoEnsinoMedio=' . $_GET['tipoEnsinoMedio'] . '&posId=' . $_GET['posId'], 
					'using_bound_params' => true,
					'named_params' => array(
																	':cpf' => $_GET['cpf'],
																	':name' => $_GET['name'],
																	':escolaId' => $_GET['escolaId'],
																	':maiorEscolaridade' => $_GET['maiorEscolaridade'],
																	':tipoEnsinoMedio' => $_GET['tipoEnsinoMedio'],
																	':posId' => $_GET['posId']
																	)     
			);		
			$paginate = $this->buscaServidorModel->getServidor($page, $options); 						
			if($paginate->success == true) {  
				$data['paginate'] = $paginate;
				$results = $paginate->resultset->fetchAll();  
			}
			$data['results'] =  $results; 			
			$data['escolas'] = $this->escolaModel->getEscolas();
			$data['pos'] = $this->fposModel->getPos();			
			$this->view('fbuscaservidores/index', $data);
		}

		public function ver($userId){ 			
			if($cursossup = $this->fusercursosSupModel->getCursosUser($userId)){ 	
				foreach($cursossup as $row){
					$cursossupArray[] = [
						'ucsId' => $row->ucsId,
						'areaId' => $row->areaId,
						'area' => $this->fareaModel->getAreaById($row->areaId)->area,
						'nivelId' => $row->nivelId,
						'nivel' => $this->fnivelModel->getNivelById($row->nivelId)->nivel,
						'cursoId' => $row->cursoId,
						'curso' => $this->fcursosModel->getCursoById($row->cursoId)->curso,
						'tipoInstituicao' => $row->tipoInstituicao,
						'instituicaoEnsino' => $row->instituicaoEnsino,
						'municipioId' => $row->municipioId,
						'municipio' => $this->municipioModel->getMunicipioById($row->municipioId)->nomeMunicipio,
						'uf' => $this->municipioModel->getEstadoMunicipio($row->municipioId)->estado,
						'anoConclusao' => $row->anoConclusao,
						'file' => $row->file						
					];
				}
			} else {
				$cursossupArray = NULL;
			}
			
			if($complementacao = $this->fusercomplementacaoModel->getUserComplementacoes($userId)){
				foreach($complementacao as $row){
					$complementacaoArray[] = [
							'cpId' => $row->cpId,
							'complementacao' => $this->fcomplementacaoModel->getComplementacaoById($row->cpId)->complementacao
					];
				}
			} else {
				$complementacaoArray = NULL;
			}
			
			if($outroscursos = $this->fuseroutroscurModel->getUserOutrosCursos($userId)){
				foreach($outroscursos as $row){
					$outroscursosArray[] = [
						'cursoId' => $row->cursoId,
						'curso' => $this->foutroscursosModel->getOutrosCursosById($row->cursoId)->curso
					];
				}
			} else {
				$outroscursosArray = NULL;
			} 

			$data = [
				'escolas' => $this->fuserEscolaAnoModel->getEscolasUser($userId),
				'user' => $this->userModel->getUserById($userId),
				'forarmacao' => $this->fuserFormacaoModel->getUserFormacoesById($userId),
				'fcursossup' => $cursossupArray,
				'fcomplementacoes' => $complementacaoArray,            
				'fpos' => $this->fuserposModel->getUserPos($userId),
				'fuserEspCursos' => $this->fuserEspCursosModel->getUserEspCursos($userId),
				'foutroscur' => $outroscursosArray
			];
			$this->view('fbuscaservidores/ver', $data);
		}   
	}   
?>