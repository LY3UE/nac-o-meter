<?php

namespace App\Utils;

use App\Kernel;
use League\Csv\Reader;
use League\Csv\Statement;
use League\Csv\CharsetConverter;
use Symfony\Component\Finder\Finder;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ResultParser
{
    public function __construct()
    {

    }

    public function listResultFiles()
    {
        return $this->getResultFiles();
    }

    public function getMonthResultByCall($call,$year,$month,$band)
    {
        $filename = "${year}_${band}.csv";
        $finder = new Finder();
        $files = $finder->files()->in('../results/')->name($filename);
        $iterator = $finder->getIterator();
        $iterator->rewind();
        $filepath = $iterator->current()->getRealPath();
        $reader = Reader::createFromPath($filepath, 'r')
                    ->setHeaderOffset(0)
        ;
        $reader->setDelimiter(';');

        foreach ($reader as $record) {
                if ($record[array_keys($record)[0]] == $call) {
                    return $record[$month];
                }
        }
        return null;
    }

    public function getAllYears()
    {
        $years = Array();
        $files = $this->getResultFiles();
        foreach ($files as $f) {
            $name = $f->getFileName();
            $years[] = \preg_split('/_/',$name)[0];
        }
        rsort($years);
        return array_unique($years);
    }

    private function getResultFiles()
    {
        $finder = new Finder();
        return $finder->files()->in('../results/')->name('*.csv');
    }
}
