<?php


namespace App\Response;

use App\Response\Contracts\TransformerInterface;

class OrderResponse implements TransformerInterface
{
    protected $orderData;
    protected $formatted = [];

    /**
     * OrderResponse constructor.
     * @param $orderData
     */
    public function __construct($orderData)
    {
        $this->orderData = $orderData;
        $this->process();
    }

    private function process() {
        foreach ($this->orderData['dataset'] as $order) {
            $this->formatted[] = $this->format($order);
        }
    }

    /**
     * @param $order
     * @return array
     */
    private function format($order) {
        return [
            'country' => $order['geoip']['country_iso_code'],
            'city' => $order['geoip']['city_name'] ?? '-',
            'sku' => $order['sku'],
            'category' => $order['category'][0] ?? '',
            'order_date' => $order['order_date'],
            'country' => $order['geoip']['country_iso_code'],
            'total_amount' => $order['taxful_total_price'],
            'type' => $order['type'],
            'customer' => $order['customer_full_name']
        ];
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'dataset' => $this->formatted,
            'page' => $this->orderData['page'],
            'offset' => $this->orderData['offset'],
            'total' => $this->orderData['total']
        ];
    }
}