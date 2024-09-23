<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Banco de Currículos</title>
    <link rel="stylesheet" href="assets/styles/style.css">
    <link rel="stylesheet" href="assets/styles/header.css">
    <link rel="stylesheet" href="assets/styles/footer.css">
    <link rel="stylesheet" href="assets/styles/index.css">

    <link rel="icon" type="image/svg+xml" href="assets/images/dbc.svg">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>
<body>
    <?php
        include 'components/header.php';
    ?>

    <div class="index-container">
        <div class="index-content-left">
            <h2>Banco de Currículos e Gerenciador de Vagas de Emprego.</h2>
            <p>Com esse sistema, você terá controle total sobre os currículos armazenados e poderá visualizar a compatibilidade com as vagas cadastradas.</p>
            <div class="index-buttons">
                <a href="pages/curriculum.php">Acessar Currículos</a>
                <a href="pages/job.php">Acessar Vagas</a>
            </div>
        </div>
        <div class="index-content-right">
            <img src="assets/images/entrevista.jpg" alt="Entrevista de RH">
        </div>
    </div>

    <?php
        include 'components/footer.php';
    ?>
</body>