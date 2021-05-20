<?php


namespace App\Helpers;


class PaginateHelper
{
    protected $page;
    protected $perPage;
    protected $offset;
    protected $params;

    public function __construct($params)
    {
        $this->params = $params;
        $this->process();
    }

    private function process() {
        $this->page = $this->params['page'] ?? 1;
        // Per Page
        $this->perPage = $this->params['per_page'] ?? self::PER_PAGE;
        // Offset
        $this->offset = $this->findOffset($this->params['page'] ?? 0, $this->params['per_page'] ?? 0);
    }

    private function findOffset($page, $perPage) {
        return $page * $perPage;
    }

    /**
     * @return mixed
     */
    public function getOffset()
    {
        return $this->offset;
    }

    /**
     * @param mixed $offset
     * @return PaginateHelper
     */
    public function setOffset($offset)
    {
        $this->offset = $offset;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPerPage()
    {
        return $this->perPage;
    }

    /**
     * @param mixed $perPage
     * @return PaginateHelper
     */
    public function setPerPage($perPage)
    {
        $this->perPage = $perPage;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @param mixed $page
     * @return PaginateHelper
     */
    public function setPage($page)
    {
        $this->page = $page;
        return $this;
    }


}