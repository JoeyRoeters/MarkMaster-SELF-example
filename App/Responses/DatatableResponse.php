<?php

namespace App\Responses;

use App\Helpers\Datatable\Interfaces\DatatableActionDTOInterface;
use App\Helpers\Datatable\Interfaces\DatatableHeaderDTOInterface;
use App\Helpers\Datatable\Interfaces\DatatableRowDTOInterface;
use SELF\src\Http\Responses\MustacheResponse;

class DatatableResponse extends MustacheResponse
{
    private array $columns = [];
    private array $rows = [];
    private array $actions = [];

    public function __construct(
        private ?string $title = null,
        private array $data = [],
    )
    {
        parent::__construct('Datatable/datatable', $this->data, $this->title);
    }

    /**
     * @param string $url
     * @return $this
     */
    public function setCreate(string $url): self
    {
        $this->mustache->appendData([
            'create' => $url
        ]);

        return $this;
    }

    /**
     * @param DatatableRowDTOInterface $row
     * @return $this
     */
    public function addRow(DatatableRowDTOInterface $row): self
    {
        $this->rows[] = $row;

        return $this;
    }

    /**
     * @param array $rows
     * @return $this
     */
    public function setRows(array $rows): self
    {
        $this->rows = $rows;

        return $this;
    }

    /**
     * @param DatatableHeaderDTOInterface $header
     * @return $this
     */
    public function addHeader(DatatableHeaderDTOInterface $header): self
    {
        $this->columns[] = $header;

        return $this;
    }

    /**
     * @param DatatableActionDTOInterface $action
     * @return $this
     */
    public function addAction(DatatableActionDTOInterface $action): self
    {
        $this->actions[] = $action;

        return $this;
    }

    /**
     * @param array $headers
     * @return $this
     */
    public function setHeaders(array $headers): self
    {
        $this->columns = $headers;

        return $this;
    }

    /**
     * @return void
     */
    public function output(): void
    {
        $this->mustache->appendData([
            'columns' => array_map(fn ($column) => $column->getTitle(), $this->columns),
            'rows' => array_map(fn ($row) => $row->getData(), $this->rows),
            'actions' => array_map(fn ($action) => $action->export(), $this->actions)
        ]);

        parent::output();
    }
}