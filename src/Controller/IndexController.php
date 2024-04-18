<?php

namespace App\Controller;

use App\IndexCsvDb;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class IndexController extends AbstractController
{
    private IndexCsvDb $indexCsvDb;

    public function __construct(IndexCsvDb $indexCsvDb)
    {
        $this->indexCsvDb = $indexCsvDb;
    }

    #[Route('/', name: 'index')]
    public function handleRequest(Request $request): Response
    {
        $searchTerm = $request->query->get('search_term') ?? '';
        $searchTerm = substr($searchTerm, 0, 200);

        $sorting = $request->query->get('sorting') ?? 'by_modified';

        $entriesPerSite = 10;
        $offset = (int) ($request->query->get('offset') ?? 0);
        $amountOfMatchingEntries = $this->indexCsvDb->getAmountOfMatchingEntries($searchTerm) - $offset;

        $list = $this->indexCsvDb->getList(
            $searchTerm,
            $sorting,
            $offset
        );

        return $this->render('index.html.twig', [
            'index_entries' => $list,
            'sorting' => $sorting,
            'search_term' => $searchTerm,
            'show_next_link' => $offset + $entriesPerSite < $amountOfMatchingEntries,
            'next_page_link' => '?search_term='.$searchTerm.'&sorting='.$sorting.'&offset='.($offset + $entriesPerSite),
            'next_offset' => $offset + $entriesPerSite,
            'show_prev_link' => 0 < $offset,
            'prev_page_link' => '?search_term='.$searchTerm.'&sorting='.$sorting.'&offset='.($offset - $entriesPerSite),
            'prev_offset' => $offset - $entriesPerSite,
        ]);
    }
}