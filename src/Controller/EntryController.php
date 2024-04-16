<?php

namespace App\Controller;

use App\IndexCsvDb;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class EntryController extends AbstractController
{
    private IndexCsvDb $indexCsvDb;

    public function __construct(IndexCsvDb $indexCsvDb)
    {
        $this->indexCsvDb = $indexCsvDb;
    }

    #[Route('/entry/{id}', name: 'entry')]
    public function handleRequest(int $id): Response
    {
        if (PHP_INT_MAX < $id) {
            throw new Exception('Invalid id parameter');
        }

        $entry = $this->indexCsvDb->getEntryById($id);

        return $this->render('entry.html.twig', ['entry' => $entry]);
    }
}