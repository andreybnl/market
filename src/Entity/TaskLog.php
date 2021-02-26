<?php

namespace App\Entity;

use App\Repository\TaskLogRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TaskLogRepository::class)
 */
class TaskLog
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $task_code;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $startTime;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $requestSize;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $records;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $recordsProcessed;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $recordsError;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $schedule;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $end_time;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $duration;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $status;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTaskCode(): ?string
    {
        return $this->task_code;
    }

    public function setTaskCode(?string $task_code): self
    {
        $this->task_code = $task_code;

        return $this;
    }

    public function getStartTime(): ?\DateTimeInterface
    {
        return $this->startTime;
    }

    public function setStartTime(?\DateTimeInterface $startTime): self
    {
        $this->startTime = $startTime;

        return $this;
    }

    public function getRequestSize(): ?int
    {
        return $this->requestSize;
    }

    public function setRequestSize(?int $requestSize): self
    {
        $this->requestSize = $requestSize;

        return $this;
    }

    public function getRecords(): ?int
    {
        return $this->records;
    }

    public function setRecords(?int $records): self
    {
        $this->records = $records;

        return $this;
    }

    public function getRecordsProcessed(): ?int
    {
        return $this->recordsProcessed;
    }

    public function setRecordsProcessed(?int $recordsProcessed): self
    {
        $this->recordsProcessed = $recordsProcessed;

        return $this;
    }

    public function getRecordsError(): ?int
    {
        return $this->recordsError;
    }

    public function setRecordsError(?int $recordsError): self
    {
        $this->recordsError = $recordsError;

        return $this;
    }

    public function getSchedule(): ?string
    {
        return $this->schedule;
    }

    public function setSchedule(?string $schedule): self
    {
        $this->schedule = $schedule;

        return $this;
    }

    public function getEndTime(): ?\DateTimeInterface
    {
        return $this->end_time;
    }

    public function setEndTime(?\DateTimeInterface $end_time): self
    {
        $this->end_time = $end_time;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(?int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }
}
