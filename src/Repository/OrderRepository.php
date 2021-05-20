<?php


namespace App\Repository;


use App\Helpers\PaginateHelper;
use App\Repository\Contracts\OrderRepositoryInterface;
use App\Traits\ESHandlerTrait;
use League\ISO3166\ISO3166;
use ONGR\ElasticsearchDSL\Aggregation\Bucketing\DateHistogramAggregation;
use ONGR\ElasticsearchDSL\Aggregation\Bucketing\TermsAggregation;
use ONGR\ElasticsearchDSL\Query\FullText\QueryStringQuery;
use ONGR\ElasticsearchDSL\Query\Specialized\ScriptQuery;
use ONGR\ElasticsearchDSL\Query\TermLevel\RangeQuery;
use ONGR\ElasticsearchDSL\Query\TermLevel\TermQuery;
use ONGR\ElasticsearchDSL\Search;
use ONGR\ElasticsearchDSL\Sort\FieldSort;
use ONGR\ElasticsearchDSL\Tests\Unit\Unit\SearchEndpoint\SortEndpointTest;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class OrderRepository implements OrderRepositoryInterface
{
    use ESHandlerTrait;

    protected $host;
    protected const INDEX_NAME = 'kibana_sample_data_ecommerce';


    /**
     * OrderRepository constructor.
     * @param $esHost
     * @param MockHttpClient $httpClient
     */
    public function __construct($esHost, MockHttpClient $httpClient) // Host injected from ENV
    {
        $this->setUp(self::INDEX_NAME, $esHost, $httpClient);
    }

    /**
     *
     */
    public function getFields() {
        $this->execute(self::INDEX_NAME.'/_mapping?pretty');
    }

    /**
     * @return array
     */
    public function getOrders($params = [], PaginateHelper $pagination) {
        // New search
        $search = new Search();

        // Apply range
        if(!empty($params['start_date']) && !empty($params['end_date'])) {
            $search->addQuery(
                $this->applyDateRange($params['start_date'], $params['end_date'])
            );
        }

        // Apply text search if there
        if(!empty($params['search'])) {
            $search->addQuery($this->applyFullText($params['search']));
        }

        $country = !empty($params['country']) ? self::parseCountry($params['country'], 'alpha2') : false; // Convert country name into code

        $this->filter($search, 'geoip.city_name', $params['city'] ?? false)
            ->filter($search, 'geoip.country_iso_code', $country);

        // Paginate
        $search
            ->setSize($pagination->getPerPage())
            ->setFrom($pagination->getOffset());

        // Run // Use Trait
        $result = $this->executeSearch($search);

        return [
            'dataset' => $result['data'],
            'page' => $pagination->getPage(),
            'offset' => $pagination->getOffset(),
            'total' => $result['info']['value']
        ];
    }

    /**
     * @param $value
     * @param string $to
     * @return mixed
     */
    private static function parseCountry($value, $to = 'name') {
        switch ($to) {
            case 'alpha2':
                $data = (new ISO3166())->name($value);
                break;
            case 'name':
            default:
                $data = (new ISO3166())->alpha2($value);
                break;
        }
        return $data[$to];
    }

    /**
     * @param Search $search
     * @param $field
     * @param $value
     * @return $this
     */
    public function filter(Search &$search, $field, $value) {
        if($value) {
            $termQuery = new TermQuery($field, $value);
            $search->addQuery($termQuery);
        }
        return $this;
    }

    /**
     * @param $startDate
     * @param $endDate
     * @return RangeQuery
     */
    private function applyDateRange($startDate, $endDate) {
        // Range query with date
        return new RangeQuery(
            'order_date',
            [
                'gte' => date('Y-m-d\TH:i:s.vO', strtotime($startDate)),
                'lte' => date('Y-m-d\TH:i:s.vO', strtotime($endDate)),
                'boost' => 2.0,
            ]
        );
    }

    /**
     * @param $text
     * @return QueryStringQuery
     */
    public function applyFullText($text) {
        // Text search
        return new QueryStringQuery($text);
    }


    /**
     * @param $params
     * @param PaginateHelper $pagination
     * @return mixed
     */
    public function getTopSelling($params, PaginateHelper $pagination) {
        // New search
        $search = new Search();

        // Term aggregation by sku
        $termsAggregation = new TermsAggregation('products', 'sku');

        // Apply
        $search->addAggregation($termsAggregation);

        // Apply date range
        if(!empty($params['start_date']) && !empty($params['end_date'])) {
            $search->addQuery(
                $this->applyDateRange($params['start_date'], $params['end_date'])
            );
        }

        // Paginate
        $search
            ->setSize($pagination->getPerPage())
            ->setFrom($pagination->getOffset());

        // Run // Use Trait
        $result = $this->executeSearch($search, [],null, true);

        // Only return buckets
        return $result['aggregations']['products']['buckets'];
    }

    /**
     * @param $params
     * @param PaginateHelper $pagination
     * @return array
     */
    public function boughtTogether($params, PaginateHelper $pagination) {
        // New search
        $search = new Search();

        $script = "doc['sku'].size() > 1"; // Number of sku\'s greater than one

        // Term aggregation by sku
        $scriptQuery = new ScriptQuery($script);

        $search->addQuery($scriptQuery);

        // Apply date range
        if(!empty($params['start_date']) && !empty($params['end_date'])) {
            $search->addQuery(
                $this->applyDateRange($params['start_date'], $params['end_date'])
            );
        }

        // Paginate
        $search
            ->setSize($pagination->getPerPage())
            ->setFrom($pagination->getOffset());

        $sort = ['order_date' => FieldSort::DESC];

        // Run // Use Trait
        $result = $this->executeSearch($search, $sort);

        return [
            'dataset' => $result['data'],
            'page' => $pagination->getPage(),
            'offset' => $pagination->getOffset(),
            'total' => $result['info']['value']
        ];
    }
}