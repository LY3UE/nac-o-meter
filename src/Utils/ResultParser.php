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

    private function getFilePath($year, $band)
    {
        $filename = "${year}_${band}.csv";
        $files = $this->getFilesByPattern($filename);
        $iterator = $files->getIterator();
        $iterator->rewind();
        return $iterator->current()->getRealPath();
    }

    private function getCSVReader($filepath) {
        return Reader::createFromPath($filepath, 'r')
                    ->setHeaderOffset(0)
                    ->setDelimiter(';')
        ;
    }

    public function getMonthResultByCall($call,$year,$month,$band)
    {
        $filepath = $this->getFilePath($year,$band);
        $reader = $this->getCSVReader($filepath);

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
        $files = $this->getFilesByPattern('*.csv');
        foreach ($files as $f) {
            $name = $f->getFileName();
            $years[] = \preg_split('/_/',$name)[0];
        }
        rsort($years);
        return array_unique($years);
    }

    private function getFilesByPattern($pattern)
    {
        $finder = new Finder();
        return $finder->files()->in('../results/')->name($pattern);
    }
}
