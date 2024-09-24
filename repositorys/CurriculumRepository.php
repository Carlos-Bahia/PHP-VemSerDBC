<?php

namespace src\repositorys;

include '../classes/Curriculum.php';
include '../database/db.php';

use PDO;
use src\classes\Curriculum;

class CurriculumRepository
{
    private \PDO $pdo;

    public function __construct()
    {
        global $pdo;
        $this->pdo = $pdo;
    }

    private function formCurriculum(array $curriculum): Curriculum
    {
        return new Curriculum(
            $curriculum['name'],
            $curriculum['age'],
            $curriculum['qualifications'],
            $curriculum['contact'],
            $curriculum['github'],
            $curriculum['id']);
    }

    public function createCurriculum(Curriculum $curriculum): Curriculum
    {
        $sql = "INSERT INTO Curriculum (name, age, qualifications, contact, github) VALUES (?, ?, ?, ?, ?);";
        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(1, $curriculum->getName());
        $statement->bindValue(2, $curriculum->getAge());
        $statement->bindValue(3, $curriculum->getQualifications());
        $statement->bindValue(4, $curriculum->getContact());
        $statement->bindValue(5, $curriculum->getGithub());
        $statement->execute();

        $curriculum->setId($this->pdo->lastInsertId());
        return $curriculum;
    }

    public function getCurriculumById(int $id): Curriculum
    {
        $sql = "SELECT * FROM Curriculum WHERE id = ?;";
        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(1, $id);
        $statement->execute();

        $curriculum = $statement->fetch(\PDO::FETCH_ASSOC);

        return $this->formCurriculum($curriculum);
    }

    public function getCurriculumPaginated(int $page, int $perPage): array
    {
        $offset = ($page - 1) * $perPage;

        $query = "SELECT * FROM CURRICULUM LIMIT ? OFFSET ? ;";
        $statement = $this->pdo->prepare($query);

        $statement->bindValue(1, $perPage, PDO::PARAM_INT);
        $statement->bindValue(2, $offset, PDO::PARAM_INT);
        $statement->execute();

        $curriculums = $statement->fetchAll(PDO::FETCH_ASSOC);

        $curriculumArray = array_map( function ($item) {
            return $this->formCurriculum($item);
        }, $curriculums);

        return $curriculumArray;
    }

    public function getTotalPages(int $page, int $perPage): int
    {
        $queryCount = "SELECT COUNT(*) FROM Curriculum";
        $totalResults = $this->pdo->query($queryCount)->fetchColumn();
        $totalPages = ceil($totalResults / $perPage);

        return $totalPages;
    }

    public function deleteCurriculum($id): void
    {
        $sql = "DELETE FROM Curriculum WHERE id = ?;";
        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(1, $id);
        $statement->execute();
    }

    public function updateCurriculum($id, Curriculum $curriculum): void
    {
        $sql = "UPDATE CURRICULUM
                SET name = ?,
                    age = ?,
                    qualifications = ?,
                    contact = ?,
                    github = ?
                WHERE id = ?;";

        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(1, $curriculum->getName());
        $statement->bindValue(2, $curriculum->getAge());
        $statement->bindValue(3, $curriculum->getQualifications());
        $statement->bindValue(4, $curriculum->getContact());
        $statement->bindValue(5, $curriculum->getGithub());
        $statement->bindValue(6, $id);
        $statement->execute();
    }

}