<?php
declare(strict_types=1);

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\SerializerInterface;


class ApiController extends AbstractController
{
    private ProductRepository $productRepository;
    private SerializerInterface $serializer;

    public function __construct(ProductRepository $productRepository, SerializerInterface $serializer)
    {
        $this->productRepository = $productRepository;
        $this->serializer = $serializer;
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

    /**
     * @Route("/api/product/del/id/{id}", name="product_delete_by_id", methods={"DELETE"})
     */
    public function deleteProductByID(int $id): Response
    {
        $this->productRepository->deleteByID($id);

        return new JsonResponse(['message' => "Product with id = $id deleted"]);
    }

    /**
     * @Route("/api/product/del/sku/{sku}", name="product_delete_by_sku", methods={"DELETE"})
     */
    public function deleteProductBySKU(string $sku): Response
    {
        $this->productRepository->deleteBySKU($sku);

        return new JsonResponse(['message' => "Product(s) with sku = $sku deleted"]);
    }


    /**
     * @Route("/api/product/update/id/{id}", name="product_update_by_id", methods={"PUT"})
     */
    public function updateProductByID(Request $request, int $id): Response
    {
        $body = json_decode((string)$request->getContent(), true);

        $product = $this->productRepository->updateById($id, $body);
    }

    /**
     * @Route("/api/product", name="products_return", methods={"GET"})
     */
    public function returnAllProducts(): Response
    {
        $products = $this->productRepository->getProductsArray();
        $productsJson = $this->serializer->serialize($products, 'json');

        return new JsonResponse(json_decode($productsJson, true));
    }

    /**
     * @Route("/api/product/id/{id}", name="product_return_by_id", methods={"GET"})
     */
    public function returnProductByID(int $id): Response
    {
        $product = $this->productRepository->findByID($id);
        $productJson = $this->serializer->serialize($product, 'json');

        return new JsonResponse(json_decode($productJson, true));
    }

    /**
     * @Route("/api/product/sku/{sku}", name="product_return_by_sku", methods={"GET"})
     */
    public function returnProductBySKU(string $sku): Response
    {
        $products = $this->productRepository->findBySKU($sku);
        $productsJson = $this->serializer->serialize($products, 'json');

        return new JsonResponse(json_decode($productsJson, true));
    }
}