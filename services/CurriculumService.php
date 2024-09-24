<?php

namespace src\services;
require '../repositorys/curriculumRepository.php';
require '../repositorys/ExperienceRepository.php';
require '../repositorys/AcademicRepository.php';
require '../repositorys/CurriculumKnowledgeRpository.php';

use src\classes\AcademicBackground;
use src\classes\Curriculum;
use src\classes\CurriculumKnowledge;
use src\classes\Experience;
use src\repositorys\AcademicRepository;
use src\repositorys\CurriculumKnowledgeRpository;
use src\repositorys\CurriculumRepository;
use src\repositorys\ExperienceRepository;

class CurriculumService
{
    private static $singleton;

    private $curriculumRepository;
    private $academicRepository;
    private $knowledgeRepository;
    private $experienceRepository;

    public function __construct()
    {
        $this->curriculumRepository = new CurriculumRepository();
        $this->academicRepository = new AcademicRepository();
        $this->experienceRepository = new ExperienceRepository();
        $this->knowledgeRepository = new CurriculumKnowledgeRpository();
    }

    public static function getInstance(){
        if(self::$singleton == null){
            self::$singleton = new self();
        }

        return self::$singleton;
    }

    //Auxiliar

    public static function formatContact($numero) {
        $numero = preg_replace('/\D/', '', $numero);

        if (strlen($numero) == 10) {
            return preg_replace('/(\d{2})(\d{4})(\d{4})/', '($1) $2-$3', $numero);
        } elseif (strlen($numero) == 11) {
            return preg_replace('/(\d{2})(\d{1})(\d{4})(\d{4})/', '($1) $2$3-$4', $numero);
        }

        return $numero;
    }

    //Curriculum.php

    public function getCurriculumsPaginated(int $page, int $perPage): array
    {
        return $this->curriculumRepository->getCurriculumPaginated($page, $perPage);
    }

    public function getTotalPages(int $page, int $perPage): int
    {
        return $this->curriculumRepository->getTotalPages($page, $perPage);
    }

    //Curriculum-create.php

    public function createCompleteCurriculum(array $postData): int
    {

        $curriculum = new Curriculum($postData['name'], $postData['age'], $postData['qualifications'], $postData['contact'], $postData['github']);
        $curriculum = $this->curriculumRepository->createCurriculum($curriculum);

        foreach ($postData['academic'] as $academic) {
            $academicBackground = new AcademicBackground($curriculum->getId(), $academic['scholarly'], $academic['college'], $academic['course_name'], $academic['status']);
            $academicBackground = $this->academicRepository->createAcademicBackground($academicBackground);
            $curriculum->addAcademicBackground($academicBackground);
        }

        foreach ($postData['experience'] as $experience) {
            $exp = new Experience($curriculum->getId(), $experience['company_name'], $experience['position'], $experience['admission_date'], $experience['dismissal_date'], $experience['description']);
            $exp = $this->experienceRepository->createExperience($exp);
            $curriculum->addExperience($exp);
        }

        foreach ($postData['knowledge'] as $knowledge) {
            $curriculum_knowledge = new CurriculumKnowledge($curriculum->getId(), $knowledge);
            $curriculum_knowledge = $this->knowledgeRepository->createCurriculumKnowledge($curriculum_knowledge);
            $curriculum->addKnowledge($curriculum_knowledge);
        }

        return $curriculum->getId();
    }

    //curriculum-update.php POST

    public function updateCurriculum(int $curriculumId, array $postData): void
    {
        $updateCurriculum = new Curriculum($postData['name'], $postData['age'], $postData['qualifications'], $postData['contact'], $postData['github'], $curriculumId);
        $this->curriculumRepository->updateCurriculum($curriculumId, $updateCurriculum);

        foreach ($postData['academic'] as $academic) {
            $academicUpdate = new AcademicBackground($curriculumId, $academic['scholarly'], $academic['college'], $academic['course_name'], $academic['status'], $academic['id']);
            $this->academicRepository->updateAcademicBackground($academicUpdate);
        }

        foreach ($postData['experience'] as $experience) {
            $experienceUpdate = new Experience($curriculumId, $experience['company_name'], $experience['position'], $experience['admission_date'], $experience['dismissal_date'], $experience['description'],  $experience['id']);
            $this->experienceRepository->updateExperience($experienceUpdate);
        }

        $knowledgesToUpdate = [];
        foreach ($postData['knowledge'] as $knowledge) {
            $knowledgeUpdate = new CurriculumKnowledge($curriculumId, $knowledge);
            array_push($knowledgesToUpdate, $knowledgeUpdate);
        }
        $this->knowledgeRepository->updateKnowledgesFromCurriculumId($curriculumId, $knowledgesToUpdate);
    }

    //curriculum-update.php /GET
    //curriculum-details.php /GET

    public function getCurriculumById(int $curriculumId): Curriculum
    {
        return $this->curriculumRepository->getCurriculumById($curriculumId);
    }

    public function getAcademicBackgroundByCurriculumId(int $curriculumId): array
    {
        return $this->academicRepository->getAcademicBackgroundByCurriculumId($curriculumId);
    }

    public function getExperiencesByCurriculumId(int $curriculumId): array
    {
        return $this->experienceRepository->getExperiencesByCurriculumId($curriculumId);
    }

    public function getKnowledgesByCurriculumId(int $curriculumId): array
    {
        return $this->knowledgeRepository->getKnowledgesByCurriculumId($curriculumId);
    }

    //curriculum-details.php //DELETE
    public function deleteCurriculum(int $curriculumId): void
    {
        $this->academicRepository->deleteByCurriculumId($curriculumId);
        $this->experienceRepository->deleteExperience($curriculumId);
        $this->knowledgeRepository->deleteCurriculumKnowledge($curriculumId);
        $this->curriculumRepository->deleteCurriculum($curriculumId);
    }

}