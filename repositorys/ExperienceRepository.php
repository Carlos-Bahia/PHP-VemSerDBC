<?php

namespace src\repositorys;

include '../classes/Experience.php';
include '../database/db.php';

use src\classes\Experience;

class ExperienceRepository
{
    private \PDO $pdo;

    public function __construct()
    {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function formExperience(array $experience): Experience
    {
        return new Experience(
          $experience['id_curriculum'],
          $experience['company_name'],
          $experience['position'],
          $experience['admission_date'],
          $experience['dismissal_date'],
          $experience['description'],
          $experience['id']
        );
    }

    public function createExperience(Experience $experience): Experience
    {
        $sql = "INSERT INTO Experience (id_curriculum, company_name, position, admission_date, dismissal_date, description) VALUES (?, ?, ?, ?, ?, ?);";
        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(1, $experience->getCurriculumId());
        $statement->bindValue(2, $experience->getCompanyName());
        $statement->bindValue(3, $experience->getPosition());
        $statement->bindValue(4, $experience->getAdmissionDate());
        if($experience->getDismissalDate() !== null && $experience->getDismissalDate() !== "" && $experience->getDismissalDate() !== "0000-00-00") {
            $statement->bindValue(5, $experience->getDismissalDate());
        } else {
            $statement->bindValue(5, null);
        }
        $statement->bindValue(6, $experience->getDescription());
        $statement->execute();

        $experience->setId($this->pdo->lastInsertId());
        return $experience;
    }

    public function getExperiencesByCurriculumId(int $id): array
    {
        $sql = "SELECT * FROM Experience WHERE id_curriculum = ?;";
        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(1, $id);
        $statement->execute();

        $exps = $statement->fetchAll(\PDO::FETCH_ASSOC);

        $expArray = array_map( function ($item) {
            return $this->formExperience($item);
        }, $exps);

        return $expArray;
    }

    public function deleteExperience(int $id): void
    {
        $sql = "DELETE FROM Experience WHERE id_curriculum = ?;";
        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(1, $id);
        $statement->execute();
    }

    public function updateExperience(Experience $experience): void
    {
        if($experience->getId() == null || $experience->getId() == "") {
            $this->createExperience($experience);
        } else {
            $sql = "UPDATE Experience
                    SET company_name = ?,
                        position = ?,
                        admission_date = ?,
                        dismissal_date = ?,
                        description = ?
                    WHERE id = ?;";
            $statement = $this->pdo->prepare($sql);
            $statement->bindValue(1, $experience->getCompanyName());
            $statement->bindValue(2, $experience->getPosition());
            $statement->bindValue(3, $experience->getAdmissionDate());
            $statement->bindValue(4, $experience->getDismissalDate());
            $statement->bindValue(5, $experience->getDescription());
            $statement->bindValue(6, $experience->getId());
            $statement->execute();
        }
    }
}