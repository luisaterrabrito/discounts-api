<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use AppBundle\Services\DiscountService;
use \Exception;

class OrderController extends Controller
{
    /**
     * @var DiscountService $discountService
     */
    private $discountService;

    /**
     * Class constuctor
     *
     * @param DiscountService $discountService
     */
    public function __construct(DiscountService $discountService)
    {
        $this->discountService = $discountService;
    }

  /**
   * Get discount for order
   *
   *   Response: {
   *       'Total' => '',
   *       'Total after discount' => '',
   *       'Reason for discount' => ''
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
   *  parameters={
   *      {
   *          "name"="id",
   *          "dataType"="string",
   *          "required"=true,
   *          "description"="Customer id"
   *      },
   *      {
   *          "name"="customer-id",
   *          "dataType"="string",
   *          "required"=true,
   *          "description"="Customer name"
   *      },
   *      {
   *          "name"="items",
   *          "dataType"="array",
   *          "required"=true,
   *          "description"="Array with order items and their information"
   *      },
   *      {
   *          "name"="product-id",
   *          "dataType"="string",
   *          "required"=true,
   *          "description"="Item product id"
   *      },
   *      {
   *          "name"="quantity",
   *          "dataType"="string",
   *          "required"=true,
   *          "description"="Item quantity"
   *      },
   *      {
   *          "name"="unit-price",
   *          "dataType"="string",
   *          "required"=true,
   *          "description"="Item unit price"
   *      },
   *      {
   *          "name"="total (item)",
   *          "dataType"="string",
   *          "required"=true,
   *          "description"="Item total"
   *      },
   *      {
   *          "name"="total",
   *          "dataType"="string",
   *          "required"=true,
   *          "description"="Order total"
   *      }
   *  },
   *  statusCodes={
   *    200 = "Returned when the discount verification was done correctly"
   *  },
   *  description="Get discount for order"
   * )
   * @param Request $request
   *
   * @return View
   */
    public function discountAction(Request $request)
    {
        $response = new JsonResponse();
        try {
            $order = json_decode($request->getContent(), true);

            $this->validateOrder($order);

            $result = $this->discountService->calculateDiscount($order);

            $response->setData([$result]);
            $response->setStatusCode(200);
        } catch (Exception $e) {
            $response->setData(['error' => $e->getMessage()]);
            $response->setStatusCode(400);
        }

        return $response;
    }

    /**
     * Validate request params from $order array
     */
    public function validateOrder($order)
    {
        if (!is_array($order)) {
            throw new Exception("Order is not an array", 400);
        }
        if (!array_key_exists('id', $order) || !is_string($order['id'])) {
            throw new Exception("Order missing 'id' or is not string", 400);
        }
        if (!array_key_exists('customer-id', $order) || !is_string($order['customer-id'])) {
            throw new Exception("Order missing 'customer-id' or is not string", 400);
        }
        if (!array_key_exists('items', $order) || !is_array($order['items'])) {
            throw new Exception("Order missing 'items' or is not an array", 400);
        }
        if (!array_key_exists('total', $order) || !is_string($order['total'])) {
            throw new Exception("Order missing 'total' or is not string", 400);
        }
        foreach ($order['items'] as $item) {
            $this->validateItem($item);
        }
    }

    /**
     * Validate request params from $item array
     */
    public function validateItem($item)
    {
        if (!is_array($item)) {
            throw new Exception("Item is not an array", 400);
        }
        if (!array_key_exists('product-id', $item) || !is_string($item['product-id'])) {
            throw new Exception("Item is missing 'product-id' or is not string", 400);
        }
        if (!array_key_exists('quantity', $item) || !is_string($item['quantity'])) {
            throw new Exception("Item is missing 'quantity' or is not string", 400);
        }
        if (!array_key_exists('unit-price', $item) || !is_string($item['unit-price'])) {
            throw new Exception("Item is missing 'unit-price' or is not string", 400);
        }
        if (!array_key_exists('total', $item) || !is_string($item['total'])) {
            throw new Exception("Item is missing 'total' or is not string", 400);
        }
    }
}
