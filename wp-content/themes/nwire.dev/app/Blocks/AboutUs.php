<?php

namespace app\Blocks;

use Carbon_Fields\Field\Field;

class AboutUs extends Block
{
    public function fields(): array
    {
        return [
            Field::make('map', 'crb_location')
                ->set_position(37.423156, -122.084917, 14),
            Field::make('sidebar', 'crb_custom_sidebar'),
            Field::make('image', 'crb_photo'),
        ];
    }
}
