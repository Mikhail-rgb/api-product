<?php
declare(strict_types=1);

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiController
{
    private ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * @Route("/api/product", name="product_create", methods={"POST"})
     */
    public function createProduct(Request $request): Response
    {
        $body = json_decode((string)$request->getContent(), true);

        $product = $this->productRepository->create(
            $body['sku'],
            $body['title'],
            $body['amount'],
            $body['currency'],
            $body['type'],
        );

        return new JsonResponse(['id' => $product->getId()]);
    }
}