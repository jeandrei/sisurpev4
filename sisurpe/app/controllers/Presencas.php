<?php 
    class Presencas extends Controller{
         public function __construct(){            
          $this->abrePresencaModel = $this->model('Abrepresenca');    
          $this->inscricaoModel = $this->model('Inscricoe');
          $this->inscritoModel = $this->model('Inscrito');
          $this->temaModel = $this->model('Tema');
          $this->userModel = $this->model('User');    
          $this->presencaModel = $this->model('Presenca');  
        }
        
        public function index($abre_presenca_id){ 
          
          if((!isLoggedIn())){ 
            flash('message', 'Você deve efetuar o login para ter acesso a esta página', 'error'); 
            redirect('pages/index');
            die();
          } else if ((!isAdmin()) && (!isSec())){                
              flash('message', 'Você não tem permissão de acesso a esta página', 'error'); 
              redirect('pages/index'); 
              die();
          }   
          
          $inscricoes_id = $this->abrePresencaModel->getInscricaoId($abre_presenca_id)->inscricoes_id; 
          $data = [
              'abre_presenca_id' => $abre_presenca_id, 
              'inscricoes_id' => $inscricoes_id,        
              'title' => 'Registro de Presenca',
              'description'=> 'Registre aqui sua presença',
              'curso' => $this->inscricaoModel->getInscricaoById($inscricoes_id),
              'presenca_em_andamento' => $this->abrePresencaModel->temPresencaEmAndamento($inscricoes_id)
          ];            
          
          $this->view('presencas/index', $data);
        }  



        public function fechar($abre_presenca_id){        
          
          if((!isLoggedIn())){ 
            flash('message', 'Você deve efetuar o login para ter acesso a esta página', 'error'); 
            redirect('pages/index');
            die();
          } else if ((!isAdmin()) && (!isSec())){                
              flash('message', 'Você não tem permissão de acesso a esta página', 'error'); 
              redirect('pages/index'); 
              die();
          }   
        
          $this->abrePresencaModel->fecharPresenca($abre_presenca_id);
          $inscricoes_id = $this->abrePresencaModel->getInscricaoId($abre_presenca_id);
          
          redirect('abrepresencas/index/' . $inscricoes_id->inscricoes_id);
      } 


        public function add(){

          if((!isLoggedIn())){ 
            flash('message', 'Você deve efetuar o login para ter acesso a esta página', 'error'); 
            redirect('pages/index');
            die();
          } else if ((!isAdmin()) && (!isSec())){                
              flash('message', 'Você não tem permissão de acesso a esta página', 'error'); 
              redirect('pages/index'); 
              die();
          }   

          $data=[
            'abre_presenca_id' => $_POST['abre_presenca_id'],
            'user_id'=>$_POST['user_id']               
          ];
        

        $error=[];
       
        if(empty($data['abre_presenca_id'])){
            $error['abre_presenca_id_err'] = 'Erro ao tentar recuperar o id da presença!';
        }

        if(empty($data['user_id'])){
          $error['user_id_err'] = 'Erro ao tentar recuperar o id usuário!';
        }



        //Se o usuário já tiver presença nesse curso eu dou a mensagem de erro
        if($this->presencaModel->jaRegistrado($data)){
          $json_ret = array(
            'classe'=>'alert alert-danger', 
            'message'=>'Usuário já registrado para esta presença!',
            'error'=>$data
            );                     
            echo json_encode($json_ret); 
            return;
        }
                   


        if(
            empty($error['abre_presenca_id_err']) && 
            empty($error['user_id_err']) 
          )
        {                
            try{

                if($this->presencaModel->register($data)){                        
                    $json_ret = array(                                            
                                        'error'=>false,
                                        'classe'=>'alert alert-success',
                                        'message'=>'Presença Confirmada',
                                    );                     
                    
                    echo json_encode($json_ret); 
                }     
            } catch (Exception $e) {
                $json_ret = array(
                        'classe'=>'alert alert-danger', 
                        'message'=>'Erro ao gravar os dados',
                        'error'=>$data
                        );                     
                echo json_encode($json_ret); 
            }


            
        }   else {
            $json_ret = array(
                'classe'=>'alert alert-danger', 
                'message'=>'Erro ao tentar gravar os dados',
                'error'=>$error
            );
            echo json_encode($json_ret);
        }                             
      }//add



      public function update(){

        if((!isLoggedIn())){ 
          flash('message', 'Você deve efetuar o login para ter acesso a esta página', 'error'); 
          redirect('pages/index');
          die();
        } else if ((!isAdmin()) && (!isSec())){                
            flash('message', 'Você não tem permissão de acesso a esta página', 'error'); 
            redirect('pages/index'); 
            die();
        }   

        $data=[
          'abre_presenca_id' => $_POST['abre_presenca_id'],
          'user_id'=>$_POST['user_id']               
        ];      
      

      $error=[];
     
      if(empty($data['abre_presenca_id'])){
          $error['abre_presenca_id_err'] = 'Erro ao tentar recuperar o id da presença!';
      }

      if(empty($data['user_id'])){
        $error['user_id_err'] = 'Erro ao tentar recuperar o id usuário!';
      }                 


      if(
          empty($error['abre_presenca_id_err']) && 
          empty($error['user_id_err']) 
        )
      {                
          try{
              //removo a presença do usuário se ele tiver nesse curso
              if($this->presencaModel->removePresenca($data['abre_presenca_id'],$data['user_id'])){
                //se removeu certinho verifico se o usuário marcou ou desmarcou o checkbox
                if($_POST['presenca'] == 'true'){
                  //se ele marcou eu marco a presença
                  if($this->presencaModel->register($data)){                        
                    $json_ret = array(                                            
                      'class'=>'success', 
                      'message'=>'Presença confirmada!',
                      'error'=>false
                    );                     
                    
                    echo json_encode($json_ret); 
                  }     
                } else {
                  if($this->presencaModel->removePresenca($data['abre_presenca_id'],$data['user_id'])){
                    $json_ret = array(                                            
                      'class'=>'success', 
                      'message'=>'Presença removida!',
                      'error'=>false
                    );                     
                    
                    echo json_encode($json_ret);
                  }
                }
              }  
              
          } catch (Exception $e) {
              $json_ret = array
                (
                  'class'=>'error', 
                  'message'=>'Erro ao gravar os dados!',
                  'error'=>$data
                );                     
              echo json_encode($json_ret); 
          }


          
      }   else {
          $json_ret = array(
            'class'=>'error', 
            'message'=>'Erro ao tentar gravar os dados!',
            'error'=>$error
          );
          echo json_encode($json_ret);
      }                             
    }//update

        
       
        
}