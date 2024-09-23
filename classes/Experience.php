<?php

namespace classes;

class Experience {
    private $id;
    private $curriculumId;
    private $companyName;
    private $position;
    private $admissionDate;
    private $dismissalDate;
    private $description;

    public function __construct($curriculumId, $companyName, $position, $admissionDate, $dismissalDate, $description) {
        $this->curriculumId = $curriculumId;
        $this->companyName = $companyName;
        $this->position = $position;
        $this->admissionDate = $admissionDate;
        $this->dismissalDate = $dismissalDate;
        $this->description = $description;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getCurriculumId()
    {
        return $this->curriculumId;
    }

    public function setCurriculumId($curriculumId)
    {
        $this->curriculumId = $curriculumId;
    }

    public function getCompanyName()
    {
        return $this->companyName;
    }

    public function setCompanyName($companyName)
    {
        $this->companyName = $companyName;
    }

    public function getPosition()
    {
        return $this->position;
    }

    public function setPosition($position)
    {
        $this->position = $position;
    }

    public function getAdmissionDate()
    {
        return $this->admissionDate;
    }

    public function setAdmissionDate($admissionDate)
    {
        $this->admissionDate = $admissionDate;
    }

    public function getDismissalDate()
    {
        return $this->dismissalDate;
    }

    public function setDismissalDate($dismissalDate)
    {
        $this->dismissalDate = $dismissalDate;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }
}