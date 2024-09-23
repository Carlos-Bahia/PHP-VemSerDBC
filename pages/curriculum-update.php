<?php
require_once '../database/db.php';

$curriculumId = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        global $pdo;
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("UPDATE Curriculum SET name = ?, age = ?, qualifications = ?, contact = ?, github = ? WHERE id = ?");
        $stmt->execute([$_POST['name'], $_POST['age'], $_POST['qualifications'], $_POST['contact'], $_POST['github'], $curriculumId]);

        // Deletando Formação Acadêmica
        $stmt = $pdo->prepare("DELETE FROM Academic_Background WHERE id_curriculum = ?");
        $stmt->execute([$curriculumId]);

        // Deletando Experiências
        $stmt = $pdo->prepare("DELETE FROM Experience WHERE id_curriculum = ?");
        $stmt->execute([$curriculumId]);

        // Deletando Conhecimentos
        $stmt = $pdo->prepare("DELETE FROM Curriculum_knowledge WHERE id_curriculum = ?");
        $stmt->execute([$curriculumId]);

        foreach ($_POST['academic'] as $academic) {
            $stmt = $pdo->prepare("INSERT INTO Academic_Background (id_curriculum, scholarly, college, course_name, status) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$curriculumId, $academic['scholarly'], $academic['college'], $academic['course_name'], $academic['status']]);
        }

        foreach ($_POST['experience'] as $experience) {
            $stmt = $pdo->prepare("INSERT INTO Experience (id_curriculum, company_name, position, admission_date, dismissal_date, description) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$curriculumId, $experience['company_name'], $experience['position'], $experience['admission_date'], $experience['dismissal_date'], $experience['description']]);
        }

        foreach ($_POST['knowledge'] as $knowledge) {
            $stmt = $pdo->prepare("INSERT INTO Curriculum_knowledge (id_curriculum, knowledge) VALUES (?, ?)");
            $stmt->execute([$curriculumId, $knowledge]);
        }

        $pdo->commit();

        header("Location: curriculum-details.php?id=$curriculumId");
        exit();
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Erro: " . $e->getMessage();
    }
} else {
    // Carregar os dados atuais do currículo para preencher os campos
    $stmt = $pdo->prepare("SELECT * FROM Curriculum WHERE id = ?");
    $stmt->execute([$curriculumId]);
    $curriculum = $stmt->fetch(PDO::FETCH_ASSOC);

    // Carregar Formação Acadêmica
    $stmt = $pdo->prepare("SELECT * FROM Academic_Background WHERE id_curriculum = ?");
    $stmt->execute([$curriculumId]);
    $academicBackgrounds = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Carregar Experiências
    $stmt = $pdo->prepare("SELECT * FROM Experience WHERE id_curriculum = ?");
    $stmt->execute([$curriculumId]);
    $experiences = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Carregar Conhecimentos
    $stmt = $pdo->prepare("SELECT * FROM Curriculum_knowledge WHERE id_curriculum = ?");
    $stmt->execute([$curriculumId]);
    $knowledges = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atualizar Currículo</title>
    <link rel="stylesheet" href="../assets/styles/style.css">
    <link rel="stylesheet" href="../assets/styles/header.css">
    <link rel="stylesheet" href="../assets/styles/footer.css">
    <link rel="stylesheet" href="../assets/styles/curriculum-create.css">

    <link rel="icon" type="image/svg+xml" href="../assets/images/dbc.svg">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    <script>
        function addField(section) {
            const container = document.getElementById(section);
            const clone = container.querySelector('div').cloneNode(true);

            const inputs = clone.querySelectorAll('input, select, textarea');
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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

</head>
<body>
<?php include '../components/header.php'; ?>

<div class="curriculum-create">
    <form method="POST">
        <div class="form-content">
            <h2>Dados Pessoais</h2>
            <div class="personal-data">
                <div>
                    <label for="name">Nome:</label><br>
                    <input type="text" name="name" value="<?= htmlspecialchars($curriculum['name']) ?>" required><br>
                </div>
                <div>
                    <label for="age">Idade:</label><br>
                    <input type="number" name="age" value="<?= htmlspecialchars($curriculum['age']) ?>" required><br>
                </div>
                <div>
                    <label for="qualifications">Qualificações:</label><br>
                    <input type="text" name="qualifications" value="<?= htmlspecialchars($curriculum['qualifications']) ?>" required><br>
                </div>
                <div>
                    <label for="contact">Contato:</label><br>
                    <input type="text" name="contact" value="<?= htmlspecialchars($curriculum['contact']) ?>" required><br>
                </div>
                <div>
                    <label for="github">GitHub:</label><br>
                    <input type="text" name="github" value="<?= htmlspecialchars($curriculum['github']) ?>"><br>
                </div>
            </div>

            <div class="extra-details">
                <h2>Formação Acadêmica</h2>
                <div class="extra-details-form" id="academic-section">
                    <?php foreach ($academicBackgrounds as $index => $academic) : ?>
                        <div>
                            <label for="scholarly">Nível:</label><br>
                            <select name="academic[<?= $index ?>][scholarly]" required>
                                <option value="Ensino Médio" <?= $academic['scholarly'] == 'Ensino Médio' ? 'selected' : '' ?>>Ensino Médio</option>
                                <option value="Ensino Técnico" <?= $academic['scholarly'] == 'Ensino Técnico' ? 'selected' : '' ?>>Ensino Técnico</option>
                                <option value="Graduação" <?= $academic['scholarly'] == 'Graduação' ? 'selected' : '' ?>>Graduação</option>
                                <option value="Pós-Graduação" <?= $academic['scholarly'] == 'Pós-Graduação' ? 'selected' : '' ?>>Pós-Graduação</option>
                                <option value="Mestrado" <?= $academic['scholarly'] == 'Mestrado' ? 'selected' : '' ?>>Mestrado</option>
                                <option value="Doutorado" <?= $academic['scholarly'] == 'Doutorado' ? 'selected' : '' ?>>Doutorado</option>
                                <option value="Outros" <?= $academic['scholarly'] == 'Outros' ? 'selected' : '' ?>>Outros</option>
                            </select><br>
                            <label for="college">Instituição:</label><br>
                            <input type="text" name="academic[<?= $index ?>][college]" value="<?= htmlspecialchars($academic['college']) ?>" required><br>
                            <label for="course_name">Curso:</label><br>
                            <input type="text" name="academic[<?= $index ?>][course_name]" value="<?= htmlspecialchars($academic['course_name']) ?>" required><br>
                            <label for="status">Status:</label><br>
                            <select name="academic[<?= $index ?>][status]" required>
                                <option value="Em Andamento" <?= $academic['status'] == 'Em Andamento' ? 'selected' : '' ?>>Em Andamento</option>
                                <option value="Concluído" <?= $academic['status'] == 'Concluído' ? 'selected' : '' ?>>Concluído</option>
                                <option value="Interrompido" <?= $academic['status'] == 'Interrompido' ? 'selected' : '' ?>>Interrompido</option>
                                <option value="Não Informado" <?= $academic['status'] == 'Não Informado' ? 'selected' : '' ?>>Não Informado</option>
                            </select><br>
                        </div>
                        <?php if($academic !== end($academicBackgrounds)) : ?>
                            <div class="divider"></div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
                <div class="button-form">
                    <button type="button" onclick="addField('academic-section')">Adicionar Formação Acadêmica</button><br>
                </div>
            </div>

            <div class="extra-details">
                <h2>Experiências</h2>
                <div class="extra-details-form" id="experience-section">
                    <?php foreach ($experiences as $index => $experience) : ?>
                        <div>
                            <label for="company_name">Empresa:</label><br>
                            <input type="text" name="experience[<?= $index ?>][company_name]" value="<?= htmlspecialchars($experience['company_name']) ?>" required><br>
                            <label for="position">Cargo:</label><br>
                            <input type="text" name="experience[<?= $index ?>][position]" value="<?= htmlspecialchars($experience['position']) ?>" required><br>
                            <label for="admission_date">Data de Admissão:</label><br>
                            <input type="date" name="experience[<?= $index ?>][admission_date]" value="<?= htmlspecialchars($experience['admission_date']) ?>" required><br>
                            <label for="dismissal_date">Data de Demissão:</label><br>
                            <input type="date" name="experience[<?= $index ?>][dismissal_date]" value="<?= htmlspecialchars($experience['dismissal_date']) ?>"><br>
                            <label for="description">Descrição:</label><br>
                            <textarea name="experience[<?= $index ?>][description]" required><?= htmlspecialchars($experience['description']) ?></textarea><br>
                        </div>
                        <?php if($experience !== end($experiences)) : ?>
                            <div class="divider"></div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
                <div class="button-form">
                    <button type="button" onclick="addField('experience-section')">Adicionar Experiência</button><br>
                </div>
            </div>

            <div class="extra-details">
                <h2>Conhecimentos</h2>
                <div class="extra-details-form" id="knowledge-section">
                    <?php foreach ($knowledges as $index => $knowledge) : ?>
                        <div>
                            <label for="knowledge">Conhecimento:</label><br>
                            <input type="text" name="knowledge[<?= $index ?>]" value="<?= htmlspecialchars($knowledge['knowledge']) ?>" required><br>
                        </div>
                        <?php if($knowledge !== end($knowledges)) : ?>
                            <div class="divider" id="divider-k<?php echo $index ?>"></div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
                <div class="button-form">
                    <button type="button" onclick="addField('knowledge-section')">Adicionar Conhecimento</button><br>
                </div>
            </div>

            <div class="form-submit">
                <button type="submit">Atualizar Currículo</button>
            </div>
        </div>
    </form>
</div>
<?php include '../components/footer.php'; ?>
</body>
</html>