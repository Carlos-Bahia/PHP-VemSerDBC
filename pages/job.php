<?php

include '../database/db.php';

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 8; // Resultados por página
$offset = ($page - 1) * $limit;

$query = "SELECT * FROM Job_Offer LIMIT :limit OFFSET :offset";
$stmt = $pdo->prepare($query);

$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

$stmt->execute();

$jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);

$queryCount = "SELECT COUNT(*) FROM Job_Offer";
$totalResults = $pdo->query($queryCount)->fetchColumn();
$totalPages = ceil($totalResults / $limit);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Banco de Vagas</title>
    <link rel="stylesheet" href="../assets/styles/style.css">
    <link rel="stylesheet" href="../assets/styles/header.css">
    <link rel="stylesheet" href="../assets/styles/footer.css">
    <link rel="stylesheet" href="../assets/styles/job.css">

    <link rel="icon" type="image/svg+xml" href="../assets/images/dbc.svg">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
</head>
<body>
<?php include '../components/header.php'; ?>

<div class="job-container">
    <div class="job-table-title">
        <h1>Vagas de Emprego</h1>
        <form action="job-create.php" method="get">
            <button type="submit">Adicionar Vaga</button>
        </form>
    </div>
    <div class="job-content job-table">
        <table class="job-table-info">
            <thead>
            <tr>
                <th class="table-title">Nome da Vaga</th>
                <th class="table-title">Descrição</th>
                <th class="table-title"></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($jobs as $job): ?>
                <tr>
                    <td><?php echo htmlspecialchars($job['name']); ?></td>
                    <td><?php echo htmlspecialchars($job['description']); ?></td>
                    <td>
                        <a href="job-details.php?id=<?php echo $job['id']; ?>">
                            <img class="eye-icon" src="../assets/images/eye-icon.png" alt="Visualizar">
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="job-content pagination-content">
        <a class="pagination-btn <?php echo $page == 1 ? 'pagination-disable' : ''; ?>" <?php if($page != 1) echo 'aria-label="Previous"'; ?> href="?page=<?php echo $page - 1; ?>">
            <?php if($page != 1) echo '&lt;'; ?>
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
        <a class="pagination-btn <?php echo $page == $totalPages ? 'pagination-disable' : ''; ?>" <?php if($page != 1) echo 'aria-label="Next"'; ?> href="?page=<?php echo $page + 1; ?>">
            <?php if($page != $totalPages) echo '&gt;'; ?>
        </a>
    </div>
</div>

<?php include '../components/footer.php'; ?>
</body>
</html>
