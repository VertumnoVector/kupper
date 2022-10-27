<?php
  require_once 'config.php';
  require_once 'db.php';
  require_once 'fdpf/fpdf.php';



  //impressao do bilhete
  class PDF extends FPDF
{
// Page header
function Header()
{
    $id = $_GET['id'];

    $db = new Database;
    $data = $db->readOne($id);
  
    $encode = json_encode($data);
  
    $json = json_decode($encode,true);
    $ticket = $json['id'];
    $nome = $json['nome'];
    $valor = $json['valor'];
    $natureza = $json['natureza'];
    $data = $json['created_at'];

    $dataFormat = date_create($data);


    //VIA DO CLUBE
    // Logo
    $this->Image('img/banner2.png',11,150,18,30);
    $this->Rect(10,150,20,36);

    //PRIMEIRO CABEÇALHO
    $this->SetFont('Helvetica','B',16);
    $this->SetX(30);
    $this->MultiCell(110,6,'GREMIO BENEFICENTE ESPORTIVO E RECREATIVO ANTÔNIO JOÃO   CNPJ: 03.208.733/0001-04','LTR','C',false);
    
    $this->SetX(30);
    $this->SetFont('Helvetica','',12);
    $this->MultiCell(110,6,'Rua Montese, 31 - Miguel Sutil  -  Vila Militar CEP: 78040-941  -  Cuiabá  -  Matogrosso FONES: (65) 6321-2174 / 99284-6999','LBR','C',false);
   

    //SEGUNDO CABEÇALHO
    $this->SetFont('Helvetica','B',14);
    $this->SetXY(140,10);
    $this->MultiCell(60,6,'Recibo de: RECEBIMENTO',1,'C',false);

    $this->SetTextColor(255,0,0);
    $this->SetFont('Helvetica','B',20);
    $this->SetXY(140,22);
    $this->MultiCell(60,8,$valor,1,'C',false);

    $this->SetTextColor(0,0,0);
    $this->SetFont('Helvetica','B',12);
    $this->SetXY(140,30);
    $this->MultiCell(60,16,'Recibo Nr: '.$ticket,1,'C',false);

    //CORPO
    $this->SetXY(10,46);
    $this->MultiCell(190,10,'RECEBIMENTO DE: ','LR','L',false);
    $this->SetFont('Helvetica','',12);
    $this->SetXY(53,48);
    $this->Write(6, $nome);

    $this->SetFont('Helvetica','B',12);
    $this->SetXY(10,53);
    $this->MultiCell(190,16,'Referente a: ','LRB','L',false);
    $this->SetFont('Helvetica','',12);
    $this->SetXY(37,58);
    $this->Write(6, $natureza);

    //RODAPE
    $this->SetXY(10,63);
    $this->SetFont('Helvetica','',10);
    $this->MultiCell(190,16,'Por ser verdade assino o presente recibdo','LR','C',false);
    $this->SetFont('Helvetica','',12);
    $this->SetXY(10,70);
    $this->MultiCell(190,16,'( )Dinheiro','LR','L',false);
    $this->SetXY(10,80);
    $this->MultiCell(190,16,'( )Débito','LR','L',false);
    $this->SetXY(10,90);
    $this->MultiCell(190,16,'( )Crédito','LR','L',false);
    $this->SetXY(10,100);
    $this->MultiCell(190,16,'( )Cheque Nr_______               DATA: '.date_format($dataFormat,'d/m/Y').'          __________________________________','LR','L',false);
    $this->SetFont('Helvetica','',10);
    $this->SetXY(10,105);
    $this->MultiCell(190,16,$nome.'               ','LRB','R',false);
    $this->SetXY(10,115);
    $this->MultiCell(190,16,'Via do Clube',0,'R',false);

    //break Via do Cliente
    $this->Image('img/tesoura.png',7,135,10,10);
    $this->Line(10,140,200,140);

    //via do cliente
    // Logo
    $this->Image('img/banner2.png',11,12,18,30);
    $this->Rect(10,10,20,36);

    //PRIMEIRO CABEÇALHO
    $this->SetFont('Helvetica','B',16);
    $this->SetXY(30,150);
    $this->MultiCell(110,6,'GREMIO BENEFICENTE ESPORTIVO E RECREATIVO ANTÔNIO JOÃO   CNPJ: 03.208.733/0001-04','LTR','C',false);
    
    $this->SetX(30);
    $this->SetFont('Helvetica','',12);
    $this->MultiCell(110,6,'Rua Montese, 31 - Miguel Sutil  -  Vila Militar CEP: 78040-941  -  Cuiabá  -  Matogrosso FONES: (65) 6321-2174 / 99284-6999','LBR','C',false);
   

    //SEGUNDO CABEÇALHO
    $this->SetFont('Helvetica','B',14);
    $this->SetXY(140,150);
    $this->MultiCell(60,6,'Recibo de: RECEBIMENTO',1,'C',false);

    $this->SetTextColor(255,0,0);
    $this->SetFont('Helvetica','B',20);
    $this->SetXY(140,162);
    $this->MultiCell(60,8,$valor,1,'C',false);

    $this->SetTextColor(0,0,0);
    $this->SetFont('Helvetica','B',12);
    $this->SetXY(140,170);
    $this->MultiCell(60,16,'Recibo Nr: '.$ticket,1,'C',false);

    //CORPO
    $this->SetXY(10,185);
    $this->MultiCell(190,10,'RECEBIMENTO DE: ','LR','L',false);
    $this->SetFont('Helvetica','',12);
    $this->SetXY(53,187);
    $this->Write(6, $nome);

    $this->SetFont('Helvetica','B',12);
    $this->SetXY(10,190);
    $this->MultiCell(190,16,'Referente a: ','LRB','L',false);
    $this->SetFont('Helvetica','',12);
    $this->SetXY(37,195);
    $this->Write(6, $natureza);

    //RODAPE
    $this->SetXY(10,200);
    $this->SetFont('Helvetica','',10);
    $this->MultiCell(190,16,'Por ser verdade assino o presente recibdo','LR','C',false);
    $this->SetFont('Helvetica','',12);
    $this->SetXY(10,210);
    $this->MultiCell(190,16,'( )Dinheiro','LR','L',false);
    $this->SetXY(10,220);
    $this->MultiCell(190,16,'( )Débito','LR','L',false);
    $this->SetXY(10,230);
    $this->MultiCell(190,16,'( )Crédito','LR','L',false);
    $this->SetXY(10,240);
    $this->MultiCell(190,16,'( )Cheque Nr_______               DATA: '.date_format($dataFormat,'d-m-Y').'          __________________________________','LR','L',false);
    $this->SetFont('Helvetica','',10);
    $this->SetXY(10,245);
    $this->MultiCell(190,16,$nome.'               ','LRB','R',false);
    $this->SetXY(10,255);
    $this->MultiCell(190,16,'Via do cliente',0,'R',false);

    //break Via do Cliente
    $this->Image('img/tesoura.png',7,135,10,10);
    $this->Line(10,140,200,140);


}

// Page footer
function Footer()
{
    // Position at 1.5 cm from bottom
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('Helvetica','I',8);
    // Page number
    $this->Cell(0,10,'',0,0,'L');
}
}

// Instanciation of inherited class
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Helvetica','',12);

$pdf->Output();

?>