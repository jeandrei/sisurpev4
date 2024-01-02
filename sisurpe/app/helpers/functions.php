<?php 
function imprimeuf($ufsec){
$arrayEstados = array(
    'AC',
    'AL',
    'AM',
    'AP',
    'AC',
    'BA',
    'CE',
    'DF',
    'ES',
    'GO',
    'MA',
    'MT',
    'MS',
    'MG',
    'PA',
    'PB',
    'PR',
    'PE',
    'PE',
    'PI',
    'RJ',
    'RN',
    'RN',
    'RO',
    'RS',
    'RR',
    'SC',
    'SE',
    'SP',
    'TO' 
  );  
  foreach($arrayEstados as $uf){ 
    //iduf tem que ser passada pelo post
    if($uf == $ufsec){
      $html .= '<option selected value="'.$uf.'" '.'>'.$uf.'</option>';
    }
    else{
    $html .='<option value="'.$uf.'" '.'>'.$uf.'</option>';           

  }

}
return $html;
}

function getTamanhosCalcados(){
  $arrayTamanhos = array(
    '22',
    '23',
    '24',
    '25',
    '26',
    '27',
    '28',
    '29',
    '30',
    '31',
    '32',
    '33',
    '34',
    '35',
    '36',
    '37',
    '38',
    '39',
    '40',
    '41',
    '42',
    '43'
  );
  return $arrayTamanhos;
}

function getTipoInstituicoes(){
  $data = array(
    'Pública',
    'Privada'
  );
  return $data;
}

function getMaiorEscolaridade($escolaridade){
  switch ($escolaridade) {
    case 'nao_concluiu':
        return "Não concluiu o EF";
        break;
    case 'e_fundamental':
        return "Ensino Fundamental";
        break;
    case 'e_medio':
        return "Ensino Médio";
        break;
    case 'e_superior':
      return "Ensino Superior";
      break;
  }  
}

function getTipoEnsinoMedio($em){
  switch ($em) {
    case 'geral':
        return "Formação Geral";
        break;
    case 'normal':
        return "Modalidade normal (magistério)";
        break;
    case 'c_tecnico':
        return "Curso técnico";
        break;
    case 'm_indigena':
      return "Magistério indígena - modalidade normal";
      break;
  }  
}

function getArrayTamanhos(){
  $arrayTamanhos = array(
    'P',
    'M',
    'G',
    'GG',
    'XGG',
    '1',
    '2',
    '4',
    '6',
    '8',
    '10',
    '12',
    '14',
    '16'    
  );
  return $arrayTamanhos;
}

function imptamanhounif($tamanhosec){

    $arrayTamanhos = getArrayTamanhos();

    foreach($arrayTamanhos as $tamanho){ 
      //idtamanho tem que ser passada pelo post
      if($tamanho == $tamanhosec){
        $html .= '<option selected value="'.$tamanho.'" '.'>'.$tamanho.'</option>';
      }
      else{
      $html .='<option value="'.$tamanho.'" '.'>'.$tamanho.'</option>';           
  
    }
  
  }
  return $html;
  }


  function imptamanhocalc($tamanhosec){

    $arrayTamanhos = getTamanhosCalcados();

    foreach($arrayTamanhos as $tamanho){ 
      //idtamanho tem que ser passada pelo post
      if($tamanho == $tamanhosec){
        $html .= '<option selected value="'.$tamanho.'" '.'>'.$tamanho.'</option>';
      }
      else{
      $html .='<option value="'.$tamanho.'" '.'>'.$tamanho.'</option>';           
  
    }
  
  }
  return $html;
  }

  function getLinhas(){
    $arrayLinhas = array(
      'NÃO UTILIZA',
      'LINHA 03',
      'LINHA 05',
      'LINHA 06',
      'LINHA 08', 
      'LINHA 09', 
      'LINHA 14', 
      'LINHA 15', 
      'LINHA 18', 
      'LINHA 19', 
      'ROTA 02',
      'ROTA 07',
      'ROTA 14',
      'ROTA 17',
      'ROTA 17A',
      'ROTA 20-18A',
      'ROTA VAN'  
    );
    return $arrayLinhas;
  }

  function imptlinhastransporte($linhasec){
    $arrayLinhas =  getLinhas();
      foreach($arrayLinhas as $linha){ 
        //idtamanho tem que ser passada pelo post
        if($linha == $linhasec){
          $html .= '<option selected value="'.$linha.'" '.'>'.$linha.'</option>';
        }
        else{
        $html .='<option value="'.$linha.'" '.'>'.$linha.'</option>';           
    
      }
    
    }
    return $html;
    }


