<?php

namespace src\repositorys;

include '../classes/AcademicBackground.php';
include '../database/db.php';

use src\classes\AcademicBackground;

class AcademicRepository
{
    private \PDO $pdo;

    public function __construct()
    {
        global $pdo;
        $this->pdo = $pdo;
    }

    private function formAcademicBackground(array $academic): AcademicBackground
    {
        return new AcademicBackground(
            $academic['id_curriculum'],
            $academic['scholarly'],
            $academic['college'],
            $academic['course_name'],
            $academic['status'],
            $academic['id']
        );
    }

    public function createAcademicBackground(AcademicBackground $academic): AcademicBackground
    {
        $sql = "INSERT INTO Academic_Background (id_curriculum, scholarly, college, course_name, status) VALUES (?, ?, ?, ?, ?);";
        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(1, $academic->getCurriculumId());
        $statement->bindValue(2, $academic->getScholarly());
        $statement->bindValue(3, $academic->getCollege());
        $statement->bindValue(4, $academic->getCourseName());
        $statement->bindValue(5, $academic->getStatus());
        $statement->execute();

        $academic->setId($this->pdo->lastInsertId());
        return $academic;
    }

    public function getAcademicBackgroundByCurriculumId(int $id): array
    {
        $sql = "SELECT * FROM Academic_Background WHERE id_curriculum = ?;";
        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(1, $id);
        $statement->execute();

        $academic = $statement->fetchAll(\PDO::FETCH_ASSOC);

        $academicArray = array_map( function ($item) {
            return $this->formAcademicBackground($item);
        }, $academic);

        return $academicArray;
    }

    public function deleteByCurriculumId(int $id): void
    {
        $sql = "DELETE FROM Academic_Background WHERE id_curriculum = ?;";
        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(1, $id);
        $statement->execute();
    }

    public function deleteById(int $id): void
    {
        $sql = "DELETE FROM Academic_Background WHERE id = ?;";
        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(1, $id);
        $statement->execute();
    }

    public function updateAcademicBackground(AcademicBackground $academicBackground): void
    {
        if($academicBackground->getId() == null || $academicBackground->getId() == ""){
            $this->createAcademicBackground($academicBackground);
        }else{
            $sql = "UPDATE Academic_Background
                    SET scholarly = ?,
                        college = ?,
                        course_name = ?,
                        status = ?
                    WHERE id = ?;";
            $statement = $this->pdo->prepare($sql);
            $statement->bindValue(1, $academicBackground->getScholarly());
            $statement->bindValue(2, $academicBackground->getCollege());
            $statement->bindValue(3, $academicBackground->getCourseName());
            $statement->bindValue(4, $academicBackground->getStatus());
            $statement->bindValue(5, $academicBackground->getId());
            $statement->execute();
        }
    }
}