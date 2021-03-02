<?php

namespace App\Model;

use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;

class Condition
{
    private $filesystem;

    public function __construct(
        Filesystem $filesystem
    )
    {
        $this->filesystem = $filesystem;
    }

    public function createBusy()
    {
        try {
            $this->filesystem->touch('busy.txt');
        }
        catch (IOExceptionInterface $exception) {
            echo "An error occurred while creating your file";
        }
    }

    public function checkBusy()
    {
        return !($this->filesystem->exists('busy.txt'));
    }

    public function deleteBusy()
    {
        $this->filesystem->remove('busy.txt');
    }
}