<?php
require_once '../database/db.php';

$jobId = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        global $pdo;
        $pdo->beginTransaction();

        // Update job details
        $stmt = $pdo->prepare("UPDATE Job_Offer SET name = ?, description = ? WHERE id = ?");
        $stmt->execute([$_POST['name'], $_POST['description'], $jobId]);

        // Deleting existing knowledge
        $stmt = $pdo->prepare("DELETE FROM Job_knowledge WHERE id_job = ?");
        $stmt->execute([$jobId]);

        // Insert new knowledge
        foreach ($_POST['knowledge'] as $knowledge) {
            $stmt = $pdo->prepare("INSERT INTO Job_knowledge (id_job, knowledge) VALUES (?, ?)");
            $stmt->execute([$jobId, $knowledge]);
        }

        $pdo->commit();

        header("Location: job-details.php?id=$jobId");
        exit();
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Erro: " . $e->getMessage();
    }
} else {
    // Load existing job data
    $stmt = $pdo->prepare("SELECT * FROM Job_Offer WHERE id = ?");
    $stmt->execute([$jobId]);
    $job = $stmt->fetch(PDO::FETCH_ASSOC);

    // Load existing knowledge
    $stmt = $pdo->prepare("SELECT * FROM Job_knowledge WHERE id_job = ?");
    $stmt->execute([$jobId]);
    $knowledges = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atualizar Vaga</title>
    <link rel="stylesheet" href="../assets/styles/style.css">
    <link rel="stylesheet" href="../assets/styles/header.css">
    <link rel="stylesheet" href="../assets/styles/footer.css">
    <link rel="stylesheet" href="../assets/styles/job-create.css">

    <link rel="icon" type="image/svg+xml" href="../assets/images/dbc.svg">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    <script>
        function addField(section) {
            const container = document.getElementById(section);
            const clone = container.querySelector('div').cloneNode(true);

            const inputs = clone.querySelectorAll('input');
            inputs.forEach(input => {
                const name = input.name.replace(/\[\d+\]/, `[${container.children.length}]`);
                input.name = name;
                input.value = '';
            });

            const divider = document.createElement('div');
            divider.classList.add('divider');

            container.appendChild(divider);
            container.appendChild(clone);
        }
    </script>
</head>
<body>
<?php include '../components/header.php'; ?>

<div class="job-create">
    <form method="POST">
        <div class="form-content">
            <h2>Dados da Vaga</h2>
            <div class="job-data">
                <div>
                    <label for="name">Nome da Vaga:</label><br>
                    <input type="text" name="name" value="<?= htmlspecialchars($job['name']) ?>" required><br>
                </div>
                <div>
                    <label for="description">Descrição:</label><br>
                    <textarea name="description" required><?= htmlspecialchars($job['description']) ?></textarea><br>
                </div>
            </div>

            <div class="extra-details">
                <h2>Conhecimentos Necessários</h2>
                <div class="extra-details-form" id="knowledge-section">
                    <?php foreach ($knowledges as $index => $knowledge) : ?>
                        <div>
                            <label for="knowledge">Conhecimento:</label>
                            <input type="text" name="knowledge[<?= $index ?>]" value="<?= htmlspecialchars($knowledge['knowledge']) ?>" required><br>
                        </div>
                        <?php if ($knowledge !== end($knowledges)) : ?>
                            <div class="divider"></div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
                <div class="button-form">
                    <button type="button" onclick="addField('knowledge-section')">Adicionar Conhecimento</button><br>
                </div>
            </div>

            <div class="form-submit">
                <button type="submit">Atualizar Vaga</button>
            </div>
        </div>
    </form>
</div>

<?php include '../components/footer.php'; ?>
</body>
</html>
