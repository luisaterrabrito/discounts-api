<?php

namespace Tests\Service;

use PHPUnit\Framework\TestCase;
use AppBundle\Discount\CustomerRevenue;
use AppBundle\Discount\ProductCategoryTools;
use AppBundle\Discount\ProductCategorySwitches;
use AppBundle\Services\DiscountService;
use Doctrine\ORM\EntityManager;

class DiscountServiceTest extends TestCase
{
    /**
     * @var CustomerRevenue $customerRevenue
     */
    private $customerRevenue;

    /**
     * @var ProductCategoryTools $productCategoryTools
     */
    private $productCategoryTools;

    /**
     * @var ProductCategorySwitches $productCategorySwitches
     */
    private $productCategorySwitches;

    /**
     * {@inheritDoc}
     */
    public function setUp(){
        $this->customerRevenue = $this
            ->getMockBuilder(CustomerRevenue::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->productCategoryTools = $this
            ->getMockBuilder(ProductCategoryTools::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->productCategorySwitches = $this
            ->getMockBuilder(ProductCategorySwitches::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * Test method calculateDiscount from DiscountService
     */
    public function testCalculateDiscount(){
        $this->customerRevenue->expects($this->once())
            ->method('has')
            ->will($this->returnValue(true));
        $this->customerRevenue->expects($this->once())
            ->method('calculate')
            ->will($this->returnValue(12.2));
        $this->customerRevenue->expects($this->once())
            ->method('getMessage')
            ->will($this->returnValue('test discount customer'));

        $this->productCategoryTools->expects($this->once())
            ->method('has')
            ->will($this->returnValue(true));
        $this->productCategoryTools->expects($this->once())
            ->method('calculate')
            ->will($this->returnValue(42.2));
        $this->productCategoryTools->expects($this->never())
            ->method('getMessage')
            ->will($this->returnValue('test discount tools'));

        $this->productCategorySwitches->expects($this->once())
            ->method('has')
            ->will($this->returnValue(true));
        $this->productCategorySwitches->expects($this->once())
            ->method('calculate')
            ->will($this->returnValue(2.2));
        $this->productCategorySwitches->expects($this->once())
            ->method('getMessage')
            ->will($this->returnValue('test discount switches'));

        $discountService = new DiscountService(
            $this->customerRevenue,
            $this->productCategoryTools,
            $this->productCategorySwitches
        );

        $data = [
          "id" => "1",
          "customer-id" => "1",
          "items" => [
              [
                  "product-id" => "B102",
                  "quantity" => "10",
                  "unit-price" => "4.99",
                  "total" => "49.90"
              ]
          ],
          "total" => "49.90"
        ];

        $response = $discountService->calculateDiscount($data);
        $this->assertEquals('49.90', $response['Total']);
        $this->assertEquals('2.2', $response['Total after discount']);
        $this->assertEquals('test discount switches', $response['Reason for discount']);
    }
}
