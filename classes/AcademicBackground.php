<?php

namespace src\classes;

class AcademicBackground {
    private $id;
    private $curriculumId;
    private $scholarly;
    private $college;
    private $courseName;
    private $status;

    public function __construct($curriculumId, $scholarly, $college, $courseName, $status, $id = null) {
        $this->curriculumId = $curriculumId;
        $this->scholarly = $scholarly;
        $this->college = $college;
        $this->courseName = $courseName;
        $this->status = $status;
        $this->setId($id);
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

    public function getScholarly()
    {
        return $this->scholarly;
    }

    public function setScholarly($scholarly)
    {
        $this->scholarly = $scholarly;
    }

    public function getCollege()
    {
        return $this->college;
    }

    public function setCollege($college)
    {
        $this->college = $college;
    }

    public function getCourseName()
    {
        return $this->courseName;
    }

    public function setCourseName($courseName)
    {
        $this->courseName = $courseName;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }
}