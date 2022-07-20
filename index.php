<?php

require_once "init.php";

use Classes\Tarefa;

$tarefa = new Tarefa();

if (isset($_POST['descricao'])) {
    $tarefa->id = filter_input(INPUT_POST, "id", FILTER_SANITIZE_NUMBER_INT);
    $tarefa->descricao = filter_input(INPUT_POST, "descricao", FILTER_SANITIZE_ADD_SLASHES);
    $tarefa->concluida = filter_input(INPUT_POST, "concluida", FILTER_SANITIZE_ADD_SLASHES) == 'on' ? 1 : 0;

    $msgValidacao = $tarefa->validar();
    if (!$msgValidacao) {
        $tarefa->salvar();
    } else {
        $_SESSION['msg'] = $msgValidacao;
    }
    header('Location: ' . BASE_URL);
    die;
}

if (isset($_GET['editar'])) {
    $id = filter_input(INPUT_GET, "editar", FILTER_SANITIZE_NUMBER_INT);
    $tarefa->buscar($id);
}

if (isset($_GET['concluir'])) {
    $id = filter_input(INPUT_GET, "concluir", FILTER_SANITIZE_NUMBER_INT);
    $tarefa->buscar($id);
    $tarefa->concluida = 1;
    $tarefa->salvar();
    header('Location: ' . BASE_URL);
    die;
}

if (isset($_GET['refazer'])) {
    $id = filter_input(INPUT_GET, "refazer", FILTER_SANITIZE_NUMBER_INT);
    $tarefa->buscar($id);
    $tarefa->concluida = 0;
    $tarefa->salvar();
    header('Location: ' . BASE_URL);
    die;
}

if (isset($_GET['excluir'])) {
    $id = filter_input(INPUT_GET, "excluir", FILTER_SANITIZE_NUMBER_INT);
    $tarefa->excluir($id);
    header('Location: ' . BASE_URL);
    die;
}


$tarefas = $tarefa->lista();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE-edge">
    <meta name="viewport" content="width=device-widtg, intial-scale=1.0">
    <link rel="stylesheet" href="css/estilo.css">
    <title> Lista de Tarefas</title>
</head>

<body>
    <main>
        <div class="formulario">
            <form method="POST">
                <input type="hidden" name="id" value="<?= $tarefa->id ?>">
                <label for="descricao">Descrição</label>
                <input type="text" name="descricao" value="<?= $tarefa->descricao ?>">
                <div>
                    <input type="checkbox" name="concluida" <?= $tarefa->concluida == 1 ? "checked" : "" ?> />Concluida
                </div>
                <button type="submit">Salvar</button>
            </form>
            <?php if (isset($_SESSION['msg'])) : $mensagem = $_SESSION['msg'];
                unset($_SESSION['msg']); ?>
                <span><?= $mensagem ?></span>
            <?php endif; ?>


        </div>
        <div class="lista">
            <ul>
                <?php foreach ($tarefas as $registro) : ?>
                    <li class="<?= $registro->concluida == 1 ? "concluida" : "" ?>">
                        <?= $registro->descricao ?>
                        <a href="?editar=<?= $registro->id ?>">[Editar]</a>
                        <a href="?excluir=<?= $registro->id ?>">[excluir]</a>
                        <?php if ($registro->concluida == 0) : ?>
                            <a href="?concluir=<?= $registro->id ?>">[Concluir]</a>
                        <?php else : ?>
                            <a href="?refazer=<?= $registro->id ?>">[Refazer]</a>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </main>
</body>

</html>