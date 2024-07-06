<?php
session_start();
include "cabecalho.php";
include "banco.php";

if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit;
}

$usuario = $_SESSION['usuario'];
$is_admin = $_SESSION['is_admin'];

$nome_pesquisa = isset($_GET['nome']) ? $_GET['nome'] : '';
if ($nome_pesquisa) {
    $filmes = getFilmeByNome($nome_pesquisa);
} else {
    $filmes = getFilmes();
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FILMESFLIX</title>
    <link rel="stylesheet" href="filmes.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family-Lilita+One&display=swap" rel-"stylesheet">
</head>
<body>
<header class="header">
    <a class="navbar-brand" href="filmes.php">FILMES<span class="highlight">FLIX</span></a>
        <style>
    .highlight {
        color: #ff0000;
        font-weight: bold; 
    }
    a.navbar-brand{
        margin-left: -350px;
        padding-left: 170px;
    }
</style>
        <input type="checkbox" id="check">

        <label for="check" class="icons">
            <i class='bx bx-menu' id="menu-icon"></i>
            <i class='bx bx-x' id="close-icon"></i>
        </label>

        <nav class="navbar">
            
            <?php if ($is_admin): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="cadastrar.php">Cadastrar Filme</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="editar.php">Editar Filmes</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="excluir.php">Excluir Filmes</a>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
                    <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <form class="form-inline" action="filmes.php" method="get">
                            <input class="form-control mr-sm-2" type="search" placeholder="Pesquisar" aria-label="Pesquisar" name="nome" value="<?= htmlspecialchars($nome_pesquisa) ?>">
                            <button class="btn btn-outline-primary my-2 my-sm-0" type="submit">Pesquisar</button>
                        </form>
                    </li>
                    

        </nav>
    </header>

    <div class="container mt-5">
        <h2 class="listafilmes">Lista de Filmes</h2>
      <div class="row">
            <?php if ($filmes): ?>
                <?php foreach ($filmes as $filme): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <a href="trailer.php?id=<?= $filme['id'] ?>">
                                <img src="<?= htmlspecialchars($filme['poster']) ?>" class="card-img-top" alt="<?= htmlspecialchars($filme['titulo']) ?>">
                            </a>
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($filme['titulo']) ?></h5>
                                <p class="card-text"><?= htmlspecialchars($filme['descricao']) ?></p>
                                <p class="text-muted">Nota: <?= htmlspecialchars($filme['nota']) ?></p>
                                <?php if ($is_admin): ?>
                                    <a href="editar.php?id=<?= $filme['id'] ?>" class="btn btn-warning">Editar</a>
                                    <a href="excluir.php?id=<?= $filme['id'] ?>" class="btn btn-danger">Excluir</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Nenhum filme encontrado.</p>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
