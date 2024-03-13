<?php require APPROOT . '/views/inc/header.php'; ?>
<?php flash('message');?>   

  <main role="main">

      <section class="jumbotron text-center">
        <div class="container">
          <h1 class="jumbotron-heading">Modelos</h1>
          <p class="lead text-muted">Modelos de certificado disponíveis.</p>          
        </div>
      </section>

      <div class="album py-5 bg-light">
        <div class="container">

          <div class="row">
            
            <!-- FORMULÁRIO -->
            <form id="frmCertificados" action="<?php echo URLROOT.'/certificados/add'?>" method="POST" novalidate enctype="multipart/form-data">        
              <fieldset>
              <!-- DECIMA LINHA -->
                <!-- Adicionar arquivo-->
                  <div class="row" style="margin:5px;">  
                      <!-- Mensagem -->    
                      <div class="alert alert-warning mt-2" role="alert">
                          Arquivos permitidos com extenção <strong>jpg, png e pdf</strong>, e no máximo com <strong>20 MB</strong>. <b>Dica:</b> Se estiver utilizano o celular para bater uma foto do seu diploma, diminua a resolução da foto para não exceder o tamanho máximo permitido.
                      </div>
                      <!-- Input file -->
                      <div class="input-group mb-3">
                          <label class="input-group-text" for="file_post">Upload</label>
                          <input 
                              type="file" 
                              class="form-control" 
                              id="file_post"
                              name="file_post"                
                          ><!-- A função fileValidation está no arquivo main.js-->                   
                      </div><!--onchange="return fileValidation('file_post','file_post_err');" -->
                      <!-- Span para caso tenha erros -->
                      <span id="file_post_err" name="file_post_err" class="text-danger">
                          <?php echo isset($data['file_post_err']) ? $data['file_post_err']: ''; ?>
                      </span>
                  </div><!-- row -->            
                  <!-- Fim Adicionar arquivo -->                 
              <!-- DECIMA LINHA -->                   
              </fieldset>
              <!-- BOTÕES -->
                <div class="form-group mt-3 mb-3">           
                    <button type="submit" id="btnSalvar" name="btnSalvar" class="btn btn-primary"><i class="fa-solid fa-paper-plane"></i> Salvar</button>                   
                </div>   
              <!-- BOTÕES -->
            </form> 
            <!-- FORMULÁRIO -->
            <?php if(isset($data['modelosCertificados'])) : ?>
              <?php foreach($data['modelosCertificados'] as $key => $modelo) : ?>    
                <div class="col-md-4">
                  <div class="card mb-4 box-shadow">
                    <img class="card-img-top" src="<?php echo $modelo['url'];?>" alt="Card image cap">
                    <div class="card-body">                    
                      <div class="d-flex justify-content-between align-items-center">
                        <div class="btn-group">
                          <button type="button" class="btn btn-sm btn-outline-secondary" data-toggle="modal" data-target="#<?php echo 'modal'.$key?>">Ver</button>  
                          <a href="<?php echo URLROOT.'/certificados/delete&arquivo='.$modelo['arquivo'];?>" class="btn btn-sm btn-outline-secondary">Excluir</a>
                        </div>                      
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Modal -->
                <div class="modal fade bd-example-modal-lg" id="modal<?php echo $key;?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Ver Imagem</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <img class="img-fluid" src="<?php echo $modelo['url'];?>" alt="">
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>                      
                      </div>
                    </div>
                  </div>
                </div>
                <!-- Modal -->
              <? endforeach; ?>  
            <?php endif;?>
            
          </div>
        </div>
      </div>

  </main>

  <footer class="container">
    &copy; <?php echo date("Y"); ?>
  </footer>





<?php require APPROOT . '/views/inc/footer.php'; ?>