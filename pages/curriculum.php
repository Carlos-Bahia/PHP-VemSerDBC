<?php

include '../database/db.php';
include '../services/curriculumService.php';

use src\services\CurriculumService;

$curriculumService = CurriculumService::getInstance();

    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = 8;
    $curriculum = $curriculumService->getCurriculumsPaginated($page, $limit);
    $totalPages = $curriculumService->getTotalPages($page, $limit);

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Banco de Currículos</title>
    <link rel="stylesheet" href="../assets/styles/style.css">
    <link rel="stylesheet" href="../assets/styles/header.css">
    <link rel="stylesheet" href="../assets/styles/footer.css">
    <link rel="stylesheet" href="../assets/styles/curriculum.css">

    <link rel="icon" type="image/svg+xml" href="../assets/images/dbc.svg">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>
<body>
    <?php
    include '../components/header.php';
    ?>

    <div class="curriculum-container">
        <div class="curriculum-table-title">
            <h1>Currículos</h1>
            <form action="curriculum-create.php" method="get">
                <button type="submit">Adicionar Currículo</button>
            </form>
        </div>
        <div class="curriculum-content curriculum-table">
            <table class="curriculum-table-info">
                <thead>
                    <tr>
                        <th class="table-title">Nome</th>
                        <th class="table-title">Qualificação</th>
                        <th class="table-title">Contato</th>
                        <th class="table-title">Github</th>
                        <th class="table-title"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($curriculum as $entry): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($entry->getName()); ?></td>
                            <td><?php echo htmlspecialchars($entry->getQualifications()); ?></td>
                            <td><?php echo htmlspecialchars($curriculumService::formatContact($entry->getContact())); ?></td>
                            <td><a href="<?php echo $entry->getGithub(); ?>" target="_blank"><?php echo $entry->getGithub(); ?></a></td>
                            <td>
                                <a href="curriculum-details.php?id=<?php echo $entry->getId(); ?>">
                                    <img class="eye-icon" src="../assets/images/eye-icon.png" alt="Visualizar">
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        </div>
        <div class="curriculum-content">
            <a class="pagination-btn <?php echo $page == 1 ? 'pagination-disable' : ''; ?>" href="?page=<?php echo $page - 1; ?>">
                <?php echo '&lt;'; ?>
            </a>
            <?php
            $hasPrintedMiddle = false;
            for ($i = 1; $i <= $totalPages; $i++):
                if ($i == 1 || $i == $page - 1 || $i == $page || $i == $page + 1 || $i == $totalPages): ?>
                    <a class="pagination-btn <?php echo $page == $i ? 'pagination-actual' : ''; ?>" href="?page=<?php echo $i; ?>">
                        <?php echo $i; ?>
                        <?php $hasPrintedMiddle = false; ?>
                    </a>
                <?php elseif (!$hasPrintedMiddle): ?>
                    <a class="pagination-btn" href="?page=<?php echo $i; ?>">
                        <?php echo "..."; ?>
                    </a>
                    <?php $hasPrintedMiddle = true;?>
                <?php endif;
            endfor;
            ?>
            <a class="pagination-btn <?php echo $page == $totalPages ? 'pagination-disable' : ''; ?>"  href="?page=<?php echo $page + 1; ?>">
                <?php echo '&gt;'; ?>
            </a>
        </div>
    </div>

    <?php
    include '../components/footer.php';
    ?>
</body>
