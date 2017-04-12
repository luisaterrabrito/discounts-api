<?php

namespace AppBundle\Services;

use Doctrine\ORM\EntityManager;
use AppBundle\Entity\Product;
use Symfony\Component\Config\Definition\Exception\Exception;

class ProductService
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
     * Verify if product exists by id, if not, call method to add to database each
     * product from products array
     *
     * @param  array $products
     *
     * @throws HttpException
     *
     * @return array Response
     */
    public function createProduct($products)
    {
        $repository = $this->entityManager->getRepository('AppBundle:Product');
        foreach ($products as $product) {
            if (!empty($repository->findOneById($product['id']))) {
                throw new Exception("The product with id: " . $product['id'] . " already exists", 409);
            }
            $this->addProduct($product, $repository);
        }
        return [
            'Result' => 'Products created successfully'
        ];
    }

    /**
     * Register a new product in database
     *
     * @param array $product
     * @param Doctrine\ORM\EntityRepository  $repository
     *
     * @throws HttpException
     */
    public function addProduct($product, $repository)
    {
        try {
            $newProduct = new Product();
            $newProduct->setId($product['id']);
            $newProduct->setDescription($product['description']);
            $newProduct->setCategory(intval($product['category']));
            $newProduct->setPrice(floatval($product['price']));
            $this->entityManager->persist($newProduct);
            $this->entityManager->flush();
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), 400);
        }
    }
}
