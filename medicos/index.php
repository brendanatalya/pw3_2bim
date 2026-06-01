<?php
    require_once("functions.php");

    if (isset($_GET["pdf"])) { //acrescentado para gerar pdf
        if ($_GET["pdf"] == "ok") {
            pdf();
        } else {
            pdf($_GET["pdf"]);
        }
    }

    index();
    include(HEADER_TEMPLATE);
?>
        <header>
            <div class="row">
                <div class="col-sm-6">
                    <h2>Médicos</h2>
                </div>
                <div class="col-sm-6 text-right h2">
                    <?php if (isset($_SESSION["user"])) : //verifica se existe usuario logado?>
                        <a class="btn btn-info" href="add.php"><i class="fa-solid fa-user-plus"></i> Novo Médico</a>
                    <?php endif;?>
                    <?php if ($_SERVER["REQUEST_METHOD"] == "POST") : ?>
                    <a class="btn btn-dark" href="index.php?pdf=<?php echo $_POST["doctors"]; ?>" download><i class="fa-solid fa-file-pdf"></i> Listagem</a>
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
                            <input type="search" class="form-control" name="doctors" maxlength="50" required>
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
                        <th>CRM</th>
                        <th>Foto</th>
                        <th>Atualizado em</th>
                        <th>Opções</th>
                    </tr>
                </thead>
                <tbody>
                <?php if ($medicos) : ?>
                <?php foreach ($medicos as $medico) : ?>
                    <tr>
                        <td><?php echo $medico['id']; ?></td>
                        <td><?php echo $medico['nome']; ?></td>
                        <td><?php echo $medico['crm']; ?></td>
                        <td><?php 
                                if (!empty($medico["foto"])) {
                                    echo "<a href=\"view.php?id=" . $medico['id'] . "\">
                                            <img src=\"fotos/" . $medico["foto"] . "\" class=\"img-thumbnail shadow\" width=\"150px\">
                                        </a>";
                                } else {
                                    echo "<a href=\"view.php?id=" . $medico['id'] . "\">
                                            <img src=\"fotos/semimagem.jpg\" class=\"img-thumbnail shadow\" width=\"150px\">
                                        </a>";
                                }
                            ?>
                        </td>
                        <td><?php echo formatadata($medico['modified'], "d/m/Y - H:i:s"); ?></td>
                        <td class="actions text-right botao">
                            <a href="view.php?id=<?php echo $medico['id']; ?>" class="btn btn-sm btn-dark diminuir mb-2"><i class="fa-solid fa-eye"></i> Visualizar</a>
                             <?php if (isset($_SESSION["user"])) : //verifica se existe usuario logado?>
                                <a href="edit.php?id=<?php echo $medico['id']; ?>" class="btn btn-sm btn-secondary diminuir mb-2"><i class="fa-solid fa-file-medical"></i> Editar</a>
                                <a href="#" class="btn btn-sm btn-light diminuir mb-2" data-bs-toggle="modal" data-bs-target="#modalmedico" data-customer="<?php echo $medico['id']; ?>">
                                    <i class="fa-solid fa-trash-can"></i> Excluir
                                </a>
                            <?php endif;?>
                        </td>
                    </tr>
                <?php
                    endforeach;
                    else :
                ?>
                    <tr>
                        <td colspan="6">Nenhum registro encontrado.</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
<?php
    include "modal.php"; 
    include(FOOTER_TEMPLATE); 
?>