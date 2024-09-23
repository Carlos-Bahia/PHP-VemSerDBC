<?php

include '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id'])) {
        $id = $_POST['id'];
        deleteCurriculum($id);
        header("Location: ../../pages/job.php");
        exit();
    }
}

function deleteCurriculum($id)
{
    global $pdo;
    $sql = $pdo->prepare("DELETE FROM JOB_KNOWLEDGE WHERE id_job = :id;
                                DELETE FROM JOB_OFFER WHERE id= :id");
    $sql->bindParam(':id', $id, PDO::PARAM_INT);
    $sql->execute();
}