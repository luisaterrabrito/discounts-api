<?php

namespace AppBundle\Services;

use Doctrine\ORM\EntityManager;
use AppBundle\Entity\Customer;
use Symfony\Component\Config\Definition\Exception\Exception;

class CustomerService
{

    /**
     * @var array $discounts
     */
    private $entityManager;

    /**
     * Class constuctor
     *
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Verify if customer exists by id, if not, call method to add to database each
     * customer from custumers array
     *
     * @param  array $customers
     *
     * @throws HttpException
     *
     * @return array Response
     */
    public function createCustomer($customers)
    {
        $repository = $this->entityManager->getRepository('AppBundle:Customer');
        foreach ($customers as $customer) {
            if (!empty($repository->findOneById($customer['id']))) {
                throw new Exception("The customer with id: " . $customer['id'] . " already exists", 409);
            }
            $this->addCustomer($customer, $repository);
        }
        return [
            'Result' => 'Customers created successfully'
        ];
    }

    /**
     * Register a new custumer in database
     *
     * @param array $customer
     * @param Doctrine\ORM\EntityRepository  $repository
     *
     * @throws HttpException
     */
    public function addCustomer($customer, $repository)
    {
        try {
            $date = \DateTime::createFromFormat('Y-m-d', $customer['since']);
            $newCustomer = new Customer();
            $newCustomer->setName($customer['name']);
            $newCustomer->setSince($date);
            $newCustomer->setRevenue(floatval($customer['revenue']));
            $this->entityManager->persist($newCustomer);
            $this->entityManager->flush();
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), 400);
        }
    }
}
