<?php
    require_once("functions.php");
    if (!isset($_SESSION)) session_start();
    if (isset($_SESSION["user"])) { //verifica se tem um usuaro logado
        if ($_SESSION["user"] != "admin") { // verifica se o usuario é admin
            $_SESSION["message"] = "Você precisa ser administrador para acessar esse recurso!";
            $_SESSION["type"] = "danger";
        }
    } else {
        $_SESSION["message"] = "Você precisa estar logado e ser administrador para acessar esse recurso!";
        $_SESSION["type"] = "danger";
    }

    if (isset($_GET["pdf"])) { //acrescentado para gerar pdf
        if ($_GET["pdf"] == "ok") {
            pdf();
        } else {
            pdf($_GET["pdf"]);
        }
    }
    
    index();
    include(HEADER_TEMPLATE);

    if (isset($_SESSION["user"]) && $_SESSION["user"] == "admin") : 

?>
        <header>
            <div class="row">
                <div class="col-sm-6">
                    <h2>Usuários</h2>
                </div>
                <div class="col-sm-6 text-right h2">
                    <a class="btn btn-info" href="add.php"><i class="fa-solid fa-user-gear"></i> Novo Usuário</a>
                    <?php if ($_SERVER["REQUEST_METHOD"] == "POST") : ?>
                    <a class="btn btn-dark" href="index.php?pdf=<?php echo $_POST["users"]; ?>" download><i class="fa-solid fa-file-pdf"></i> Listagem</a>
                    <?php else : ?>
                    <a class="btn btn-dark" href="index.php?pdf=ok" download><i class="fa-solid fa-file-pdf"></i> Listagem</a>
                    <?php endif;?>
                    <a class="btn btn-dark" href="index.php"><i class="fa-solid fa-retweet"></i> Atualizar</a>
                </div>
            </div>

            <div class="row">
                <form name="filtro" action="index.php" method="post">
                    <div class="row">
                        <div class="input-group mb-2">
                            <input type="search" class="form-control" name="users" maxlength="50" required>
                            <button type="submit" class="btn btn-secondary"> <i class="fa-solid fa-magnifying-glass"></i> Pesquisar</button>
                        </div>                        
                    </div>
                </form>
            </div>
        </header>
        <hr>

        <div class="tabelaresponsiva">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th width="30%">Nome</th>
                        <th>Login</th>
                        <th>Foto</th>
                        <th>Opções</th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($usuarios) : ?>
                <?php foreach ($usuarios as $usuario) : ?>
                    <tr>
                        <td><?php echo $usuario['id']; ?></td>
                        <td><?php echo $usuario['nome']; ?></td>
                        <td><?php echo $usuario['user']; ?></td>

                        <td><?php 
                                if (!empty($usuario["foto"])) {
                                    echo "<a href=\"view.php?id=" . $usuario['id'] . "\">
                                            <img src=\"fotos/" . $usuario["foto"] . "\" class=\"img-thumbnail shadow\" width=\"150px\">
                                        </a>";
                                } else {
                                    echo "<a href=\"view.php?id=" . $usuario['id'] . "\">
                                            <img src=\"fotos/semimagem.jpg\" class=\"img-thumbnail shadow\" width=\"150px\">
                                        </a>";
                                }
                            ?>
                        </td>

                        <td class="actions text-right botao">
                            <a href="view.php?id=<?php echo $usuario['id']; ?>" class="btn btn-sm btn-dark diminuir mb-2"><i class="fa-solid fa-eye"></i> Visualizar</a>
                            <a href="edit.php?id=<?php echo $usuario['id']; ?>" class="btn btn-sm btn-secondary diminuir mb-2"><i class="fa-solid fa-file-medical"></i> Editar</a>
                            <a href="#" class="btn btn-sm btn-light diminuir mb-2" data-bs-toggle="modal" data-bs-target="#delete-usuario" data-customer="<?php echo $usuario['id']; ?>">
                                <i class="fa-solid fa-trash-can"></i> Excluir
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="5">Nenhum registro encontrado.</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>

<?php
    endif; //fim do if que verifica se o usuario é admin
    if (!empty($_SESSION["message"])) :
?>
    <div class="alert alert-<?php echo $_SESSION["type"]; ?> alert-dismissible" role="alert">                
        <?php echo $_SESSION["message"]; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div> 

<?php
    endif; 
    include "modal.php"; 
    include(FOOTER_TEMPLATE); 
?>