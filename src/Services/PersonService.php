<?php

namespace App\Services;

use App\Library\SwapiPlugin;
use App\Transform\PersonTransformer;
use App\Transform\DetailTransformer;
use App\Repository\PeopleRepository;
use App\Entity\People;
use Doctrine\ORM\EntityManagerInterface;

class PersonService
{
    private $plugin;
    private $transformer;
    private $detailTransformer;
    private $repository;
    private $entityManager;

    public function __construct(
        SwapiPlugin $plugin,
        PersonTransformer $transformer,
        DetailTransformer $detailTransformer,
        PeopleRepository $repository,
        EntityManagerInterface $entityManager
    ) {
        $this->plugin = $plugin;
        $this->transformer = $transformer;
        $this->detailTransformer = $detailTransformer;
        $this->repository = $repository;
        $this->entityManager = $entityManager;
    }

    public function list($name)
    {
        $response = $this->plugin->consultName($name);
        if (method_exists($response, "getBody")) {
            $body = json_decode($response->getBody()->getContents(), true);
            $results = $body['results'] ?? [];
            $results = $this->transformer->transform($results);
            return $results;
        }
        return [];
    }

    public function listDb()
    {
        $list = $this->repository->findAll();
        return $list;
    }

    public function detail($url)
    {
        $response = $this->plugin->consult($url);
        if (method_exists($response, "getBody")) {
            $body = json_decode($response->getBody()->getContents(), true);
            $results = $this->detailTransformer->transform($body);
            return $results;
        }
        return [];
    }

    public function save($url)
    {
        $results = $this->detail($url);

        $url = $results[0]['url'] ?? false;
        $name = $results[0]['name'] ?? false;

        $find = $this->repository->findOneBy(
            ['url' => $url]
        );

        if (!$find) {
            $people = new People();
            $people->setName($name);
            $people->setUrl($url);
            $this->entityManager->persist($people);
            $this->entityManager->flush();
        }

        return;
    }

    public function delete($url)
    {
        $results = $this->detail($url);

        $url = $results[0]['url'] ?? false;

        $find = $this->repository->findOneBy(
            ['url' => $url]
        );

        if ($find) {
            $this->entityManager->remove($find);
            $this->entityManager->flush();
        }

        return;
    }
}
