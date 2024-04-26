<?php

namespace app\Blocks;

use Carbon_Fields\Field\Field;

class TestBlock extends Block
{
    public function fields(): array
    {
        return [
            Field::make('text', 'crb_photo'),
        ];
    }
}
