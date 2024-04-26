<?php

namespace app\PostTypes;

use Rareloop\Lumberjack\Post;

class Block extends Post
{
    /**
     * Return the key used to register the post type with WordPress
     * First parameter of the `register_post_type` function:
     * https://codex.wordpress.org/Function_Reference/register_post_type
     *
     * @return string
     */
    public static function getPostType(): string
    {
        return 'blocks';
    }

    /**
     * Return the config to use to register the post type with WordPress
     * Second parameter of the `register_post_type` function:
     * https://codex.wordpress.org/Function_Reference/register_post_type
     *
     * @return array|null
     */
    protected static function getPostTypeConfig(): ?array
    {
        return [
            'labels' => [
                'name' => __('Blocks'),
                'singular_name' => __('Block'),
                'add_new_item' => __('Add New Block'),
            ],
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'menu_icon' => 'dashicons-align-wide',
            'supports' => ['title']
        ];
    }

}
