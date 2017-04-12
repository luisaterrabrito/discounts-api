<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use AppBundle\Services\CustomerService;
use \Exception;

class CustomerController extends Controller
{
    /**
     * @var CustomerService $customerService
     */
    private $customerService;

    /**
     * Class constructor
     *
     * @param CustomerService $customerService
     */
    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
    }

  /**
   * Create new customers
   *
   *   Response: {
   *       'Result' => 'Customer created successfully'
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
   *          "name"="name",
   *          "dataType"="string",
   *          "required"=true,
   *          "description"="Customer name"
   *      },
   *      {
   *          "name"="since",
   *          "dataType"="string",
   *          "required"=true,
   *          "format"="Y-m-d",
   *          "description"="Date since customer exists"
   *      },
   *      {
   *          "name"="revenue",
   *          "dataType"="string",
   *          "required"=true,
   *          "description"="Customer revenue"
   *      }
   *  },
   *  statusCodes={
   *    200 = "Returned when the customers were created successfully",
   *    409 = "The customer with id: x already exists"
   *  },
   *  description="Create new customers"
   * )
   * @param Request $request
   *
   * @return View
   */
    public function createCustomerAction(Request $request)
    {
        $response = new JsonResponse();
        try {
            $customers = json_decode($request->getContent(), true);

            $this->validateCustomers($customers);

            $result = $this->customerService->createCustomer($customers);

            $response->setData([$result]);
            $response->setStatusCode(200);
        } catch (Exception $e) {
            $response->setData(['error' => $e->getMessage()]);
            $response->setStatusCode(400);
        }

        return $response;
    }

    /**
     * Validate request params
     */
    public function validateCustomers($customers)
    {
        if (!is_array($customers)) {
            throw new Exception("Customers is not an array", 400);
        }
        foreach ($customers as $customer) {
            if (!array_key_exists('id', $customer) || !is_string($customer['id'])) {
                throw new Exception("Customer missing 'id' or is not a string", 400);
            }
            if (!array_key_exists('name', $customer) || !is_string($customer['name'])) {
                throw new Exception("Customer missing 'name' or is not a string", 400);
            }
            if (!array_key_exists('since', $customer) || !is_string($customer['since'])) {
                throw new Exception("Customer missing 'since' or is not a string", 400);
            }
            if (!array_key_exists('revenue', $customer) || !is_string($customer['revenue'])) {
                throw new Exception("Customer missing 'revenue' or is not a string", 400);
            }
        }
    }
}
