<?php

require APPROOT . '/inc/fpdf/fpdf.php'; 


//die(var_dump($data));

//echo('Curso mano' . $data['curso']->nome_curso);
//die();
class PDF extends FPDF
{            
            
            // Page header
            function Header()
            {   $currentdate = date("d-m-Y");
                // Logo
                $this->Image(APPROOT . '/views/relatorios/logo.png',10,6,110);
                // Date
                $this->SetFont('Arial','B',10); 
                $this->Cell(80);
                $this->Cell(180,10, utf8_decode('Data de impressão:' . $currentdate),0,0,'C');                
                // Arial bold 15
                $this->SetFont('Arial','B',15);    
                // Title
                $this->Ln(20);
                // Move to the right
                $this->Cell(80);
                $this->Cell(30,10, utf8_decode("Livro de Registros"),0,0,'C');
                $this->Ln(10);                            
            }

            // Page footer
            function Footer()
            {
                // Position at 1.5 cm from bottom
                $this->SetY(-15);
                // Arial italic 8
                $this->SetFont('Arial','I',8);
                // Page number
                $this->Cell(0,10,utf8_decode('Página ').$this->PageNo(),0,0,'C');                
            }
}


// Instanciation of inherited class
$pdf = new PDF();
//define o tipo e o tamanho da fonte                                  
$pdf->SetFont('Arial','B',8);
//defino as colunas do relatório
$colunas =array("N","Nome","C/H FR","N° REG","Data","Ass");
//largura das colunas
$larguracoll = array(1 => 8, 2 => 110, 3 => 12, 4 => 20, 5 => 20, 6 => 27);
//tamanho da fonte
$left = 10; 


//defino a variável escola como em branco pois depois faço a verificação se for diferente da escola do array crio uma nova página
//para a outra escola 
$escola="";
$countescola=1; 
$countgeral=0;


$pdf->AddPage('P');
//SETA A FONTE PARA TAMANHO 8 NEGRITO
$pdf->SetFont('Arial','B',12);
$pdf->Cell(0, 5,utf8_decode($data['curso']->nome_curso), 0, 1, "C");
$countescola=1;                       
$pdf->Ln();
$i=0;
foreach($colunas as $coluna){
    $i++;
    $pdf->SetFont('Arial','B',8);                   
    $pdf->Cell($larguracoll[$i],$left,utf8_decode($coluna),1);
}

$pdf->Ln(); 
$count=0;          
if(!empty($data['inscritos'])){
    foreach($data['inscritos'] as $row){  
        $chPresenca = 0;
        if($presencas = $this->inscricaoModel->getPresencasUsuarioById($row->user_id,$row->inscId)){
          foreach($presencas as $pres){
            $chPresenca += $pres->carga_horaria_tema;
          } 
          $count++;
          $pdf->SetFont('Arial','B',8);
          $pdf->Cell($larguracoll[1],$left,utf8_decode($count),1);                     
          $pdf->Cell($larguracoll[2],$left,utf8_decode(mb_strtoupper($row->name)),1); 
          $pdf->Cell($larguracoll[3],$left,utf8_decode($chPresenca . "h"),1);
          $pdf->Cell($larguracoll[4],$left," ",1);
          $pdf->Cell($larguracoll[5],$left,"_____/_____",1);                                       
          $pdf->Cell($larguracoll[6],$left,"_______________",1);   
          
          //linha nova
          $pdf->Ln();  
        }
}//END FOREACH 
            
    $pdf->Ln();
    $pdf->SetFont('Arial','B',16);
    $pdf->Cell(40,10,"Total: ".utf8_decode($count),0,0,'C');

    if($pdf->Output())
    {
        $pdf->Output("Relatorio.pdf",'I');  
    }
    else{                
        echo $data['erro'] = $error;
        $this->view('relatorios/erroAoGerarRelatorio',$data);
    }  

} else{                
die('Sem dados para emitir!');
$this->view('relatorios/erroAoGerarRelatorio', $data); 
}       
?>
