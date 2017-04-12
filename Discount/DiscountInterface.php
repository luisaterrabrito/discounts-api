<?php

namespace AppBundle\Discount;

interface DiscountInterface
{

     /**
     * Verify if the discount applies to given order
     *
     * @param array $order
     *
     * @return bool
     */
    public function has(array $order);

     /**
     * Calculate discount for given order
     *
     * @param array $order
     *
     * @return float total discount
     */
    public function calculate(array $order);

    /**
     * Return string with message explaining the discount
     *
     * @return string message
     */
    public function getMessage();
}
