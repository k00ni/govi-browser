<?php

function getEntryFromIndex(string $iri): array|null
{
    foreach (loadIndexIntoArray() as $row) {
        if ($iri == $row[1]) {
            return $row;
        }
    }

    return null;
}

function loadIndexIntoArray(int $maxAmount = 0): array
{
    $i = 0;
    $result = [];

    $file = new SplFileObject('index.csv');
    $file->setFlags(SplFileObject::READ_CSV);
    $file->setCsvControl(',', '"', '\\'); // this is the default anyway though
    foreach ($file as $row) {
        ++$i;
        if ($i == 1) {
            // ignore header
            continue;
        }

        $result[] = $row;

        if (0 < $maxAmount && $i > $maxAmount) {
            break;
        }
    }

    return $result;
}

function getDataSources(): array
{
    $dataSources = [];

    foreach (loadIndexIntoArray() as $row) {
        if (isset($row[8]) && false === isset($dataSources[$row[8]])) {
            $dataSources[$row[8]] = ['name' => $row[7], 'url' => $row[8]];
        }
    }

    usort($dataSources, function($a, $b) {
        return $a['name'] > $b['name'] ? 1 : -1;
    });

    return $dataSources;
}