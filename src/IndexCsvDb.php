<?php

declare(strict_types=1);

namespace App;

use Exception;
use PDO;
use PDOException;
use SplFileObject;

class IndexCsvDb
{
    protected PDO $db;

    /**
     * @throws \PDOException
     */
    public function __construct()
    {
        // create/open SQLite file
        $this->db = new PDO('sqlite:'.__DIR__.'/../index.db');

        $this->initDbFile();
    }

    private function initDbFile(): void
    {
        $this->db->exec('CREATE TABLE IF NOT EXISTS entry (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            ontology_title TEXT,
            ontology_iri TEXT UNIQUE,
            summary TEXT,
            license_information TEXT,
            authors TEXT,
            contributors TEXT,
            project_page TEXT,
            source_page TEXT,
            latest_json_ld_file TEXT,
            latest_n3_file TEXT,
            latest_ntriples_file TEXT,
            latest_rdfxml_file TEXT,
            latest_turtle_file TEXT,
            modified TEXT,
            version TEXT,
            source_title TEXT,
            source_url TEXT
        )');

        // get amount of entries
        $stmt = $this->db->prepare('SELECT COUNT(id) FROM entry');
        $stmt->execute();

        if (0 == $stmt->fetchColumn()) {
            /*
             * load index.csv into index.db
             */
            $i = 0;
            $file = new SplFileObject(__DIR__.'/../index.csv');
            $file->setFlags(SplFileObject::READ_CSV);
            $file->setCsvControl(',', '"', '\\'); // this is the default anyway though
            foreach ($file as $row) {
                ++$i;
                if ($i == 1) {
                    // ignore header
                    continue;
                }

                if (false === isset($row[1])) {
                    continue;
                }

                $stmt = $this->db->prepare(
                    'INSERT INTO entry (
                        ontology_title,
                        ontology_iri,
                        summary,
                        authors,
                        contributors,
                        license_information,
                        project_page,
                        source_page,
                        latest_json_ld_file,
                        latest_n3_file,
                        latest_ntriples_file,
                        latest_rdfxml_file,
                        latest_turtle_file,
                        modified,
                        version,
                        source_title,
                        source_url
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);'
                );
                $stmt->execute($row);
            }
        }
    }

    /**
     * @return array<mixed>
     */
    public function getEntryById(int $id): array
    {
        $sql = 'SELECT * FROM entry WHERE id = ?';

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);

        $entry = $stmt->fetch();
        $entry['ontology_title'] = stripslashes($entry['ontology_title']);

        return $entry;
    }

    public function getAmountOfMatchingEntries(string $searchTerm): int
    {
        $sql = 'SELECT COUNT(id)
                  FROM entry
                 WHERE ontology_title LIKE ? OR summary LIKE ? OR ontology_iri LIKE ?';

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            '%'.$searchTerm.'%',
            '%'.$searchTerm.'%',
            '%'.$searchTerm.'%',
        ]);

        return (int) $stmt->fetchColumn();
    }

    /**
     * @return iterable
     */
    public function getList(string $searchTerm, string $sorting, int $offset): iterable
    {
        if ('by_modified' == $sorting) {
            $orderByPart = 'ORDER BY modified DESC';
        } else {
            $orderByPart = 'ORDER BY ontology_title ASC';
        }

        $sql = 'SELECT *
                  FROM entry
                 WHERE ontology_title LIKE ? OR summary LIKE ? OR ontology_iri LIKE ?
                '.$orderByPart.'
                 LIMIT ?, 10';

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            '%'.$searchTerm.'%',
            '%'.$searchTerm.'%',
            '%'.$searchTerm.'%',
            $offset
        ]);

        $result = [];
        foreach ($stmt->fetchAll() as $entry) {
            $entry['ontology_title'] = stripslashes($entry['ontology_title']);
            $result[] = $entry;
        }

        return $result;
    }
}
