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
            
          </div>
        </div>
      </div>

  </main>

  <footer class="container">
    &copy; <?php echo date("Y"); ?>
  </footer>





<?php require APPROOT . '/views/inc/footer.php'; ?>