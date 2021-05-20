<?php


namespace App\Controller\API;


use App\Traits\ResponseTrait;
use Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

abstract class BaseController extends AbstractController
{

    use ResponseTrait;

    /**
     * Send API Response
     *
     * @param null $data
     * @param string $message
     * @param int $statusCode
     * @param array $headers
     * @return JsonResponse
     */
    public function sendResponse($data = null, $message = 'OK', $statusCode = 200, $headers = ['content-type'=>'application/json'])
    {
        $this->setStatusCode($statusCode);
        $this->setHeaders($headers);

        $response = [
            'status_code' => $this->getStatusCode(),
            'status' => $this->getStatusText(),
            'message' => $message,
            'result' => $data
        ];

        // Paginate logic
        if($data instanceof SlidingPagination) {
            $response['result'] = $this->paginate($data);
        }

        return new Response($this->serializer->serialize($response, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]), $statusCode, $this->getHeaders());
    }

    /**
     * @param SlidingPagination $data
     * @return array
     */
    public function paginate(SlidingPagination $data) {
        return [
            "total" => $data->getTotalItemCount(),
            "per_page" => $data->getItemNumberPerPage(),
            "current_page" => $data->getCurrentPageNumber(),
            "last_page" => ceil($data->getTotalItemCount()/$data->getItemNumberPerPage()),
            "from" =>  $data->getCurrentPageNumber() == 1 ? 1 : ($data->getCurrentPageNumber()-1)*$data->getItemNumberPerPage(),
            "to" => $data->getCurrentPageNumber()*$data->getItemNumberPerPage(),
            "data" => $data->getItems()
        ];
    }

    /**
     * Send API Response
     *
     * @param null $data
     * @param string $message
     * @param int $statusCode
     * @param array $headers
     * @param null $statusText
     * @param null $debug
     * @return JsonResponse
     */
    public function sendErrorResponse($data = null, $message = 'ERROR', $statusCode = 400, $headers = [], $statusText = null, $debug = null)
    {

        $this->setStatusCode($statusCode, $statusText);
        $this->addHttpHeaders($headers);

        $errorObject = [
            'status_code' => $this->getStatusCode(),
            'status' => $this->getStatusText(),
            'message' => $message,
            'result' => $data
        ];

        if ($debug) {
            $errorObject['debug'] = $debug;
        }

        return new JsonResponse($errorObject, $statusCode, $this->getHeaders());
    }
}
