<?php
require '../database/db.php';
require '../services/CurriculumService.php';

use src\services\CurriculumService;

$curriculumService = CurriculumService::getInstance();
$curriculumId = $_GET['id'];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        try {

            $curriculumService->updateCurriculum($curriculumId, $_POST);

            header("Location: curriculum-details.php?id=$curriculumId");
            exit();
        } catch (Exception $e) {
            echo "Erro: " . $e->getMessage();
        }
    } else {
        $curriculum = $curriculumService->getCurriculumById($curriculumId);
        $academicBackground = $curriculumService->getAcademicBackgroundByCurriculumId($curriculumId);
        $experiences = $curriculumService->getExperiencesByCurriculumId($curriculumId);
        $knowledges = $curriculumService->getKnowledgesByCurriculumId($curriculumId);
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
                    <input type="text" name="name" value="<?= htmlspecialchars($curriculum->getName()) ?>" required><br>
                </div>
                <div>
                    <label for="age">Idade:</label><br>
                    <input type="number" name="age" value="<?= htmlspecialchars($curriculum->getAge()) ?>" required><br>
                </div>
                <div>
                    <label for="qualifications">Qualificações:</label><br>
                    <input type="text" name="qualifications" value="<?= htmlspecialchars($curriculum->getQualifications()) ?>" required><br>
                </div>
                <div>
                    <label for="contact">Contato:</label><br>
                    <input type="text" name="contact" value="<?= htmlspecialchars($curriculum->getContact()) ?>" required><br>
                </div>
                <div>
                    <label for="github">GitHub:</label><br>
                    <input type="text" name="github" value="<?= htmlspecialchars($curriculum->getGithub()) ?>"><br>
                </div>
            </div>

            <div class="extra-details">
                <h2>Formação Acadêmica</h2>
                <div class="extra-details-form" id="academic-section">
                    <?php foreach ($academicBackground as $index => $academic) : ?>
                        <div>
                            <label for="scholarly">Nível:</label><br>
                            <select name="academic[<?= $index ?>][scholarly]" required>
                                <option value="Ensino Médio" <?= $academic->getScholarly() == 'Ensino Médio' ? 'selected' : '' ?>>Ensino Médio</option>
                                <option value="Ensino Técnico" <?= $academic->getScholarly() == 'Ensino Técnico' ? 'selected' : '' ?>>Ensino Técnico</option>
                                <option value="Graduação" <?= $academic->getScholarly() == 'Graduação' ? 'selected' : '' ?>>Graduação</option>
                                <option value="Pós-Graduação" <?= $academic->getScholarly() == 'Pós-Graduação' ? 'selected' : '' ?>>Pós-Graduação</option>
                                <option value="Mestrado" <?= $academic->getScholarly() == 'Mestrado' ? 'selected' : '' ?>>Mestrado</option>
                                <option value="Doutorado" <?= $academic->getScholarly() == 'Doutorado' ? 'selected' : '' ?>>Doutorado</option>
                                <option value="Outros" <?= $academic->getScholarly() == 'Outros' ? 'selected' : '' ?>>Outros</option>
                            </select><br>
                            <label for="college">Instituição:</label><br>
                            <input type="text" name="academic[<?= $index ?>][college]" value="<?= htmlspecialchars($academic->getCollege()) ?>" required><br>
                            <label for="course_name">Curso:</label><br>
                            <input type="text" name="academic[<?= $index ?>][course_name]" value="<?= htmlspecialchars($academic->getCourseName()) ?>" required><br>
                            <label for="status">Status:</label><br>
                            <select name="academic[<?= $index ?>][status]" required>
                                <option value="Em Andamento" <?= $academic->getStatus() == 'Em Andamento' ? 'selected' : '' ?>>Em Andamento</option>
                                <option value="Concluído" <?= $academic->getStatus() == 'Concluído' ? 'selected' : '' ?>>Concluído</option>
                                <option value="Interrompido" <?= $academic->getStatus() == 'Interrompido' ? 'selected' : '' ?>>Interrompido</option>
                                <option value="Não Informado" <?= $academic->getStatus() == 'Não Informado' ? 'selected' : '' ?>>Não Informado</option>
                            </select><br>
                            <input type="hidden" name="academic[<?= $index ?>][id]" value="<?= htmlspecialchars($academic->getId()) ?>" required><br>

                        </div>
                        <?php if($academic !== end($academicBackground)) : ?>
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
                            <input type="text" name="experience[<?= $index ?>][company_name]" value="<?= htmlspecialchars($experience->getCompanyName()) ?>" required><br>
                            <label for="position">Cargo:</label><br>
                            <input type="text" name="experience[<?= $index ?>][position]" value="<?= htmlspecialchars($experience->getPosition()) ?>" required><br>
                            <label for="admission_date">Data de Admissão:</label><br>
                            <input type="date" name="experience[<?= $index ?>][admission_date]" value="<?= htmlspecialchars($experience->getAdmissionDate()) ?>" required><br>
                            <label for="dismissal_date">Data de Demissão:</label><br>
                            <input type="date" name="experience[<?= $index ?>][dismissal_date]" value="<?= htmlspecialchars($experience->getDismissalDate()) ?>"><br>
                            <label for="description">Descrição:</label><br>
                            <textarea name="experience[<?= $index ?>][description]" required><?= htmlspecialchars($experience->getDescription()) ?></textarea><br>
                        </div>
                        <input type="hidden" name="experience[<?= $index ?>][id]" value="<?= htmlspecialchars($experience->getId()) ?>" required><br>

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
                            <input type="text" name="knowledge[<?= $index ?>]" value="<?= htmlspecialchars($knowledge->getKnowledge()) ?>" required><br>
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