<?php
    require_once "../config.php";
    require_once "../inc/pdf.php";
    require_once DBAPI;

    $usuarios = null;
    $usuario = null;

    //Listagem de Usuarios

    function index() {
        global $usuarios;
       if (!empty($_POST["users"])) {
             $usuarios = filter("usuarios", "nome like '%{$_POST["users"]}%';");
        } else {
            $usuarios = find_all("usuarios");
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

    //Cadastro de usuarios

    function add() {

        if (!empty($_POST['usuario'])) { 
            try {
                $usuario = $_POST['usuario']; //vira vetor automaticamente
                $usuario["foto"] = "";
                    
                if (!empty($usuario["'password'"])) {
                    $password2 = $_POST["password2"];
                    if($usuario["'password'"] == $password2 ) {
                        $usuario["'password'"] = password_hash($usuario["'password'"], PASSWORD_DEFAULT); //hash 
                    } else {
                        throw new Exception("Erro ao cadastrar o usuario: Verifique a senha!");
                    }
                }


                if (!empty($_FILES["foto"]["name"])) {
                    //upload da foto
                    $pastadestino = "fotos/"; //é relativo
                    $arquivodestino = $pastadestino . basename($_FILES["foto"]["name"]); //caminho completo ate o arquivo que sera gravado
                    $nomearquivo = basename($_FILES["foto"]["name"]); //nome do arquivo
                    $resolucaoarquivo = getimagesize($_FILES["foto"]["tmp_name"]); //tmp_name é o nome armazendo no servidor, essa fun~ção getimageisze gera um vetor com o tamanho
                    $tamanhoarquivo = $_FILES["foto"]["size"]; //tamanho do arquivo em bytes
                    $nometemp = $_FILES["foto"]["tmp_name"]; //nome e caminho do arquivo no servidor
                    $tipoarquivo = strtolower(pathinfo($arquivodestino, PATHINFO_EXTENSION)); //extensao do arquivo
                    
                    //chamada da função upload para gravar a imagem
                    upload($pastadestino, $arquivodestino, $tipoarquivo, $nometemp, $tamanhoarquivo);

                    $usuario["foto"] = $nomearquivo;
                }


                save("usuarios", $usuario);
                header("Location: index.php");

            } catch (Exception $e) {
                $_SESSION["message"] = "Aconteceu um erro: " . $e->getMessage();
                $_SESSION["type"] = "danger";
            }
        }
    }

    //Atualizacao/Edicao de usuario

    function edit() {

        $now = new DateTime("now", new DateTimeZone("America/Sao_Paulo"));

        if (isset($_GET['id'])) {

            $id = $_GET['id'];

            if (isset($_POST['usuario'])) {
                
                $usuario = $_POST['usuario'];

                $currentUsuario = find("usuarios", $id);

                // criptografa a senha se foi preenchida
                if (!empty($usuario['password'])) {
                    $usuario['password'] = password_hash($usuario['password'], PASSWORD_DEFAULT);
                } else {
                    unset($usuario['password']); // Não atualiza a senha se estiver vazia
                }

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
                        if (!empty($currentUsuario['foto']) && file_exists($target_dir . $currentUsuario['foto'])) {
                            unlink($target_dir . $currentUsuario['foto']);
                        }

                        // faz upload da nova imagem
                        move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file);
                        $usuario['foto'] = basename($_FILES["foto"]["name"]);
                    }
                }

                else {
                    $usuario['foto'] = $currentUsuario['foto'];
                }

                // faz o update independentemente de ter foto nova ou não
                update("usuarios", $id, $usuario);
                header("location: index.php");
                exit;
            } else {

                global $usuario;
                $usuario = find("usuarios", $id);
            } 
        } else {
            header('location: index.php');
            exit;
        }
    }

    //Visualização de um usuario

    function view($id = null) {
        global $usuario;
        $usuario = find("usuarios", $id);
    }

    //Exclusão de um usuario

    function delete($id = null) {

        global $usuarios;
        $usuarios = remove("usuarios", $id);

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
        $usuarios = null;

        if ($p) {
            $usuarios = filter("usuarios", "nome like '%" . $p . "%'");
        } else {
            $usuarios = find_all ("usuarios");
        }

        // Converte todos os campos de todos os usuários de uma vez
        foreach ($usuarios as &$usuario) { // &$usuario = modifica o original
            foreach ($usuario as $campo => $valor) {
                $usuario[$campo] = converteTexto($valor ?? "");
            }
        }

        unset($usuario);
        $pdf->SetLeftMargin(0); //zera a margem padrao do fpdf
        $pdf->SetX(0); 
        $pdf->SetY(24);
        $pdf->SetFillColor(33, 37, 41);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFont("Arial", "B", 14);
        $pdf->Cell(210, 10, converteTexto("Listagem de Usuários"), 0, 1, "C", true);
        $pdf->Ln(5);
        $pdf->SetLeftMargin(10);


        //cabeçaljo
        $pdf->SetTextColor(0, 0, 0);
        
        $pdf->SetFont("Arial", "B", 10);
        $pdf->SetX(22); 
        $pdf->SetFont("Arial", "B", 10);
        $pdf->SetFillColor(170, 162, 245);
        $pdf->SetDrawColor(61, 53, 133);
        $pdf->Cell(40, 10, "ID",    1, 0, "C", true);
        $pdf->Cell(40, 10, "Nome",  1, 0, "C", true);
        $pdf->Cell(40, 10, "User",  1, 0, "C", true);
        $pdf->Cell(40, 10, "Foto",  1, 1, "C", true);

        $pdf->SetDrawColor(0, 0, 0);

        foreach ($usuarios as $usuario) { 
            
            $alturaLinha = 40;

            if ($pdf->GetY() + $alturaLinha > $pdf->GetPageHeight() - 20) {
                $pdf->AddPage();
                // Repete o cabeçalho na nova página
                $pdf->SetDrawColor(61, 53, 133);
                $pdf->SetX(22);
                $pdf->SetFont("Arial", "B", 10);
                $pdf->Cell(40, 10, "ID",      1, 0, "C", true);
                $pdf->Cell(40, 10, "Nome", 1, 0, "C", true);
                $pdf->Cell(40, 10, "User", 1, 0, "C", true);
                $pdf->Cell(40, 10, "Foto",    1, 1, "C", true);
            }
            
            $pdf->SetDrawColor(61, 53, 133);
            $pdf->SetX(22);
            $y = $pdf->GetY();

            $pdf->Cell(40, $alturaLinha, $usuario['id'] , 1, 0, "C");
            $pdf->Cell(40, $alturaLinha, converteTexto($usuario['nome']) , 1, 0, "C");
            $pdf->Cell(40, $alturaLinha, converteTexto($usuario['user']) , 1, 0, "C");
            //$pdf->Image("../imagens/" . $usuario['foto'], 10, 6, 13);
            
            $xfoto = $pdf->GetX(); //pega a posição anterior
            $pdf->Cell(40, $alturaLinha, "", 1, 1, "C"); // celula para encaixar a foto

            //$caminho = $_SERVER['DOCUMENT_ROOT'] . "/pw3_2bim/imagens/" . $usuario['foto']; //caminho pq tinha dado problema
            $comfoto = $_SERVER['DOCUMENT_ROOT'] . "/pw3_2bim/usuarios/fotos/" . $usuario['foto'];
            $semfoto = $_SERVER['DOCUMENT_ROOT'] . "/pw3_2bim/usuarios/fotos/semimagem.jpg";

            if (!empty($usuario['foto']) && file_exists($comfoto)) {
                $pdf->Image($comfoto, $xfoto + 6, $y + 6, 26, 0);
            } elseif (file_exists($semfoto)) {
                $pdf->Image($semfoto, $xfoto + 6, $y + 6, 26, 0);
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