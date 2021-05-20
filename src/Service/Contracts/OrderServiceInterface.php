<?php

namespace App\Service\Contracts;

interface OrderServiceInterface
{
    /**
     * @param $params
     * @return mixed
     */
    public function getOrders($params);

    /**
     * @param $params
     * @return mixed
     */
    public function getTopSelling($params);

    /**
     * @return mixed
     */
    public function getFields();
}