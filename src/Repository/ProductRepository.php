<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\Product;
use App\Enum\ErrorCodeEnum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use RuntimeException;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    private int $pageSize = 5;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function create(string $sku, string $title, int $amount, string $currency, string $type): Product
    {
        $this->checkSkuAndType($sku, $type);

        $product = new Product($sku, $title, $amount, $currency, $type);

        $this->save($product);

        return $product;
    }

    private function checkSkuAndType(string $sku, string $type): void
    {
        $product = $this->findOneBy(['sku' => $sku]);

        if ($product && $product->getType() !== $type) {
            throw new RuntimeException(
                sprintf(
                    'Products with SKU `%s` has type `%s`.',
                    $sku,
                    $product->getType()
                ),
                ErrorCodeEnum::SAME_SKU_DIFFERENT_TYPES
            );
        }
    }

    /**
     * @param string $sku
     * @return Product[]
     */
    public function findBySKU(string $sku): array
    {
        $products = $this->findBy(['sku' => $sku]);

        if (!$products) {
            throw new RuntimeException(
                'Product sku not found',
                ErrorCodeEnum::PROPERTY_NOT_FOUND
            );
        }

        return $products;
    }

    public function findByID(int $id): Product
    {
        $product = $this->find($id);

        if (!$product) {
            throw new RuntimeException(
                'Product id not found',
                ErrorCodeEnum::PROPERTY_NOT_FOUND
            );
        }

        return $product;
    }

    public function updateById(int $id, array $body): Product
    {
        $product = $this->find($id);

        if (!$product) {
            throw new RuntimeException(
                'Product id not found',
                ErrorCodeEnum::PROPERTY_NOT_FOUND
            );
        }

        $this->update($product, $body);
        $this->save($product);

        return $product;
    }

    /**
     * @param string $sku
     * @param array $body
     * @return Product[]
     */
    public function updateBySKU(string $sku, array $body): array
    {
        $products = $this->findBy(['sku' => $sku]);

        if (!$products) {
            throw new RuntimeException(
                'Product sku not found',
                ErrorCodeEnum::PROPERTY_NOT_FOUND
            );
        }

        foreach ($products as $product) {
            $this->update($product, $body);
            $this->save($product);
        }

        return $products;
    }

    public function deleteByID(int $id): void
    {
        $product = $this->find($id);

        if (!$product) {
            throw new RuntimeException(
                'Product id not found',
                ErrorCodeEnum::PROPERTY_NOT_FOUND
            );
        }

        $this->delete($product);
    }

    public function deleteBySKU(string $sku): void
    {
        $products = $this->findBy(['sku' => $sku]);

        if (!$products) {
            throw new RuntimeException(
                'Product sku not found',
                ErrorCodeEnum::PROPERTY_NOT_FOUND
            );
        }

        foreach ($products as $product) {
            $this->delete($product);
        }
    }

    private function update(Product $product, array $body): void
    {
        foreach ($body as $key => $value) {
            switch ($key) {
                case "sku":
                    $this->checkSkuAndType($value, $product->getType());
                    $product->setSku($value);
                    break;
                case "title":
                    $product->setTitle($value);
                    break;
                case "amount":
                    $product->setAmount((int)$value);
                    break;
                case "currency":
                    $product->setCurrency($value);
                    break;
                case "type":
                    $this->checkSkuAndType($product->getSku(), $value);
                    $product->setType($value);
                    break;

                default:
                    throw new RuntimeException(
                        'Unknown property',
                        ErrorCodeEnum::UNKNOWN_PROPERTY
                    );
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

    /**
     * @return Product[]
     */
    public function getProductsArray(): array
    {
        $products = $this->findAll();
        if (!$products) {
            throw new RuntimeException(
                'Products not found',
                ErrorCodeEnum::PROPERTY_NOT_FOUND
            );
        }

        return $products;
    }


    public function getPageSize(): int
    {
        return $this->pageSize;
    }

    public function paginationProducts(array $products, PaginatorInterface $paginator,
                                       int $page): PaginationInterface
    {
        return $paginator->paginate(
            $products,
            $page,
            $this->getPageSize()
        );
    }

}
