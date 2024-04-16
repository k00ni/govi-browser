<?php

namespace App\Controller;

use App\IndexCsvDb;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use function App\getDataSources;
use function App\loadIndexIntoArray;

class IndexController extends AbstractController
{
    private IndexCsvDb $indexCsvDb;

    public function __construct(IndexCsvDb $indexCsvDb)
    {
        $this->indexCsvDb = $indexCsvDb;
    }

    #[Route('/', name: 'index')]
    public function handleRequest(): Response
    {
        return $this->render('index.html.twig', [
            'index_entries' => $this->indexCsvDb->getList(),
        ]);
    }
}