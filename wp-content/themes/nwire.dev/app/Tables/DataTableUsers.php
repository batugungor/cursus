<?php

namespace app\Tables;

class DataTableUsers extends DataTable
{
    public function __construct($items)
    {
        parent::__construct();

        $this
            ->setBulkActions([
                "edit_access_one_by_one" => "Toegang beheren (één voor één)",
                "give_all_basic_access" => "Toegang geven tot de cursus (zonder toegang tot lessen en quizzes)",
                "remove_access" => "Toegang verwijderen",
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
}
