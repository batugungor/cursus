<?php

namespace app\Tables;

use app\Factory\DataTableFactory;

abstract class DataTable extends DataTableFactory
{
    public function setColumns($columns): DataTable
    {
        $base = [
            'cb' => '<input type="checkbox" />'
        ];

        $this->factory_set_column($base + $columns);

        return $this;
    }

    public function setItems($items): DataTable
    {
//        dd($items);
        $this->factory_set_items($items);

        return $this;
    }

    public function setBulkActions($bulk_actions): DataTable
    {
        $this->factory_set_bulk_actions($bulk_actions);

        return $this;
    }

    public function setColumnDefault($item, $column_name): DataTable
    {
        $this->factory_set_column_default($item, $column_name);

        return $this;
    }

    public function setCheckbox($key): DataTable
    {
        $this->factory_set_column_checkbox($key);

        return $this;
    }

    public function generateTable(): DataTable
    {
        $this->prepare_items();

        return $this;
    }

    function processBulkActions(): void
    {
        // TODO: Implement ProcessBulkActions() method.
    }
}
