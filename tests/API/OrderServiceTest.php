<?php


namespace App\Tests\API;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class OrderServiceTest extends KernelTestCase
{
    public function testOrderList() {
        self::bootKernel();
        $container = self::$kernel->getContainer();
        $container = self::$container; // Private Services
        $service = self::$container->get('App\Service\Contracts\OrderServiceInterface');

        $params['per_page'] = 15;
        $params['offset'] =  0;
        $params['page'] =  1;

        $response = $service->getOrders($params);

        $this->assertArrayHasKey('dataset', $response);
        $this->assertArrayHasKey('page', $response);
        $this->assertArrayHasKey('offset', $response);
        $this->assertArrayHasKey('total', $response);
    }

    public function testTopProducts() {
        self::bootKernel();
        $container = self::$kernel->getContainer();
        $container = self::$container; // Private Services
        $service = self::$container->get('App\Service\Contracts\OrderServiceInterface');

        $params['start_date'] = date("Y-m-d\TH:i:s", strtotime('today UTC'));
        $params['end_date'] = date("Y-m-d\TH:i:s", strtotime("-1 month"));

        $response = $service->getTopSelling($params);
        $this->assertIsArray($response);
        $bucket = $response[0];
        $this->assertArrayHasKey('key', $bucket);
        $this->assertArrayHasKey('doc_count', $bucket);
    }
}