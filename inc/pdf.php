<?php 
    require("fpdf.php");

    class PDF extends FPDF {
        //header da pagina

        function Header() {

            $this->SetFillColor(33, 37, 41);
            $this->SetDrawColor(119, 109, 213);
            $this->Rect(0, 0, 210, 28, "F");
            //logo
            $this->Image("../imagens/icon.png", 70, 8, 12);
            //fonte: Arial vold 15
            $this->SetFont("Arial", "B", 20);
            //move to the right
            //$this->Cell(40);
            //titulo
            $this->setTextColor(255, 255, 255);
            $this->Cell(195, 10, iconv("UTF-8", "ISO-8859-1", "Gem Company"), "B", 0, "C");
            //quebra de linha
            $this->Ln(20);
        }

        function Footer () {

            //posicao a 1.5 cm do fim da pagina
            $this->SetY(-15);
            $this->SetFillColor(33, 37, 41); // mesma cor do header
            $this->Rect(0, $this->GetY(), 210, 20, "F");

            $this->SetDrawColor(100, 150, 180); // azul um pouco mais escuro
            $this->Line(10, $this->GetY(), 200, $this->GetY());

            //arial iatlic 8
            $this->SetFont("Arial", "I", 8);
            //numero da pagina
            $this->setTextColor(255, 255, 255);
            $this->Cell(0, 10, converteTexto("Página ") . $this->PageNo() . " de {nb}", 0, 0, "C");
        }
    }

    function converteTexto($texto) {
        // Detecta o encoding e converte corretamente
        if (mb_detect_encoding($texto, 'UTF-8', true)) {
            return iconv("UTF-8", "ISO-8859-1//TRANSLIT//IGNORE", $texto);
        }
        return $texto; // já está em ISO-8859-1, não precisa converter
    }
/*
    function converteTexto($str) {
        $str = iconv("UTF-8", "windows-1252", $str);
        return $str;
    }
*/
?>