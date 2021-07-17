<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Boolean;
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
        $this->setSku($sku);
        $this->setTitle($title);
        $this->setAmount($amount);
        $this->setCurrency($currency);
        $this->setType($type);
    }

    public function getId(): int
    {
        if ($this->id === null) {
            throw new RuntimeException('Product creation failed', 1);
        }

        return $this->id;
    }

    public function setTitle(string $title): void
    {
        if(!$title)
        {
            throw new RuntimeException('Product title not specified', 2);
        }

        $this->title = $title;
    }


    public function setAmount(int $amount): void
    {
        if($amount === null)
        {
            throw new RuntimeException('Product amount not specified', 2);
        }
        else if($amount < 0)
        {
            throw new RuntimeException('Product amount cannot be negative', 3);
        }

        $this->amount = $amount;
    }

    public function setCurrency(string $currency): void
    {
        $this->checkCurrency($currency);

        $this->currency = $currency;
    }


    public function setType(string $type): void
    {
        if(!$type)
        {
            throw new RuntimeException('Product type not specified', 2);
        }

        $this->type = $type;
    }


    public function setSku(string $sku): void
    {
        if(!$sku)
        {
            throw new RuntimeException('Product SKU not specified', 2);
        }

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

    private function checkCurrency(string $currency): void
    {
        if(!$currency)
        {
            throw new RuntimeException('Product currency not specified', 2);
        }

        $usableCurrency = array(
        "rub" => "rub",
        "usd" => "usd",
        "eur" => "eur",
        );

        if(!in_array($currency, $usableCurrency))
        {
            throw new RuntimeException('Unusable currency', 4);
        }
    }

}
