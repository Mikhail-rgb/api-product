<?php
declare(strict_types=1);

namespace App\Repository;

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
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function create(string $sku, string $title, int $amount, string $currency, string $type): Product
    {
        //If products with different types have the same SKU -> error
        //$this->checkSKU($sku, $type);

        $product = new Product($sku, $title, $amount, $currency, $type);

        $this->save($product);

        return $product;
    }

    private function checkSKU(string $sku, string $type): void
    {
        $products = $this->findBy(['sku' => $sku]);
        $productsAmount = count($products);

        if($productsAmount > 1)
        {
            foreach ($products as $product)
            {
                if($product['type'] != $type)
                {
                    throw new RuntimeException('The same SKU with different types');
                }
            }
        }
    }

    public function findBySKU(string $sku): array
    {
        $products = $this->findBy(['sku' => $sku]);

        if (!$products) {
            throw new RuntimeException('Product sku not found');
        }

        return $products;
    }

    public function findByID(int $id): Product
    {
        $product = $this->find($id);

        if (!$product) {
            throw new RuntimeException('Product id not found');
        }

        return $product;
    }

    public function updateById(int $id, string $body): Product
    {
        $product = $this->find($id);

        if (!$product) {
            throw new RuntimeException('Product id not found');
        }

        //$product->setTitle($newTitle);

        $this->save($product);

        return $product;
    }

    public function deleteByID(int $id): void
    {
        $product = $this->find($id);

        if (!$product) {
            throw new RuntimeException('Product id not found');
        }

        $this->delete($product);
    }

    public function deleteBySKU(string $sku): void
    {
        $products = $this->findBy(['sku' => $sku]);

        if (!$products) {
            throw new RuntimeException('Product sku not found');
        }

        foreach($products as $product)
        {
            $this->delete($product);
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
        return $this->findAll();
    }

}
