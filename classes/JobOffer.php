<?php

namespace classes;

class JobOffer {
    private $id;
    private $name;
    private $description;
    private $knowledges = [];

    public function __construct($name, $description) {
        $this->name = $name;
        $this->description = $description;
    }

    public function addKnowledge($knowledge) {
        $this->knowledges[] = $knowledge;
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

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
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