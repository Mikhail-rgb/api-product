<?php
declare(strict_types=1);

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use RuntimeException;
use App\Enum\ErrorCodeEnum;

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
            throw new RuntimeException(
                'Product creation failed',
                ErrorCodeEnum::CREATION_FAILED
            );
        }

        return $this->id;
    }

    public function setTitle(string $title): void
    {
        if (!$title) {
            throw new RuntimeException(
                'Product title not specified',
                ErrorCodeEnum::PROPERTY_NOT_SPECIFIED
            );
        }

        $this->title = $title;
    }


    public function setAmount(int $amount): void
    {
        if ($amount === null) {
            throw new RuntimeException(
                'Product amount not specified',
                ErrorCodeEnum::PROPERTY_NOT_SPECIFIED
            );
        } else if ($amount < 0) {
            throw new RuntimeException(
                'Product amount cannot be negative',
                ErrorCodeEnum::NEGATIVE_AMOUNT
            );
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
        if (!$type) {
            throw new RuntimeException(
                'Product type not specified',
                ErrorCodeEnum::PROPERTY_NOT_SPECIFIED
            );
        }

        $this->type = $type;
    }


    public function setSku(string $sku): void
    {
        if (!$sku) {
            throw new RuntimeException(
                'Product SKU not specified',
                ErrorCodeEnum::PROPERTY_NOT_SPECIFIED
            );
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
        if (!$currency) {
            throw new RuntimeException(
                'Product currency not specified',
                ErrorCodeEnum::PROPERTY_NOT_SPECIFIED
            );
        }

        $usableCurrency = array(
            "rub" => "rub",
            "usd" => "usd",
            "eur" => "eur",
        );

        if (!in_array($currency, $usableCurrency)) {
            throw new RuntimeException(
                sprintf(
                    'Unknown currency. Expected one of this: `%s`, `%s`, `%s`. Input currency `%s`.',
                    $usableCurrency['rub'],
                    $usableCurrency['usd'],
                    $usableCurrency['eur'],
                    $currency
                ),
                ErrorCodeEnum::UNKNOWN_PROPERTY
            );
        }
    }
}
