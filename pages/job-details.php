<?php

include '../database/db.php';

$page = intval($_GET['id']);

try {
    //Jobs
    $stmtJob = $pdo->prepare("SELECT j.* FROM Job_Offer j WHERE j.id = :id");
    $stmtJob->execute(['id' => $page]);
    $job = $stmtJob->fetch(PDO::FETCH_ASSOC);

    $stmtKnowledge = $pdo->prepare("SELECT * FROM Job_knowledge k WHERE k.id_job = :id");
    $stmtKnowledge->execute(['id' => $page]);
    $knowledge = $stmtKnowledge->fetchAll(PDO::FETCH_ASSOC);

    //Curriculums
    $limit = 4;
    $offset = ($page - 1) * $limit;

    $query = "
    SELECT c.*, (COUNT(c.id) / (select COUNT(*)
							from job_knowledge jk1 
							where JK1.id_job = ?)) as percentage
    FROM CURRICULUM c
    JOIN CURRICULUM_KNOWLEDGE k ON c.id = k.id_curriculum
    WHERE LOWER(k.knowledge) IN (
        SELECT LOWER(jk.knowledge) 
        FROM JOB_KNOWLEDGE jk 
        WHERE jk.id_job = ? 
    )
    GROUP BY c.id
    order by percentage desc;
    LIMIT ? OFFSET ?";

    $stmt = $pdo->prepare($query);

    $stmt->bindValue(1, $page, PDO::PARAM_INT); // Para o id_job
    $stmt->bindValue(2, $page, PDO::PARAM_INT); // Para o id_job
    $stmt->bindValue(3, $limit, PDO::PARAM_INT); // Limite
    $stmt->bindValue(4, $offset, PDO::PARAM_INT); // Offset

    $stmt->execute();
    $curriculum = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die('Erro ao executar consultas: ' . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes da Vaga</title>
    <link rel="stylesheet" href="../assets/styles/style.css">
    <link rel="stylesheet" href="../assets/styles/header.css">
    <link rel="stylesheet" href="../assets/styles/footer.css">
    <link rel="stylesheet" href="../assets/styles/job-details.css">

    <link rel="icon" type="image/svg+xml" href="../assets/images/dbc.svg">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
</head>
<body>
<?php include '../components/header.php'; ?>

<div class="job-details-container">

    <div class="job-details-title">
        <h2>Descrição da Vaga</h2>
        <div class="job-actions">
            <button onclick="window.location.href='job-update.php?id=<?php echo $job['id']; ?>'" class="btn btn-edit">Editar</button>
            <form action="../database/job/delete-job.php" method="post">
                <input type="hidden" name="id" value="<?php echo $page; ?>">
                <button type="submit" class="btn btn-delete">Deletar</button>
            </form>
        </div>
    </div>

    <div class="job-details">
        <div class="details">
            <h2><?php echo htmlspecialchars($job['name']); ?></h2>
            <br>
            <p><?php echo nl2br(htmlspecialchars($job['description'])); ?></p>
            <div class="divider"></div>
        </div>
        <div class="knowledge">
            <h3>Conhecimentos Necessários</h3>
            <ul>
                <?php foreach ($knowledge as $know): ?>
                    <li><?php echo htmlspecialchars($know['knowledge']); ?></li>
                <?php endforeach; ?>
            </ul>
            <div class="divider"></div>
        </div>
        <div class="details related-curriculum">
            <h3>Curriculos Compatíveis</h3>
            <?php if(!empty($curriculum)) : ?>
                <table class="job-table-info">
                    <thead>
                    <tr>
                        <th class="table-title">Nome</th>
                        <th class="table-title">Qualificação</th>
                        <th class="table-title">Github</th>
                        <th class="table-title">Compatibilidade</th>
                        <th class="table-title"></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($curriculum as $entry): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($entry['name']); ?></td>
                            <td><?php echo htmlspecialchars($entry['qualifications']); ?></td>
                            <td><a href="<?php echo $entry['github']; ?>" target="_blank"><?php echo $entry['github']; ?></a></td>
                            <td><?php echo htmlspecialchars($entry['percentage']*100);?>%</td>
                            <td>
                                <a href="curriculum-details.php?id=<?php echo $entry['id']; ?>">
                                    <img class="eye-icon" src="../assets/images/eye-icon.png" alt="Visualizar">
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else : ?>
                <h3>⚠️Não há currículos compatíveis⚠️</h3>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include '../components/footer.php'; ?>
</body>
</html>