function validaCPF($cpf) {
 
  // Extrai somente os números
  $cpf = preg_replace( '/[^0-9]/is', '', $cpf );
   
  // Verifica se foi informado todos os digitos corretamente
  if (strlen($cpf) != 11) {
      return false;
  }
  // Verifica se foi informada uma sequência de digitos repetidos. Ex: 111.111.111-11
  if (preg_match('/(\d)\1{10}/', $cpf)) {
      return false;
  }
  // Faz o calculo para validar o CPF
  for ($t = 9; $t < 11; $t++) {
      for ($d = 0, $c = 0; $c < $t; $c++) {
          $d += $cpf{$c} * (($t + 1) - $c);
      }
      $d = ((10 * $d) % 11) % 10;
      if ($cpf{$c} != $d) {
          return false;
      }
  }
  return true;
}

function validacelular($celular){
if (preg_match('/(\(?\d{2}\)?) ?9?\d{4}-?\d{4}/', $celular)) {
  return true;
} else {
  return false;
}
}


function validanascimento($data){
$formatado = date('Y-m-d',strtotime($data));
$ano = date('Y', strtotime($formatado));
$mes = date('m', strtotime($formatado));
$dia = date('d', strtotime($formatado));
$anominimo = date('Y', strtotime('-5 year'));

if ( !checkdate( $mes , $dia , $ano )                   // se a data for inválida
     || $ano < $anominimo                                // ou o ano menor que a data mínima
     || mktime( 0, 0, 0, $mes, $dia, $ano ) > time() )  // ou a data passar de hoje
  {
    return false;
  }else{
    return true;
  }
}


function html($data)
{
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
	return $data;
}

function htmlout($text)
{
	echo html($text);
}

function validaData($data){  
  
  if(empty($data)){
    return false;
  }
  
  // se a data for menor que a data atual retorna falso
  if($data < date("Y-m-d")){
    return false;
  }

  $tempDate = explode('-', $data);
  if(checkdate($tempDate[1], $tempDate[2], $tempDate[0])){
    return true;
  } else {
    return false;
  }  
}






function formatadata($data){  
  $result = date('d/m/Y', strtotime($data));    
  return $result;
}


function validaemail($email){
  if(filter_var($email, FILTER_VALIDATE_EMAIL)){
    return true;
  } else {
    return false;
  }
  

}


function RandomPassword($length = 6){
  $chars = "0123456789bcdfghjkmnpqrstvwxyzBCDFGHJKLMNPQRSTVWXYZ";
  return substr(str_shuffle($chars),0,$length);
}


//função para verificar conteúdo de dados
function debug($data){
  echo("<pre>");
  print_r($data);
  echo("</pre>");
  die();
}



function retornaClasseFase($fase){
  switch ($fase){
    case 'ABERTO': 
      return "badge badge-success";
      break;
    case 'FECHADO':      
      return "badge badge-danger";
      break;
    case 'CANCELADO';     
      return "badge badge-warning";
      break;
      case 'CERTIFICADO';      
      return "badge badge-primary";
      break;
    case 'ARQUIVADO';      
      return "badge badge-secondary";
      break;
  }
}






?>