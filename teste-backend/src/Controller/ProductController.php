<?php

namespace Contatoseguro\TesteBackend\Controller;

use Contatoseguro\TesteBackend\Model\Product;
use Contatoseguro\TesteBackend\Service\CategoryService;
use Contatoseguro\TesteBackend\Service\ProductService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ProductController
{
    private ProductService $service;
    private CategoryService $categoryService;

    public function __construct()
    {
        $this->service = new ProductService();
        $this->categoryService = new CategoryService();
    }

    public function getAll(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $queryParams = $request->getQueryParams();

        $isActive = isset($queryParams['isActive']) ? $queryParams['isActive'] : null;
        $isActiveBoolean = $isActive === 'true' ? true : false;

        $category = isset($queryParams['category']) ? $queryParams['category'] : null;

        $orderByDate = isset($queryParams['orderByDate']) ? $queryParams['orderByDate'] : null;

        $adminUserId = $request->getHeader('admin_user_id')[0];

        if ($isActive !== null) {
            $stm = $this->service->getAllFilteredByIsActive($adminUserId, $isActiveBoolean);
        } elseif ($category !== null) {
            $stm = $this->service->getAllFilteredByCategory($adminUserId, $category);        
        } elseif ($orderByDate !== null) {
            $stm = $this->service->getAllOrderedByDate($adminUserId, $orderByDate);       
        } else {
            $stm = $this->service->getAll($adminUserId);
        }
        $response->getBody()->write(json_encode($stm->fetchAll()));
        return $response->withStatus(200); 
    }
    
    public function getOne(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $stm = $this->service->getOne($args['id']);
        $product = Product::hydrateByFetch($stm->fetch());

        $adminUserId = $request->getHeader('admin_user_id')[0];
        $productCategories = $this->categoryService->getProductCategory($product->id)->fetchAll();

        $fetchedCategories = [];
        foreach ($productCategories as $productCategory) {
            $fetchedCategory = $this->categoryService->getOne($adminUserId, $productCategory->id)->fetch();

            if ($fetchedCategory) {
                $fetchedCategories[] = $fetchedCategory->title;
            }
        }

        $product->setCategories($fetchedCategories);
        $response->getBody()->write(json_encode($product));
        return $response->withStatus(200);
    }

    public function insertOne(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $body = $request->getParsedBody();
        $adminUserId = $request->getHeader('admin_user_id')[0];

        if ($this->service->insertOne($body, $adminUserId)) {
            return $response->withStatus(200);
        } else {
            return $response->withStatus(404);
        }
    }

    public function updateOne(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $body = $request->getParsedBody();
        $adminUserId = $request->getHeader('admin_user_id')[0];

        if ($this->service->updateOne($args['id'], $body, $adminUserId)) {
            return $response->withStatus(200);
        } else {
            return $response->withStatus(404);
        }
    }

    public function deleteOne(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $adminUserId = $request->getHeader('admin_user_id')[0];

        if ($this->service->deleteOne($args['id'], $adminUserId)) {
            return $response->withStatus(200);
        } else {
            return $response->withStatus(404);
        }
    }
}
