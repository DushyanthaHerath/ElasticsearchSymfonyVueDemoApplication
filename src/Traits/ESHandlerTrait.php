<?php


namespace App\Traits;


use App\Service\Contracts\ESSearchHandlerInterface;
use Elasticsearch\ClientBuilder;
use ONGR\ElasticsearchDSL\Search;
use ONGR\ElasticsearchDSL\SearchEndpoint\SortEndpoint;
use ONGR\ElasticsearchDSL\Sort\FieldSort;

trait ESHandlerTrait
{
    private $client;

    private $http;

    private $_host;

    /**
     * @param $searchIndex
     */
    public function setUp($searchIndex, $host, $http)
    {
        $this->searchIndex = $searchIndex;

        $this->client = ClientBuilder::create()->setHosts([$host])->build();

        $this->client->indices()->refresh();

        $this->http = $http;

        $this->_host = $host;
    }

    /**
     * Execute search to the elasticsearch and handle results.
     *
     * @param Search $search Search object.
     * @param null $type Types to search. Can be several types split by comma.
     * @param bool $returnRaw Return raw response from the client.
     * @return array
     */
    public function executeSearch(Search $search, $sort = [], $type = null, $returnRaw = false)
    {
        $response = $this->client->search(
            array_filter([
                'index' => self::INDEX_NAME,
                'type' => $type,
                'body' => array_merge($search->toArray(), !empty($sort) ? ['sort' => $sort] : []),
            ])
        );

        if ($returnRaw) {
            return $response;
        }

        $documents = [];

        try {
            foreach ($response['hits']['hits'] as $document) {
                $documents[] = $document['_source'];
            }
        } catch (\Exception $e) {
            return $documents;
        }

        return [
            'data' => $documents,
            'info' => $response['hits']['total']
        ];
    }

    /**
     * @param $path
     * @param array $with
     * @return mixed
     */
    public function execute($path, $with=[]) {
        if(empty($with))
        {
            $response = $this->http->request(
                'GET',
                $this->craftPath($path)
            );
        }
        else
        {
            $response = $this->http->request(
                'POST',
                $this->craftPath($path),
                [ 'body' => $with ]
            );
        }

        $content = $response->getContent();
        return $content;
    }

    /**
     * @param $path
     * @return string
     */
    public function craftPath($path) {
        return $this->_host.'/'.$this->searchIndex.$path;
    }
}