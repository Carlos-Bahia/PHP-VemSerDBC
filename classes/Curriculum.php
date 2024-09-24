<?php

namespace src\classes;
use classes\Exception;

require '../database/db.php';

class Curriculum {
    private $id;
    private $name;
    private $age;
    private $qualifications;
    private $contact;
    private $github;
    private $academicBackgrounds = [];
    private $experiences = [];
    private $knowledges = [];

    public function __construct($name, $age, $qualifications, $contact, $github = null, $id = null) {
        $this->name = $name;
        $this->age = $age;
        $this->qualifications = $qualifications;
        $this->contact = $contact;
        $this->github = $github;
        $this->id = $id;
    }

    public function delete($pdo) {
        try {
            $pdo->beginTransaction();

            $stmt = $pdo->prepare("DELETE FROM Academic_Background WHERE id_curriculum = ?");
            $stmt->execute([$this->id]);

            $stmt = $pdo->prepare("DELETE FROM Experience WHERE id_curriculum = ?");
            $stmt->execute([$this->id]);

            $stmt = $pdo->prepare("DELETE FROM Curriculum_knowledge WHERE id_curriculum = ?");
            $stmt->execute([$this->id]);

            $stmt = $pdo->prepare("DELETE FROM Curriculum WHERE id = ?");
            $stmt->execute([$this->id]);

            $pdo->commit();
            return true;
        } catch (Exception $e) {
            $pdo->rollBack();
            throw new Exception("Erro ao deletar currículo: " . $e->getMessage());
        }
    }

    public function addAcademicBackground(AcademicBackground $academicBackground) {
        array_push($this->academicBackgrounds, $academicBackground);
    }

    public function addExperience(Experience $experience) {
        array_push($this->experiences, $experience);
    }

    public function addKnowledge($knowledge) {
        array_push($this->knowledges, $knowledge);
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getAge()
    {
        return $this->age;
    }

    public function setAge($age)
    {
        $this->age = $age;
    }

    public function getQualifications()
    {
        return $this->qualifications;
    }

    public function setQualifications($qualifications)
    {
        $this->qualifications = $qualifications;
    }

    public function getContact()
    {
        return $this->contact;
    }

    public function setContact($contact)
    {
        $this->contact = $contact;
    }

    public function getGithub()
    {
        return $this->github;
    }

    public function setGithub($github)
    {
        $this->github = $github;
    }

    public function getAcademicBackgrounds()
    {
        return $this->academicBackgrounds;
    }

    public function setAcademicBackgrounds($academicBackgrounds)
    {
        $this->academicBackgrounds = $academicBackgrounds;
    }

    public function getExperiences()
    {
        return $this->experiences;
    }

    public function setExperiences($experiences)
    {
        $this->experiences = $experiences;
    }

    public function getKnowledges()
    {
        return $this->knowledges;
    }

    public function setKnowledges($knowledges)
    {
        $this->knowledges = $knowledges;
    }

}