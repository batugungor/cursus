<?php

namespace app\Providers;

use Carbon_Fields\Carbon_Fields;
use Carbon_Fields\Container\Container;
use Rareloop\Lumberjack\Config;
use Rareloop\Lumberjack\Providers\ServiceProvider;

class RegisterCustomFieldsProvider extends ServiceProvider
{
    public function boot(Config $config): void
    {
        Carbon_Fields::boot();

        $post_types = $config->get('posttypes.register');

        if (!is_array($post_types)) {
            return;
        }

        foreach ($post_types as $post_type) {
            if (class_exists($post_type)) {
                $post_type_object = new $post_type();

                if (method_exists($post_type_object, 'getPostTypeCustomFields')) {
                    Container::make('post_meta', strtoupper($post_type_object->getPostType()))
                        ->where('post_type', '=', $post_type_object->getPostType())
                        ->add_fields($post_type_object->getPostTypeCustomFields());
                }
            }
        }
    }
}
