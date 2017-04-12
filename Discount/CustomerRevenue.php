<?php

namespace AppBundle\Discount;

use AppBundle\Entity\Customer;
use Doctrine\ORM\EntityManager;

class CustomerRevenue implements DiscountInterface
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
        $customer = $this->entityManager->getRepository('AppBundle:Customer')->findOneBy([
            'id' => $order['customer-id']
        ]);
        if ($customer && $customer->getRevenue() > 1000) {
            return true;
        }
        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function calculate(array $order)
    {
        return $order['total'] - ($order['total'] * 0.1);
    }

    /**
     * {@inheritDoc}
     */
    public function getMessage()
    {
        return '10% discount on whole order because customer had already bought 1000€';
    }
}
