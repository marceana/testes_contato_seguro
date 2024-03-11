<?php

namespace Contatoseguro\TesteBackend\Controller;

use Contatoseguro\TesteBackend\Service\CompanyService;
use Contatoseguro\TesteBackend\Service\ProductService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ReportController
{
    private ProductService $productService;
    private CompanyService $companyService;
    
    public function __construct()
    {
        $this->productService = new ProductService();
        $this->companyService = new CompanyService();
    }
    
    public function generate(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {   
        $actionTranslations = [
            'create' => 'Criação',
            'update' => 'Atualização',
            'delete' => 'Exclusão'
        ];

        $adminUserId = $request->getHeader('admin_user_id')[0];
        
        $data = [];
        $data[] = [
            'Nome do usuário',
            'Tipo de alteração',
            'Data'
        ];
        
        $stm = $this->productService->getAll($adminUserId);
        $products = $stm->fetchAll();

        foreach ($products as $i => $product) {
            $productLogs = $this->productService->getLog($product->id);
            $stm = $this->companyService->getNameById($product->company_id);
            $companyName = $stm->fetch()->name;
            
            foreach ($productLogs as $log) {
                $action = isset($actionTranslations[$log->action]) ? $actionTranslations[$log->action] : $log->action;
            
                $data[] = [
                    $companyName,
                    $action,
                    $log->timestamp
                ];
            }
        }
    
        $report = "<table style='font-size: 10px;'>";
        foreach ($data as $row) {
            $report .= "<tr>";
            foreach ($row as $column) {
                $report .= "<td>{$column}</td>";
            }
            $report .= "</tr>";
        }
        $report .= "</table>";
    
        $response->getBody()->write($report);
        return $response->withStatus(200)->withHeader('Content-Type', 'text/html');
    }
}
