<?php

namespace app\Tables;

class DataTableCourseOverview extends DataTable
{
    public function __construct($items)
    {
        parent::__construct();

        $this->setColumns([
            "post_title" => "Titel",
            "type" => "Type",
        ])
            ->setItems($items)
            ->setCheckbox("ID")
            ->setBulkActions([
                "save_access" => "Opslaan"
            ])
            ->generateTable();
    }

    public function column_cb($item)
    {
        $checked = $item["has_access"];

        return sprintf(
            '<input type="checkbox" name="item[]" %s value="%s"/>',
            $checked === true ? 'checked' : '', $item[$this->key]
        );
    }
}
