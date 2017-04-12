<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use AppBundle\Services\ProductService;
use \Exception;

class ProductController extends Controller
{
    /**
     * @var ProductService $productService
     */
    private $ProductService;

    /**
     * Class constructor
     *
     * @param ProductService $productService
     */
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

  /**
   * Create new Products
   *
   *   Response: {
   *       'Result' => 'Product created successfully',
   *   }
   *
   * @ApiDoc(
   *  headers={
   *      {
   *          "name"="Accept",
   *          "description"="application/json",
   *          "required"=true
   *      },
   *      {
   *          "name"="Content-Type",
   *          "description"="application/json",
   *          "required"=true
   *      }
   *  },
   *  requirements={
   *      {
   *          "name"="id",
   *          "dataType"="string",
   *          "required"=true,
   *          "description"="Product id"
   *      },
   *      {
   *          "name"="description",
   *          "dataType"="string",
   *          "required"=true,
   *          "description"="Product description"
   *      },
   *      {
   *          "name"="category",
   *          "dataType"="string",
   *          "required"=true,
   *          "description"="Product category"
   *      },
   *      {
   *          "name"="price",
   *          "dataType"="string",
   *          "required"=true,
   *          "description"="Product price"
   *      }
   *  },
   *  statusCodes={
   *    200 = "Returned when the products were created successfully",
   *    409 = "The product with id: x already exists"
   *  },
   *  description="Create new Products"
   * )
   * @param Request $request
   *
   * @return View
   */
    public function createProductAction(Request $request)
    {
        $response = new JsonResponse();
        try {
            $products = json_decode($request->getContent(), true);
            $this->validateProducts($products);
            $result = $this->productService->createProduct($products);
            $response->setData([$result]);
            $response->setStatusCode(200);
        } catch (Exception $e) {
            $response->setData(['error' => $e->getMessage()]);
            $response->setStatusCode(400);
        }

        return $response;
    }

    /**
     * Validate request params from $products array
     */
    public function validateProducts($products)
    {
        if (!is_array($products)) {
            throw new Exception("Products is not an array", 400);
        }
        foreach ($products as $product) {
            if (!array_key_exists('id', $product) || !is_string($product['id'])) {
                throw new Exception("Product missing 'id' or is not a string", 400);
            }
            if (!array_key_exists('description', $product) || !is_string($product['description'])) {
                throw new Exception("Product missing 'description' or is not a string", 400);
            }
            if (!array_key_exists('category', $product) || !is_string($product['category'])) {
                throw new Exception("Product missing 'category' or is not a string", 400);
            }
            if (!array_key_exists('price', $product) || !is_string($product['price'])) {
                throw new Exception("Product missing 'price' or is not a string", 400);
            }
        }
    }
}
