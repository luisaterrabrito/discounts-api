<?php

namespace AppBundle\Services;

use AppBundle\Discount\DiscountInterface;
use Doctrine\ORM\EntityManager;

class DiscountService
{

    /**
     * @var array $discounts
     */
    private $discounts;

    /**
     * Class constuctor
     *
     * @param DiscountInterface $customerRevenue
     * @param DiscountInterface $productCategoryTools
     * @param DiscountInterface $productCategorySwitches
     */
    public function __construct(
        DiscountInterface $customerRevenue,
        DiscountInterface $productCategoryTools,
        DiscountInterface $productCategorySwitches
    ) {
        $this->discounts = [
            $customerRevenue,
            $productCategoryTools,
            $productCategorySwitches
        ];
    }

    /**
     * Verifies if each type of discount applies, if yes, calculates the it
     * and returns the biggest discount
     *
     * @param  array $order
     *
     * @return array Response
     */
    public function calculateDiscount($order)
    {
        $total = $order['total'];
        $message = 'No discount to apply';
        foreach ($this->discounts as $discount) {
            if ($discount->has($order)) {
                $totalDiscount = $discount->calculate($order);
                if ($totalDiscount <= $total) {
                    $message = $discount->getMessage();
                    $total = $totalDiscount;
                }
            }
        }
        return [
            'Total' => $order['total'],
            'Total after discount' => "$total",
            'Reason for discount' => $message
        ];
    }
}
