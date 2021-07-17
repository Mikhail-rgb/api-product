<?php
declare(strict_types=1);

namespace App\Repository;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use RuntimeException;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    //max amount of elements on page
    private int $pageSize = 5;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function create(string $sku, string $title, int $amount, string $currency, string $type): Product
    {
        //If products with different types have the same SKU -> error
        $this->checkSkuAndType($sku, $type);

        $product = new Product($sku, $title, $amount, $currency, $type);

        $this->save($product);

        return $product;
    }

    private function checkSkuAndType(string $sku, string $type): void
    {
        $products = $this->findBy(['sku' => $sku]);
        $productsAmount = count($products);

        if($productsAmount >= 1)
        {
            foreach ($products as $product)
            {
                if($product->getType() != $type)
                {
                    throw new RuntimeException('The same SKU with different types', 5);
                }
            }
        }
    }

    public function findBySKU(string $sku): array
    {
        $products = $this->findBy(['sku' => $sku]);

        if (!$products) {
            throw new RuntimeException('Product sku not found', 6);
        }

        return $products;
    }

    public function findByID(int $id): Product
    {
        $product = $this->find($id);

        if (!$product) {
            throw new RuntimeException('Product id not found', 6);
        }

        return $product;
    }

    public function updateById(int $id, array $body): Product
    {
        $product = $this->find($id);

        if (!$product) {
            throw new RuntimeException('Product id not found', 6);
        }

        $this->update($product, $body);
        $this->save($product);

        return $product;
    }

    public function updateBySKU(string $sku, array $body): array
    {
        $products = $this->findBy(['sku' => $sku]);

        if (!$products) {
            throw new RuntimeException('Product sku not found', 6);
        }

        foreach($products as $product)
        {
            $this->update($product, $body);
            $this->save($product);
        }

        return $products;
    }

    public function deleteByID(int $id): void
    {
        $product = $this->find($id);

        if (!$product) {
            throw new RuntimeException('Product id not found', 6);
        }

        $this->delete($product);
    }

    public function deleteBySKU(string $sku): void
    {
        $products = $this->findBy(['sku' => $sku]);

        if (!$products) {
            throw new RuntimeException('Product sku not found', 6);
        }

        foreach($products as $product)
        {
            $this->delete($product);
        }
    }

    private function update(Product $product, array $body):void
    {
        foreach($body as $key => $value)
        {
            switch ($key)
            {
                case "sku":
                    $this->checkSkuAndType($value, $product->getType());
                    $product->setSku($value);
                    break;
                case "title": $product->setTitle($value); break;
                case "amount": $product->setAmount((int)$value); break;
                case "currency": $product->setCurrency($value); break;
                case "type":
                    $this->checkSkuAndType($product->getSku(), $value);
                    $product->setType($value);
                    break;

                default:
                    throw new RuntimeException('Unknown type', 7);
            }
        }
    }

    private function delete(Product $product): void
    {
        $this->_em->remove($product);
        $this->_em->flush();
    }

    private function save(Product $product): void
    {
        $this->_em->persist($product);
        $this->_em->flush();
    }

    public function getProductsArray(): array
    {
        $products = $this->findAll();
        if(!$products)
        {
            throw new RuntimeException('Products not found' , 6);
        }

        return $products;
    }

    /**
     * @return int
     */
    public function getPageSize(): int
    {
        return $this->pageSize;
    }

    public function paginationProducts(array $products, \Knp\Component\Pager\PaginatorInterface $paginator,
                                       int $page): \Knp\Component\Pager\Pagination\PaginationInterface
    {
        return $paginator->paginate(
            $products,
            $page,
            $this->getPageSize()
        );

    }

}
