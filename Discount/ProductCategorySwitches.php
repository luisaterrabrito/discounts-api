<?php

namespace AppBundle\Discount;

use AppBundle\Entity\Product;
use Doctrine\ORM\EntityManager;

class ProductCategorySwitches implements DiscountInterface
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
            if ($product && $product->getCategory() == 2 && $item['quantity'] >= 5) {

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
        $total = $order['total'];
        foreach ($order['items'] as $item) {
            $product = $this->entityManager->getRepository('AppBundle:Product')->findOneById($item['product-id']);
            if ($product && $product->getCategory() == 2 && $item['quantity'] >= 5) {
                if ($item['quantity'] % 5 !== 0) {
                    $switches = floor($item['quantity'] / 5);
                    $total = $total - ($switches * $product->getPrice());
                }
            }
        }
        return $total;
    }

    /**
     * {@inheritDoc}
     */
    public function getMessage()
    {
        return 'Sixth product of category Switches is free';
    }
}
