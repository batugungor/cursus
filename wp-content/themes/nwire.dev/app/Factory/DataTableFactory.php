<?php

namespace app\Factory;

use WP_List_Table;

abstract class DataTableFactory extends WP_List_Table
{
    private array $columns;
    private array $bulk_actions;
    public string $key;

    function __construct()
    {
        parent::__construct(array(
            'singular' => 'item',
            'plural' => 'items',
            'ajax' => false
        ));
    }

    function column_cb($item)
    {
        return sprintf(
            '<input type="checkbox" name="item[]" value="%s"/>',
            $item[$this->key]
        );
    }

    function get_columns(): array
    {
        return $this->columns;
    }

    function get_bulk_actions()
    {
        return $this->bulk_actions;
    }

    function process_bulk_action()
    {
        $this->ProcessBulkActions();
    }

    function prepare_items()
    {
        $columns = $this->get_columns();
        $hidden = [];
        $sortable = [];
        $this->_column_headers = [$columns, $hidden, $sortable];

        $this->process_bulk_action();
    }

    public function factory_set_column_checkbox($key): void
    {
        $this->key = $key;
    }

    public function factory_set_column($columns): void
    {
        $this->columns = $columns;
    }

    public function factory_set_items($items): void
    {
        $this->items = $items;
    }

    public function factory_set_bulk_actions($bulk_actions): void
    {
        $this->bulk_actions = $bulk_actions;
    }

    public function column_default($item, $column_name)
    {
        return $item[$column_name];
    }

    abstract function processBulkActions(): void;
}
