<?php

include '../database/db.php';
require '../classes/curriculum.php';

use classes\Curriculum;

$page = intval($_GET['id']);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $curriculumId = $_POST['id'];

        $curriculum = new Curriculum('', '', '', '', '');
        $curriculum->setId($curriculumId);

        $curriculum->delete($pdo);

        header("Location: curriculum.php");
        exit();
    } catch (Exception $e) {
        echo "Erro ao deletar o currículo: " . $e->getMessage();
    }
} else {
    try {
        $stmtCurriculum = $pdo->prepare("SELECT c.* FROM CURRICULUM c WHERE c.id = :id");
        $stmtCurriculum->execute(['id' => $page]);
        $curriculum = $stmtCurriculum->fetch(PDO::FETCH_ASSOC);

        $stmtAcademicBackground = $pdo->prepare("SELECT * FROM academic_background a WHERE a.id_curriculum = :id");
        $stmtAcademicBackground->execute(['id' => $page]);
        $academicBackground = $stmtAcademicBackground->fetchAll(PDO::FETCH_ASSOC);

        $stmtExperience = $pdo->prepare("SELECT * FROM Experience e WHERE e.id_curriculum = :id");
        $stmtExperience->execute(['id' => $page]);
        $experience = $stmtExperience->fetchAll(PDO::FETCH_ASSOC);

        $stmtKnowledge = $pdo->prepare("SELECT * FROM Curriculum_knowledge k WHERE k.id_curriculum = :id");
        $stmtKnowledge->execute(['id' => $page]);
        $knowledge = $stmtKnowledge->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        die('Erro ao executar consultas: ' . $e->getMessage());
    }
}

function formatContact($numero) {
    $numero = preg_replace('/\D/', '', $numero);

    if (strlen($numero) == 10) {
        return preg_replace('/(\d{2})(\d{4})(\d{4})/', '($1) $2-$3', $numero);
    } elseif (strlen($numero) == 11) {
        return preg_replace('/(\d{2})(\d{1})(\d{4})(\d{4})/', '($1) $2$3-$4', $numero);
    }

    return $numero;
}

function formatDate($date) {
    $dateTime = DateTime::createFromFormat('Y-m-d', $date);
    return $dateTime->format('d/m/Y');
}
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
    <link rel="stylesheet" href="../assets/styles/curriculum-details.css">

    <link rel="icon" type="image/svg+xml" href="../assets/images/dbc.svg">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>
<body>
    <?php
    include '../components/header.php';
    ?>

    <div class="curriculum-details-container">

        <div class="curriculum-details-title">
            <div>
                <h2>Detalhes do Currículo</h2>
            </div>
            <div class="curriculum-actions">
                <button onclick="window.location.href='curriculum-update.php?id=<?php echo $curriculum['id']; ?>'" class="btn btn-edit">Editar</button>
                <form action="" method="post">
                    <input type="hidden" name="id" value="<?php echo $page; ?>">
                    <button type="submit" class="btn btn-delete">Deletar</button>
                </form>
            </div>
        </div>
        <div class="curriculum-details">
            <div class="details">
                <h3>Formação Acadêmica</h3>
                <ul>
                    <?php foreach ($academicBackground as $background): ?>
                        <li>
                            <i><?php echo htmlspecialchars($background['scholarly']) . "</i><br>" . htmlspecialchars($background['course_name']) . "<br>" . htmlspecialchars($background['college']) . "<br><strong>" . htmlspecialchars($background['status']) . "</strong><br><br>"; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="details">
                <h3>Dados Pessoais</h3>
                <p><?php echo htmlspecialchars($curriculum['name']); ?>, <?php echo htmlspecialchars($curriculum['age']); ?></p>
                <p><?php echo htmlspecialchars(formatContact($curriculum['contact'])); ?></p>
                <p><a href="<?php echo htmlspecialchars($curriculum['github']); ?>" target="_blank"><?php echo htmlspecialchars($curriculum['github']); ?></a></p>
                <p><?php echo nl2br(htmlspecialchars($curriculum['qualifications'])); ?></p>
            </div>
            <div class="details">
            <h3>Experiência</h3>
                <ul>
                    <?php foreach ($experience as $exp): ?>
                        <li>
                            <strong><?php echo htmlspecialchars($exp['company_name']) . "</strong>" . " - " . htmlspecialchars($exp['position']) . "<br>" . nl2br(htmlspecialchars($exp['description'])) . "<br>" . htmlspecialchars(formatDate($exp['admission_date'])) . " a " . ($exp['dismissal_date'] ? htmlspecialchars(formatDate($exp['dismissal_date'])) : 'Atual') . "<br><br>" ; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="divider"></div>

            <div class="knowledge">
            <h3>Conhecimentos</h3>
                <ul>
                    <?php foreach ($knowledge as $know): ?>
                        <li><?php echo htmlspecialchars($know['knowledge']); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>


    <?php
    include '../components/footer.php';
    ?>
</body>