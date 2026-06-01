<?php 
	include "functions.php";
	view($_GET['id']);
    include (HEADER_TEMPLATE);
?>

    <h2>Revista <?php echo $revista['id']; ?></h2>
    <hr>

    <?php if (!empty($_SESSION['message'])) : ?>
        <div class="alert alert-<?php echo $_SESSION['type']; ?>"><?php echo $_SESSION['message']; ?></div>
    <?php endif; ?>

    <dl class="dl-horizontal">
        <dt>Editora:</dt>
        <dd><?php echo $revista['editora']; ?></dd>

        <dt>Edição:</dt>
        <dd><?php echo $revista['edicao']; ?></dd>

        <dd>
            <?php 
                if (!empty($revista["foto"])) {
                    echo "<img src=\"fotos/" . $revista["foto"] . "\" class=\"fotomedico\">";
                } else {
                    echo "<img src=\"fotos/semimagem.jpg\" class=\"fotomedico\">";
                }
            ?>
        </dd>

        <dt>Data de Lançamento:</dt>
        <dd><?php echo formatadata($revista['lanc'], "d/m/Y"); ?></dd>
    </dl>

    <dl class="dl-horizontal">
        <dt>Endereço:</dt>
        <dd><?php echo $revista['endereco']; ?></dd>

        <dt>Bairro:</dt>
        <dd><?php echo $revista['bairro']; ?></dd>

        <dt>CEP:</dt>
        <dd><?php echo formatacep($revista['cep']); ?></dd>

        <dt>Data de Cadastro:</dt>
        <dd><?php echo formatadata($revista['created'], "d/m/Y - H:i:s"); ?></dd>

        <dt>Data da última atualização:</dt>
        <dd><?php echo formatadata($revista['modified'], "d/m/Y - H:i:s"); ?></dd>
    </dl>

    <dl class="dl-horizontal">
        <dt>Cidade:</dt>
        <dd><?php echo $revista['cidade']; ?></dd>

        <dt>Telefone:</dt>
        <dd><?php echo formatatel($revista['telefone']); ?></dd>

        <dt>Celular:</dt>
        <dd><?php echo formatatel($revista['celular']); ?></dd>

        <dt>UF:</dt>
        <dd><?php echo $revista['estado']; ?></dd>
    </dl>

    <div id="actions" class="row">
        <div class="col-md-12">
             <?php if (isset($_SESSION["user"])) : //verifica se existe usuario logado?>
                <a href="edit.php?id=<?php echo $revista['id']; ?>" class="btn btn-danger"><i class="fa-solid fa-file-medical"></i> Editar</a>
            <?php endif;?>
            <a href="index.php" class="btn btn-dark"><i class="fa-solid fa-rotate-left"></i> Voltar</a>
        </div>
    </div>

<?php include(FOOTER_TEMPLATE); ?>