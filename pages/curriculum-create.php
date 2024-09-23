<?php
require_once '../database/db.php';
require '../classes/curriculum.php';
require '../classes/academicbackground.php';
require '../classes/experience.php';
require '../classes/curriculumknowledge.php';

use classes\Curriculum;
use classes\AcademicBackground;
use classes\Experience;
use classes\CurriculumKnowledge;


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {

        $curriculum = new Curriculum($_POST['name'], $_POST['age'], $_POST['qualifications'], $_POST['contact'], $_POST['github']);
        $curriculum->save($pdo);

        foreach ($_POST['academic'] as $academic) {
            $academicBackground = new AcademicBackground($curriculum->getId(), $academic['scholarly'], $academic['college'], $academic['course_name'], $academic['status']);
            $curriculum->addAcademicBackground($academicBackground);
        }
        $curriculum->saveAcademicBackgrounds($pdo);

        foreach ($_POST['experience'] as $experience) {
            $exp = new Experience($curriculum->getId(), $experience['company_name'], $experience['position'], $experience['admission_date'], $experience['dismissal_date'], $experience['description']);
            $curriculum->addExperience($exp);
        }
        $curriculum->saveExperiences($pdo);

        foreach ($_POST['knowledge'] as $knowledge) {
            $curriculum_knowledge = new CurriculumKnowledge($curriculum->getId(), $knowledge);
            $curriculum->addKnowledge($curriculum_knowledge);
        }
        $curriculum->saveKnowledges($pdo);

        header("Location: curriculum-details.php?id=" . $curriculum->getId());
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Erro: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Currículo</title>
    <link rel="stylesheet" href="../assets/styles/style.css">
    <link rel="stylesheet" href="../assets/styles/header.css">
    <link rel="stylesheet" href="../assets/styles/footer.css">
    <link rel="stylesheet" href="../assets/styles/curriculum-create.css">

    <link rel="icon" type="image/svg+xml" href="../assets/images/dbc.svg">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

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
    <?php
    include '../components/header.php';
    ?>

    <div class="curriculum-create">
        <form method="POST">
            <div class="form-content">
                <h2>Dados Pessoais</h2>
                <div class="personal-data">
                    <div>
                        <label for="name">Nome:</label><br>
                        <input type="text" name="name" required><br>
                    </div>
                    <div>
                        <label for="age">Idade:</label><br>
                        <input type="number" name="age" required><br>
                    </div>
                    <div>
                        <label for="qualifications">Qualificações:</label><br>
                        <input type="qualifications" name="qualifications" required><br>
                    </div>
                    <div>
                        <label for="contact">Contato:</label><br>
                        <input type="text" name="contact" id="contact" required><br>
                    </div>
                    <div>
                        <label for="github">GitHub:</label><br>
                        <input type="text" name="github"><br>
                    </div>
                </div>

                <div class="extra-details">
                    <h2>Formação Acadêmica</h2>

                    <div class="extra-details-form" id="academic-section">
                        <div>
                            <label for="scholarly">Nível:</label><br>
                            <select name="academic[0][scholarly]" required>
                                <option value="Ensino Médio">Ensino Médio</option>
                                <option value="Ensino Técnico">Ensino Técnico</option>
                                <option value="Graduação">Graduação</option>
                                <option value="Pós-Graduação">Pós-Graduação</option>
                                <option value="Mestrado">Mestrado</option>
                                <option value="Doutorado">Doutorado</option>
                                <option value="Outros">Outros</option>
                            </select><br>
                            <label for="college">Instituição:</label><br>
                            <input type="text" name="academic[0][college]" required><br>
                            <label for="course_name">Curso:</label><br>
                            <input type="text" name="academic[0][course_name]" required><br>
                            <label for="status">Status:</label><br>
                            <select name="academic[0][status]" required>
                                <option value="Em Andamento">Em Andamento</option>
                                <option value="Concluído">Concluído</option>
                                <option value="Interrompido">Interrompido</option>
                                <option value="Não Informado">Não Informado</option>
                            </select><br>
                        </div>
                    </div>
                    <div class="button-form">
                        <button type="button" onclick="addField('academic-section', 'academic')">Adicionar Formação Acadêmica</button><br>
                    </div>
                </div>

                <div class="extra-details">
                <h2>Experiências</h2>

                    <div class="extra-details-form" id="experience-section">
                        <div>
                            <label for="company_name">Empresa:</label><br>
                            <input type="text" name="experience[0][company_name]" required><br>
                            <label for="position">Cargo:</label><br>
                            <input type="text" name="experience[0][position]" required><br>
                            <label for="admission_date">Data de Admissão:</label><br>
                            <input type="date" name="experience[0][admission_date]" required><br>
                            <label for="dismissal_date">Data de Demissão:</label><br>
                            <input type="date" name="experience[0][dismissal_date]"><br>
                            <label for="description">Descrição:</label><br>
                            <textarea name="experience[0][description]" required></textarea><br>
                        </div>
                    </div>
                    <div class="button-form">
                        <button type="button" onclick="addField('experience-section', 'experience')">Adicionar Experiência</button><br>
                    </div>
                </div>

                <div class="extra-details">
                    <h2>Conhecimentos</h2>

                    <div class="extra-details-form" id="knowledge-section">
                        <div>
                            <label for="knowledge">Conhecimento:</label>
                            <input type="text" name="knowledge[0]" required><br>
                        </div>
                    </div>
                    <div class="button-form">
                        <button type="button" onclick="addField('knowledge-section', 'knowledge')">Adicionar Conhecimento</button><br>
                    </div>
                </div>

                <div class="form-submit">
                    <button type="submit">Cadastrar Currículo</button>
                </div>
            </div>
        </form>
    </div>
    <?php
    include '../components/footer.php';
    ?>
</body>
