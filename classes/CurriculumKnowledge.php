<?php

namespace classes;

class CurriculumKnowledge {
    private $curriculumId;
    private $knowledge;

    public function __construct($curriculumId, $knowledge) {
        $this->curriculumId = $curriculumId;
        $this->knowledge = $knowledge;
    }

    public function getKnowledge()
    {
        return $this->knowledge;
    }

    public function setKnowledge($knowledge)
    {
        $this->knowledge = $knowledge;
    }

    public function getCurriculumId()
    {
        return $this->curriculumId;
    }

    public function setCurriculumId($curriculumId)
    {
        $this->curriculumId = $curriculumId;
    }
}