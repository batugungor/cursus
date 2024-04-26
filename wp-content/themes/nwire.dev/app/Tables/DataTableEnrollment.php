<?php

namespace app\Tables;

class DataTableEnrollment extends DataTable
{
    public function __construct($items)
    {
        parent::__construct();

        $this
            ->setBulkActions([
            ])->setColumns([
                "name" => "Naam",
                "email" => "Email",
            ])
            ->setItems($this->mappers($items))
            ->setCheckbox("ID")

            ->generateTable();
    }

    public function mappers($items): array
    {
        $mapping = [];
        foreach ($items as $item) {
            $mapping[] = [
                "ID" => $item->ID,
                "name" => $item->first_name . ' ' . $item->last_name,
                "email" => $item->user_email,
            ];
        }

        return $mapping;
    }

    public function setColumns($columns): DataTable
    {
        $this->factory_set_column($columns);

        return $this;
    }
}
