<?php

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;

class ProductLowQuantityEvent extends Event
{
    public const NAME = 'product.low_quantity';

    protected $productId;
    protected $productQuantity;

    public function __construct(int $productId, int $productQuantity)
    {
        $this->productId = $productId;
        $this->productQuantity = $productQuantity;
    }

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function getProductQuantity(): int
    {
        return $this->productQuantity;
    }
}
