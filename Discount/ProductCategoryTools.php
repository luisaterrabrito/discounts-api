<?php

namespace AppBundle\Discount;

use AppBundle\Entity\Product;
use Doctrine\ORM\EntityManager;

class ProductCategoryTools implements DiscountInterface
{
    /**
     * @var EntityManager $entityManager
     */
    private $entityManager;

    /**
     * {@inheritDoc}
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * {@inheritDoc}
     */
    public function has(array $order)
    {
        foreach ($order['items'] as $item) {
            $product = $this->entityManager->getRepository('AppBundle:Product')->findOneById($item['product-id']);
            if ($product && $product->getCategory() == 1 && $item['quantity'] >= 2) {
                return true;
            }
        }
        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function calculate(array $order)
    {
        $productPrice = 0;
        foreach ($order['items'] as $item) {
            $product = $this->entityManager->getRepository('AppBundle:Product')->findOneById($item['product-id']);
            if ($product && $product->getCategory() == 1 && $item['quantity'] >= 2) {
                if ($product->getPrice() < $productPrice || $productPrice == 0) {
                    $productPrice = $product->getPrice();
                    $cheapest = $item['total'];
                }
            }
        }
        return $order['total'] - ($cheapest * 0.2);
    }

    /**
     * {@inheritDoc}
     */
    public function getMessage()
    {
        return '20% discount on cheapest product';
    }
}
