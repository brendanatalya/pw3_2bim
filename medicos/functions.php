<?php

    require_once "../config.php";
    require_once "../inc/pdf.php";
    require_once DBAPI;

    $medicos = null;
    $medico = null;

    // Listagem de Medicos

    function index() {
        global $medicos;
        $medicos = find_all("medico");
        if (!empty($_POST["doctors"])) {
            $medicos = filter("medico", "nome like '%{$_POST["doctors"]}%'");
        } else {
            $medicos = find_all("medico");
        }
    }

    function upload ($pastadestino, $arquivodestino, $tipoarquivo, $nometemp, $tamanhoarquivo) {
        //upload da foto
        try {
            $nomearquivo = basename($arquivodestino); //nome do arquivo
            $uploadOk = 1;

            //verificando se o arquivo é uma imagem
            if (isset($_POST["submit"])) {
                $check = getimagesize($nometemp);
                if ($check !== false) {
                    $_SESSION["message"] = "O arquivo é uma imagem - " . $check["mime"] . ".";
                    $_SESSION["type"] = "info";

                    $uploadOk = 1;
                } else {
                    $uploadOk = 0;
                    throw new Exception("O arquivo não é uma imagem!");
                }
            }

            //verificando se o arquivo ja existe na pasta
            if (file_exists($arquivodestino)) {
                $uploadOk = 0;
                throw new Exception("Desculpe, o arquivo já existe!");
            }

            //verificando o tamanho do arquivo
            if ($tamanhoarquivo > 5000000) {
                $uploadOk = 0;
                throw new Exception("Desculpe, mas o arquivo é muito grande!");
            }

            //aceitando apenas certos tipos de formatos
            if ($tipoarquivo != "jpg" && $tipoarquivo != "png" && $tipoarquivo != "jpeg" && $tipoarquivo != "gif") {
                $uploadOk = 0;
                throw new Exception("Desculpe, mas so sao permitidos arquivos de imagem JPG, JPEG, PNG E GIF!");
            }

            //verificando se uploadok esta como 0 por algum erro
            if ($uploadOk == 0) {
                throw new Exception("Desculpe, mas o arquivo nao pode ser enviado");
            } else {
                //se tudo estiver certinho, tentamos fazer o upload do arquivo
                if (move_uploaded_file($_FILES["foto"]["tmp_name"], $arquivodestino)) {
                    //colocando o nome do arquivo da foto do usuario no vetor
                    $_SESSION["message"] = "O arquivo " . htmlspecialchars($nomearquivo) . " foi armazenado.";
                    $_SESSION["type"] = "success";
                } else {
                    throw new Exception("Desculpe, mas o arquivo nao pode ser enviado");
                }
            }

        } catch (Exception $e) {
            $_SESSION["message"] = "Aconteceu um erro: " . $e->getMessage();
            $_SESSION["type"] = "danger";
        }
    }


    //Cadastro de Medicos

    function add() {

        if (!empty($_POST['medico'])) { 
            try {
                $now = new DateTime("now", new DateTimeZone("America/Sao_Paulo"));

                $medico = $_POST['medico'];

                if (!empty($_FILES["foto"]["name"])) {
                    //upload da foto
                    $pastadestino = "fotos/";
                    $arquivodestino = $pastadestino . basename($_FILES["foto"]["name"]); //caminho compleyo ate o arquivo que sera gravado
                    $nomearquivo = basename($_FILES["foto"]["name"]); //nome do arquivo
                    $resolucaoarquivo = getimagesize($_FILES["foto"]["tmp_name"]); 
                    $tamanhoarquivo = $_FILES["foto"]["size"]; //tamanho do arquivo em bytes
                    $nometemp = $_FILES["foto"]["tmp_name"]; //nome e caminho do arquivo no servidor
                    $tipoarquivo = strtolower(pathinfo($arquivodestino, PATHINFO_EXTENSION)); //extensao do arquivo

                    //chamada da função upload para gravar a imagem
                    upload($pastadestino, $arquivodestino, $tipoarquivo, $nometemp, $tamanhoarquivo);

                    //$medico["foto"] = $nomearquivo;
                }

                else {
                    $nomearquivo = "";
                }

                $medico["foto"] = $nomearquivo;
                $medico['created'] = $now->format("Y-m-d H:i:s");
                $medico['modified'] = $now->format("Y-m-d H:i:s");
                save("medico", $medico); 
                header("Location: index.php");
                
            } catch (Exception $e) {
                $_SESSION["message"] = "Aconteceu um erro: " . $e->getMessage();
                $_SESSION["type"] = "danger";
            }
        }
    }

    //Atualizacao/Edicao de Medico

    function edit() {

        $now = new DateTime("now", new DateTimeZone("America/Sao_Paulo"));

        if (isset($_GET['id'])) {

            $id = $_GET['id'];

            if (isset($_POST['medico'])) {
                
                $medico = $_POST['medico'];
                $medico['modified'] = $now->format("Y-m-d H:i:s");

                $currentMedico = find("medico", $id);

                // SE o usuário enviou uma nova imagem
                if (!empty($_FILES["foto"]["tmp_name"])) {

                    $target_dir = "fotos/";
                    $target_file = $target_dir . basename($_FILES["foto"]["name"]);
                    $tipoarquivo = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                    $uploadOk = 1;

                    // valida se é imagem
                    $check = getimagesize($_FILES["foto"]["tmp_name"]);
                    if ($check === false) {
                        $uploadOk = 0;
                    }

                    // verifica se já existe
                    if (file_exists($target_file)) {
                        $uploadOk = 0;
                    }

                    // tamanho máximo
                    if ($_FILES["foto"]["size"] > 500000) {
                        $uploadOk = 0;
                    }

                    // extensões permitidas
                    if (!in_array($tipoarquivo, ['jpg','jpeg','png','gif'])) {
                        $uploadOk = 0;
                    }

                    // se ocorreu algum erro, cancela somente o upload
                    if ($uploadOk == 0) {
                        echo "<h4 class='concluido'>A imagem é inválida! Mas o restante foi atualizado normalmente.</h4>";
                    } 
                    
                    else {
                        // apaga foto antiga
                        if (!empty($currentMedico['foto']) && file_exists($target_dir . $currentMedico['foto'])) {
                            unlink($target_dir . $currentMedico['foto']);
                        }

                        // faz upload da nova imagem
                        move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file);
                        $medico['foto'] = basename($_FILES["foto"]["name"]);
                    }
                }

                else {
                    $medico['foto'] = $currentMedico['foto'];
                }

                // faz o update independentemente de ter foto nova ou não
                update("medico", $id, $medico);
                header("location: index.php");
                exit;
            } else {

                global $medico;
                $medico = find("medico", $id);
            } 
        } else {
            header('location: index.php');
            exit;
        }
    }

    //Visualização de um medico

    function view($id = null) {
        global $medico;
        $medico = find("medico", $id);
    }

    //Exclusão de um medico

    function delete($id = null) {

        global $medico;
        $medico = remove("medico", $id);

        header("location: index.php");
    }

    //formata data

    function formatadata($data, $formato) {
        $df = new DateTime($data, new DateTimeZone("America/Sao_Paulo")); 

        return $df->format($formato);

    }

    //formata telefone
    function formatatel($telefone) {
        
        $tel = "(". substr($telefone,0,2) . ")" . substr($telefone,2,5) . "-" . substr($telefone,7,4);
        return $tel;

    }

    //formata cep

    function formatacep($cep) {
        
        $ceppronto = substr($cep,0,5) . "-" . substr($cep,5,3);
        return $ceppronto;

    }

   /*gerando pdf */

    function pdf ($p = null) {
        //depois copio 
        $pdf = new PDF();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('Times', '', 12);
        $medicos = null;

        if ($p) {
            $medicos = filter("medico", "nome like '%" . $p . "%'");
        } else {
            $medicos = find_all ("medico");
        }

        // Converte todos os campos de todos os usuários de uma vez
        foreach ($medicos as &$medico) { // &$medico = modifica o original
            foreach ($medico as $campo => $valor) {
                $medico[$campo] = converteTexto($valor ?? "");
            }
        }

        unset($medico);
        $pdf->SetLeftMargin(0); //zera a margem padrao do fpdf
        $pdf->SetX(0); 
        $pdf->SetY(24);
        $pdf->SetFillColor(33, 37, 41);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFont("Arial", "B", 14);
        $pdf->Cell(210, 10, converteTexto("Listagem de Médicos"), 0, 1, "C", true);
        $pdf->Ln(5);
        $pdf->SetLeftMargin(10);


        //cabeçaljo
        $pdf->SetTextColor(0, 0, 0);
        
        $pdf->SetFont("Arial", "B", 10);
        $pdf->SetX(22); 
        $pdf->SetFont("Arial", "B", 10);
        $pdf->SetFillColor(146, 230, 247);
        $pdf->SetDrawColor(9, 133, 158);
        $pdf->Cell(40, 10, "ID",    1, 0, "C", true);
        $pdf->Cell(40, 10, "Nome",  1, 0, "C", true);
        $pdf->Cell(40, 10, "CRM",  1, 0, "C", true);
        $pdf->Cell(40, 10, "Foto",  1, 1, "C", true);

        $pdf->SetDrawColor(0, 0, 0);

        foreach ($medicos as $medico) { 
            
            $alturaLinha = 40;

            if ($pdf->GetY() + $alturaLinha > $pdf->GetPageHeight() - 20) {
                $pdf->AddPage();
                // Repete o cabeçalho na nova página
                $pdf->SetDrawColor(9, 133, 158);
                $pdf->SetX(22);
                $pdf->SetFont("Arial", "B", 10);
                $pdf->Cell(40, 10, "ID",      1, 0, "C", true);
                $pdf->Cell(40, 10, "Nome", 1, 0, "C", true);
                $pdf->Cell(40, 10, "CRM", 1, 0, "C", true);
                $pdf->Cell(40, 10, "Foto",    1, 1, "C", true);
            }
            
            $pdf->SetDrawColor(9, 133, 158);
            $pdf->SetX(22);
            $y = $pdf->GetY();

            $pdf->Cell(40, $alturaLinha, $medico['id'] , 1, 0, "C");
            $pdf->Cell(40, $alturaLinha, converteTexto($medico['nome']) , 1, 0, "C");
            $pdf->Cell(40, $alturaLinha, $medico['crm'] , 1, 0, "C");
            //$pdf->Image("../imagens/" . $medico['foto'], 10, 6, 13);
            
            $xfoto = $pdf->GetX(); //pega a posição anterior
            $pdf->Cell(40, $alturaLinha, "", 1, 1, "C"); // celula para encaixar a foto

            //$caminho = $_SERVER['DOCUMENT_ROOT'] . "/pw3_2bim/imagens/" . $medico['foto']; //caminho pq tinha dado problema
            $comfoto = $_SERVER['DOCUMENT_ROOT'] . "/pw3_2bim/medicos/fotos/" . $medico['foto'];
            $semfoto = $_SERVER['DOCUMENT_ROOT'] . "/pw3_2bim/medicos/fotos/semimagem.jpg";

            if (!empty($medico['foto']) && file_exists($comfoto)) {
                $pdf->Image($comfoto, $xfoto + 8, $y + 2, 26, 0);
            } elseif (file_exists($semfoto)) {
                $pdf->Image($semfoto, $xfoto + 8, $y + 2, 26, 0);
            }

            //$pdf->Image($comfoto, $x + 2, $y + 2, 26, 16);
            // Reposiciona o cursor após a célula da imagem
            //$pdf->SetXY($x + 40, $y);
        
            
        }

        /*
        for ($i=1; $i<=40;$i++)
            $pdf->Cell(0, 10, 'Printing line number ' . $i, 0, 1);
        */
        $pdf->Output();
    }