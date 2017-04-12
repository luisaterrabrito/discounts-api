<?php

namespace Tests\Service;

use PHPUnit\Framework\TestCase;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\Customer;
use AppBundle\Discount\CustomerRevenue;

class CustomerRevenueTest extends TestCase
{
    /**
     * @var array $data
     */
    private $data;

    /**
     * {@inheritDoc}
     */
    public function setUp(){
        $this->data = [
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
    }

    /**
     * Test method has from CustomerRevenue
     */
    public function testHas(){
        $customer = $this->createMock(Customer::class);
        $customer->expects($this->once())
            ->method('getRevenue')
            ->will($this->returnValue(10010));

        $customerRepository = $this
            ->getMockBuilder(EntityRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $customerRepository->expects($this->once())
            ->method('findOneBy')
            ->will($this->returnValue($customer));

        $entityManager = $this
            ->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->getMock();
        $entityManager->expects($this->once())
            ->method('getRepository')
            ->will($this->returnValue($customerRepository));

        $customerRevenue = new CustomerRevenue($entityManager);
        $this->assertTrue($customerRevenue->has($this->data));
    }
}
