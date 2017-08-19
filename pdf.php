<?php
require_once('lib/fpdf16/fpdf.php');
/**
 * Class PDF
 */

require('Functions.php');
$function = new Functionality();

class PDF extends FPDF
{
    /**
     *
     */
    function Header()
    {
        if(!empty($_FILES["file"]))
        {
            $uploaddir = "image/logo.jpg";
            $nm = $_FILES["file"]["name"];
            $random = rand(1,99);
            move_uploaded_file($_FILES["file"]["tmp_name"], $uploaddir.$random.$nm);
            $this->Image($uploaddir.$random.$nm,10,10,20);
            unlink($uploaddir.$random.$nm);
        }
        $this->SetFont('Arial','B',12);
        $this->Ln(1);
    }
    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial','I',8);
        $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
    }

    /**
     * @param $num
     * @param $label
     */
    function ChapterTitle($num, $label)
    {
        $this->SetFont('Arial','',12);
        $this->SetFillColor(200,220,255);
        $this->Cell(0,6,"$num $label",0,1,'L',true);
        $this->Ln(0);
    }

    /**
     * @param $num
     * @param $label
     */
    function ChapterTitle2($num, $label)
    {
        $this->SetFont('Arial','',12);
        $this->SetFillColor(249,249,249);
        $this->Cell(0,6,"$num $label",0,1,'L',true);
        $this->Ln(0);
    }
}

/**
 * Handle download tweet in pdf format request...
 */
if(isset($_REQUEST['download-pdf'])){
    ob_clean();
    $filename='image/temp.pdf';
    $screen_name = $_REQUEST['username'];
    $tweets = $function->generatePDFTweet($screen_name);
    $columns = array(array("name" => "Tweets","width" => 190));
    $pdf = new PDF();
    $pdf->AddPage();
    //Heading
    $pdf->SetFont('Times','',20);
    $pdf->SetTextColor(30);
    $pdf->Cell(0,3,$screen_name."'s tweet",0,1,'C');
    $pdf->Ln();
    $pdf->SetFillColor(232, 232, 232);
    $pdf->SetFont('Arial', 'B', 8);
    foreach ($columns as $column)
    {
        $pdf->Cell($column['width'], 6, strtoupper($column['name']), 1, 0, 'L', 1);
    }
    $pdf->Ln();
    $pdf->Ln();
    $pdf->SetFont('Arial', '', 10);
    foreach ($tweets as $tweet)
    {
        foreach ($columns as $column)
        {
            $pdf->MultiCell($column['width'], 6,$tweet['no'].'->'.$tweet['text'], 1);
        }
    }
    $pdf->Output($filename,'F');
    header("Content-type:  text/pdf");
    header("Content-Disposition: attachment; filename=".$screen_name."'s tweet.pdf");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    readfile($filename);
    //unlink($filename);
    exit;
}
?>