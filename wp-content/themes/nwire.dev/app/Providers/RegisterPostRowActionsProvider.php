<?php

namespace app\Providers;

use Rareloop\Lumberjack\Config;

class RegisterPostRowActionsProvider
{
    public function boot(Config $config): void
    {
        add_filter('post_row_actions', function ($actions, $post) {
            if ($post->post_type == "course") {
                $actions['course'] = '<a href="' . admin_url('/admin.php?page=custom-list-table&post=' . $post->ID) . '">Beheer toegang</a>';
                $actions['enrollment'] = '<a href="' . admin_url('/admin.php?page=enrollment&post=' . $post->ID) . '">Bekijk studenten</a>';
            }

            return $actions;
        }, 10, 2);

        add_filter('user_row_actions', function ($actions, $user) {
            $actions['course'] = '<a href="' . admin_url('/admin.php?page=overzicht&user=' . $user->ID) . '">Beheer toegang</a>';


            return $actions;
        }, 10, 2);
    }

}
