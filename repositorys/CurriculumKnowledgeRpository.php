<?php

namespace src\repositorys;

include '../classes/CurriculumKnowledge.php';
include '../database/db.php';

use src\classes\CurriculumKnowledge;

class CurriculumKnowledgeRpository
{
    private \PDO $pdo;

    public function __construct()
    {
        global $pdo;
        $this->pdo = $pdo;
    }

    private function formCurriculumKnowledge(array $curriculumKnowledge): CurriculumKnowledge
    {
        return new CurriculumKnowledge(
            $curriculumKnowledge['id_curriculum'],
            $curriculumKnowledge['knowledge']
        );
    }

    public function createCurriculumKnowledge(CurriculumKnowledge $curriculumKnowledge): CurriculumKnowledge
    {
        $sql = "INSERT INTO Curriculum_knowledge (id_curriculum, knowledge) VALUES (?, ?);";
        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(1, $curriculumKnowledge->getCurriculumId());
        $statement->bindValue(2, $curriculumKnowledge->getKnowledge());
        $statement->execute();

        return $curriculumKnowledge;
    }

    public function getKnowledgesByCurriculumId(int $id): array
    {
        $sql = "SELECT * FROM Curriculum_knowledge WHERE id_curriculum = ?;";
        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(1, $id);
        $statement->execute();

        $knowledges = $statement->fetchAll(\PDO::FETCH_ASSOC);

        $array = array_map( function ($item) {
            return $this->formCurriculumKnowledge($item);
        }, $knowledges);

        return $array;
    }

    public function deleteCurriculumKnowledge(int $id): void
    {
        $sql = "DELETE FROM Curriculum_knowledge WHERE id_curriculum = ?;";
        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(1, $id);
        $statement->execute();
    }

    public function deleteCurriculumKnowledgeUnique(CurriculumKnowledge $knowledge): void
    {
        $sql = "DELETE FROM Curriculum_knowledge WHERE id_curriculum = ? AND knowledge = ?;";
        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(1, $knowledge->getCurriculumId());
        $statement->bindValue(2, $knowledge->getKnowledge());
        $statement->execute();
    }

    public function updateKnowledgesFromCurriculumId(int $id, array $knowledges): void
    {
        $knowledgesFromCurriculum = $this->getKnowledgesByCurriculumId($id);

        $existingKnowledges = [];
        foreach ($knowledgesFromCurriculum as $curriculumKnowledge) {
            $existingKnowledges[$curriculumKnowledge->getKnowledge()] = $curriculumKnowledge;
        }

        $knowledgeToAdd = [];
        $knowledgeToDelete = $knowledgesFromCurriculum;

        foreach ($knowledges as $knowledge) {
            $knowledgeKey = $knowledge->getKnowledge();

            if (isset($existingKnowledges[$knowledgeKey])) {
                unset($knowledgeToDelete[array_search($existingKnowledges[$knowledgeKey], $knowledgeToDelete)]);
            } else {
                array_push($knowledgeToAdd, $knowledge);
            }
        }

        foreach ($knowledgeToDelete as $knowledge) {
            $this->deleteCurriculumKnowledgeUnique($knowledge);
        }

        foreach ($knowledgeToAdd as $knowledge) {
            $this->createCurriculumKnowledge($knowledge);
        }
    }

}