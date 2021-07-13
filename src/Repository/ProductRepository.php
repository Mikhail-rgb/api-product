<?php
declare(strict_types=1);

namespace App\Repository;

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
        $product = new Product($sku, $title, $amount, $currency, $type);

        $this->save($product);

        return $product;
    }

    public function findBySKU(string $sku): Product
    {
        $products = $this->findBy(['sku' => $sku]);

        if (!$products) {
            throw new RuntimeException();
        }

        if (count($products) > 1) {
            throw new RuntimeException('More than 1');
        }

        return $products[0];
    }

    public function updateById(string $id, string $newTitle): Product
    {
        $product = $this->find($id);

        if (!$product) {
            throw new RuntimeException();
        }

        $product->setTitle($newTitle);

        $this->save($product);

        return $product;
    }

    private function save(Product $product): void
    {
        $this->_em->persist($product);
        $this->_em->flush();
    }
}
