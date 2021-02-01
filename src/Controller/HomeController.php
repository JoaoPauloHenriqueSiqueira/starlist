<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Services\PersonService;


class HomeController extends AbstractController
{
    private $service;

    public function __construct(PersonService $service)
    {
        $this->service = $service;
    }
    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        return $this->render('Starwars/index.html.twig', [
            'search' => [],
            'valueSearch' => '',
            'detail' => [],
            'results' => []
        ]);
    }

    /**
     * @Route("/search")
     */
    public function search(Request $request)
    {
        $name = $request->query->get('name');
        if ($name == "") {
            return $this->redirectToRoute('index');
        }

        $results = $this->service->list($name);
        return $this->render('Starwars/index.html.twig', [
            'search' => $results,
            'valueSearch' => $name,
            'detail' => [],
            'results' => []

        ]);
    }

    /**
     * @Route("/list", name="list")
     */
    public function list()
    {
        $results = $this->service->listDb();
        return $this->render('Starwars/index.html.twig', [
            'search' => [],
            'valueSearch' => "",
            'detail' => [],
            'results' => $results,
        ]);
    }

    /**
     * @Route("/detail")
     */
    public function detail(Request $request)
    {
        $url = $request->query->get('url');
        if ($url == "") {
            return $this->redirectToRoute('index');
        }

        $results = $this->service->detail($url);

        return $this->render('Starwars/index.html.twig', [
            'search' => $results,
            'valueSearch' => '',
            'detail' => $results[0] ?? []
        ]);
    }

    /**
     * @Route("/save")
     */
    public function save(Request $request)
    {
        $url = $request->query->get('person');
        
        if ($url == "") {
            return $this->redirectToRoute('index');
        }

        $this->service->save($url);
        return $this->redirectToRoute('list');
    }

    /**
     * @Route("/delete")
     */
    public function delete(Request $request)
    {
        $url = $request->query->get('url');
        if ($url == "") {
            return $this->redirectToRoute('index');
        }

        $this->service->delete($url);
        $this->service->listDb();

        return $this->redirectToRoute('list');
    }
}
