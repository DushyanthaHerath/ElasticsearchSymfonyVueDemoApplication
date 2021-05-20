<?php


namespace App\Service;


use App\Helpers\PaginateHelper;
use App\Repository\Contracts\OrderRepositoryInterface;
use App\Response\OrderResponse;
use App\Service\Contracts\OrderServiceInterface;

class OrderService implements OrderServiceInterface
{
    protected $repository;

    protected CONST PER_PAGE = 15;

    /**
     * OrderService constructor.
     * @param OrderRepositoryInterface $repository
     */
    public function __construct(OrderRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param $params
     * @return mixed
     */
    public function getOrders($params) {
        // Pagination parameter
        $pagination = new PaginateHelper($params);
        // Query Orders
        $orderData = $this->repository->getOrders($params, $pagination);
        // Format
        return (new OrderResponse($orderData))->toArray();
    }

    /**
     * @param $params
     * @return mixed
     */
    public function getTopSelling($params) {
        // Pagination parameter
        $pagination = new PaginateHelper($params);
        // Dataset
        return $this->repository->getTopSelling($params, $pagination);
    }

    /**
     * @param $params
     * @return mixed
     */
    public function boughtTogether($params) {
        // Pagination parameter
        $pagination = new PaginateHelper($params);

        // Query Orders
        $orderData = $this->repository->boughtTogether($params, $pagination);

        // Format
        return (new OrderResponse($orderData))->toArray();
    }

    /**
     * @return mixed
     */
    public function getFields() {
        return $this->repository->getFields();
    }
}