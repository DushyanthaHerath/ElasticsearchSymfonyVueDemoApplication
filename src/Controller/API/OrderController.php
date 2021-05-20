<?php


namespace App\Controller\API;

use App\Exception\ApiException;
use App\Service\Contracts\OrderServiceInterface;
use Elasticsearch\ClientBuilder;
use ONGR\ElasticsearchDSL\Aggregation\Bucketing\DateRangeAggregation;
use ONGR\ElasticsearchDSL\Aggregation\Bucketing\RangeAggregation;
use ONGR\ElasticsearchDSL\Aggregation\Matrix\MaxAggregation;
use ONGR\ElasticsearchDSL\Aggregation\Metric\MinAggregation;
use ONGR\ElasticsearchDSL\Query\FullText\MatchQuery;
use ONGR\ElasticsearchDSL\Query\MatchAllQuery;
use ONGR\ElasticsearchDSL\Query\TermLevel\RangeQuery;
use ONGR\ElasticsearchDSL\Search;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/order")
 */
class OrderController extends BaseController
{
    private $service;

    /**
     * OrderController constructor.
     * @param OrderServiceInterface $service
     */
    public function __construct(OrderServiceInterface $service)
    {
        $this->service = $service;
    }

    /**
     * @Route("/field")
     */
    public function fields() {
        return $this->sendResponse($this->service->getFields());
    }


    /**
     * @Route("/filter")
     */
    public function getOrders(Request $request) {
        try {
            return $this->sendResponse(
                $this->service
                    ->getOrders($request->query->all())
            );
        } catch (ApiException $e) { // TODO Use exception Handler
            return $this->sendResponse(null, $this->getStatusText(), 400);
        }
    }

    /**
     * @Route("/top_selling")
     */
    public function topSelling(Request $request) {
        try {
            return $this->sendResponse(
                $this->service
                    ->getTopSelling($request->query->all())
            );
        } catch (ApiException $e) {
            return $this->sendResponse(null, $this->getStatusText(), 400);
        }
    }

    /**
     * @Route("/bought_together")
     */
    public function boughtTogether(Request $request) {
        try {
            return $this->sendResponse(
                $this->service
                    ->boughtTogether($request->query->all())
            );
        } catch (ApiException $e) {
            return $this->sendResponse(null, $this->getStatusText(), 400);
        }
    }
}