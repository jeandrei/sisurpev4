<?php require APPROOT . '/views/inc/header.php'; ?>

<?php flash('message');?>

<hr>
<div class="p-3 text-center">
  <h2><?php echo $data['title'];?></h2>
</div>
<hr>

<!-- paginação -->
<?php
  $paginate = $data['paginate'];
  $result = $data['results'];
?>



<?php  //var_dump($data["results"]);?>


<?php

// Caso ainda não tenham registros de aluno para o usuário
if(empty($data['results'])){ 
  $data = ['error' => "Sem dados para emitir!"]; 
}

if(isset($data['error'])){ 
  die($data['error']);
} 

?> 


<form id="filtrar" action="<?php echo URLROOT; ?>/inscricoes/arquivadas" method="GET" enctype="multipart/form-data">

<div class="row mb-3"> 
  <div class="col-10">      
      <input 
          type="text" 
          name="nomeInscricao" 
          id="nomeInscricao" 
          maxlength="200"
          class="form-control"
          value="<?php if(isset($_GET['nomeInscricao'])){htmlout($_GET['nomeInscricao']);} ?>" 
          placeholder="Buscar por uma inscrição"              
      >
  </div>
  <div class="col-2">
      <input type="submit" name="botao" class="btn btn-primary" value="Atualizar">
  </div>
</div><!--<div class="row mb-3"> -->   


</form>


  
<?php foreach ($data["results"] as $registro): ?>
  
        
  <div class="row p-1 mb-2 bg-secondary text-white">
    <div class="col-10">        
        <?php echo $registro['nome_curso'] . ' de ' . $registro['data_inicio'] .' até ' .$registro['data_termino'];?> 
    </div>
    <div class="col-2">
    <a class="text-warning" href="<?php echo URLROOT; ?>/inscricoes/reabrir/<?php echo $registro['id'];?>" role="button">Reabrir</a>
    <a class="text-danger" href="<?php echo URLROOT; ?>/inscricoes/confirm/<?php echo $registro['id'];?>" role="button">Excluir</a>
    </div>
    
  </div>
  
  
<?php endforeach; ?>
  




<!-- paginação -->
<?php   
    echo '<p>'.$paginate->links_html.'</p>';    
    echo '<p style="clear: left; padding-top: 10px;">Total de Registros: '.$paginate->total_results.'</p>';    
    echo '<p>Total de Paginas: '.$paginate->total_pages.'</p>';
    echo '<p style="clear: left; padding-top: 10px; padding-bottom: 10px;">-----------------------------------</p>';
?>







<?php require APPROOT . '/views/inc/footer.php'; ?>