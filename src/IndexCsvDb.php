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
            latest_access TEXT,
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
                        license_information,
                        authors,
                        contributors,
                        project_page,
                        source_page,
                        latest_json_ld_file,
                        latest_n3_file,
                        latest_ntriples_file,
                        latest_rdfxml_file,
                        latest_turtle_file,
                        latest_access,
                        source_title,
                        source_url
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);'
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

        return $stmt->fetch();
    }

    /**
     * @return iterable
     */
    public function getList(): iterable
    {
        $sql = 'SELECT * FROM entry ORDER BY latest_access DESC LIMIT 30';

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        $result = [];
        foreach ($stmt->fetchAll() as $entry) {
            $entry['ontology_title'] = stripslashes($entry['ontology_title']);
            $result[] = $entry;
        }

        return $result;
    }
}
