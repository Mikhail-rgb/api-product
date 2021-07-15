<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use RuntimeException;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string", length=255, name="sku")
     */
    private string $sku;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $title;

    /**
     * @ORM\Column(type="integer")
     */
    private int $amount;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $currency;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $type;

    public function __construct(string $sku, string $title, int $amount, string $currency, string $type)
    {
        $this->sku = $sku;
        $this->title = $title;
        $this->amount = $amount;
        $this->currency = $currency;
        $this->type = $type;
    }

    public function getId(): int
    {
        if ($this->id === null) {
            throw new RuntimeException('Product creation failed');
        }

        return $this->id;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }


    public function setAmount(int $amount): void
    {
        $this->amount = $amount;
    }

    public function setCurrency(string $currency): void
    {
        $this->currency = $currency;
    }


    public function setType(string $type): void
    {
        $this->type = $type;
    }


    public function setSku(string $sku): void
    {
        $this->sku = $sku;
    }


    public function getSku(): string
    {
        return $this->sku;
    }


    public function getTitle(): string
    {
        return $this->title;
    }


    public function getAmount(): int
    {
        return $this->amount;
    }


    public function getCurrency(): string
    {
        return $this->currency;
    }


    public function getType(): string
    {
        return $this->type;
    }


}
