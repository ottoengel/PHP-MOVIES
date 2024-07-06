<?php
session_start();

if (isset($_SESSION['usuario'])) {
    header('Location: filmes.php');
    exit();
}

require_once 'banco.php';

$usu = $_POST['usuario'] ?? null;
$sen = $_POST['senha'] ?? null;
$nome = $_POST['nome'] ?? null;
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['login'])) {

        if ($usu && $sen) {
            $usu = $banco->real_escape_string($usu);
            $busca = $banco->query("SELECT * FROM usuarios WHERE usuario='$usu'");
            if ($busca->num_rows == 0) {
                $error = 'Usuário não existe';
            } else {
                $obj = $busca->fetch_object();
                if (password_verify($sen, $obj->senha)) {
                    $_SESSION['usuario'] = $usu;
                    $_SESSION['cod'] = $obj->cod;
                    $_SESSION['is_admin'] = $obj->is_admin ? true : false;
                    header('Location: filmes.php');
                    exit();
                } else {
                    $error = 'Senha incorreta';
                }
            }
        } else {
            $error = 'Por favor, preencha todos os campos';
        }
    } elseif (isset($_POST['register'])) {
        // Sem caractere especial e Hash pra criptografar a senha
        if ($usu && $nome && $sen) {
            $usu = $banco->real_escape_string($usu);
            $nome = $banco->real_escape_string($nome);
            $sen = password_hash($sen, PASSWORD_BCRYPT);
            $stmt = $banco->prepare("INSERT INTO usuarios (usuario, nome, senha, is_admin) VALUES (?, ?, ?, 0)");
            $stmt->bind_param("sss", $usu, $nome, $sen);

            if ($stmt->execute()) {
                $success = 'Registro realizado com sucesso. Por favor, faça login.';
            } else {
                $error = 'Erro ao registrar usuário.';
            }
        } else {
            $error = 'Por favor, preencha todos os campos';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="login.css" />
</head>

<body>

    <div class="Titulo">
        <h1>FILMESFLIX</h1>
    </div>
    
    <div class="wrapper">
        <div class="card-switch">
            <label class="switch">
                <input class="toggle" type="checkbox">
                <span class="slider"></span>
                <span class="card-side"></span>
                <div class="flip-card__inner">
                    <div class="flip-card__front">
                        <div class="title">Log in</div>
                        <form action="" method="post" class="flip-card__form">
                            <?php if ($success) : ?>
                            <div class="alert alert-success" role="alert">
                                <?= $success ?>
                            </div>
                            <?php endif; ?>
                            <?php if ($error && !isset($_POST['register'])) : ?>
                            <div class="alert alert-danger" role="alert">
                                <?= $error ?>
                            </div>
                            <?php endif; ?>
                            <input type="text" placeholder="Usuário" id="usuario" name="usuario" class="flip-card__input" required>
                            <input type="password" placeholder="Senha" id="senha" name="senha" class="flip-card__input" required>
                            <button type="submit" name="login" class="flip-card__btn">Let's go!</button>
                        </form>
                    </div>
                    <div class="flip-card__back">
                        <div class="title">Sign up</div>
                        <form action="" method="post" class="flip-card__form">
                            <?php if ($error && isset($_POST['register'])) : ?>
                            <div class="alert alert-danger" role="alert">
                                <?= $error ?>
                            </div>
                            <?php endif; ?>
                            <input type="text" placeholder="Usuário" name="usuario" class="flip-card__input" required>
                            <input type="text" placeholder="Nome" name="nome" class="flip-card__input" required>
                            <input type="password" placeholder="Senha" name="senha" class="flip-card__input" required>
                            <button type="submit" name="register" class="flip-card__btn">Confirm!</button>
                        </form>
                    </div>
                </div>
            </label>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
