<?php

namespace src\classes;

class JobKnowledge {
    private $jobId;
    private $knowledge;

    public function __construct($jobId, $knowledge) {
        $this->jobId = $jobId;
        $this->knowledge = $knowledge;
    }

    public function getJobId()
    {
        return $this->jobId;
    }

    public function setJobId($jobId)
    {
        $this->jobId = $jobId;
    }

    public function getKnowledge()
    {
        return $this->knowledge;
    }

    public function setKnowledge($knowledge)
    {
        $this->knowledge = $knowledge;
    }
}