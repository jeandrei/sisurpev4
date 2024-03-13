<?php
	class Certificados extends Controller{

			public function __construct(){
				// 1 Chama o model
				//$this->postModel = $this->model('Post');
			}

			public function index(){	
				
				//$handle = URLROOT.'/uploads/modeloCertificados';
				
			// Store your file destination to a variable
			/*$fileDirectory = URLROOT.'/uploads/modeloCertificados/';
			echo $fileDirectory;					
			echo '<img src="'.$fileDirectory.'/padrao.jpg" /><br />';
			die(); */

			
			$fileDirectory = 'uploads/modeloCertificados/';
			$url = URLROOT .'/'. $fileDirectory;
			$files = scandir($fileDirectory);			
			foreach ($files as $file) {
					$extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
					if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'])) {	
						$modelosCertificados[] = [
							'url' => $url.$file,
							'arquivo' => $fileDirectory.$file
						]; //'<img src="'.$url.$file.'" /><br />';
					}
			}

			
				// Posso passar valores aqui pois no view está definido um array para isso
				// public function view($view, $data = []){
				// 2 Chama um método
				//$posts = $this->postModel->getPosts();				
				// 3 coloca os valores no array
				$data = [
					'title' => 'Bem-vindo!',
					'description'=> 'O SISURPE é um sistema de centralização de registros que visa facilitar os processos internos da Secretaria de Educação, bem como auxiliar no planejamento de ações estratégicas.',
					'modelosCertificados' => $modelosCertificados
				];
				// 4 Chama o view passando os dados
				$this->view('certificados/index', $data);
			}	
			
			public function delete(){
				$arquivo = $_GET['arquivo'];	
				$data['arquivo'] = $_GET['arquivo'];
				
				if(isset($_POST['delete'])){
					removeFile($arquivo);
					redirect('certificados/index');
					die();
				} else {                  
					$this->view('certificados/confirma',$data);
					exit();
				} 				
			}
	}