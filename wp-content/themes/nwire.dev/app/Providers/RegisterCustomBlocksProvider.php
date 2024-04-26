<?php

namespace app\Providers;

use Carbon_Fields\Block;
use Carbon_Fields\Container\Container;
use Carbon_Fields\Field\Field;
use Rareloop\Lumberjack\Config;
use Timber\Post;
use Timber\Timber;
use WP_Post;

class RegisterCustomBlocksProvider
{
    public function boot(Config $config): void
    {
        $blocks_config = $config->get('blocks.blocks');

        foreach ($blocks_config as $block_config) {
            $block_structure = (new $block_config());

            $block = $this->checkIfBlockExistsInDatabase($block_structure->title);
            if (!is_null($block)) {
                $context = Timber::context();
                $context['block'] = Timber::get_post($block);

                $this->createViewSettings($block_structure, $block);
                $this->createPostMetaForBlock($block_structure, $block);
                $this->createGutenbergBlock($block, $block_structure, $context);
            }
        }
        $this->setAdminNotice();

    }

    private function checkIfBlockExistsInDatabase(string $name): bool|int|Post|WP_Post|null
    {
        $query = get_posts([
            'post_type' => 'blocks',
            'post_status' => 'publish',
            's' => $name,
            'posts_per_page' => 1,
        ]);

        return empty($query) ? $this->createNewBlockInDatabase($name) : $query[0];
    }

    private function checkIfBlockIsUsedInAnyPages($name): array
    {
        $args = [
            'post_type' => 'page',
            'posts_per_page' => -1, // Retrieve all pages
            's' => 'wp:carbon-fields/' . $name,
            'search_columns' => ['post_content'],
        ];

        return get_posts($args);
    }

    private function createNewBlockInDatabase($name): bool|Post|null
    {
        $post_data = [
            'post_title' => $name,
            'post_name' => sanitize_title($name),
            'post_status' => 'publish',
            'post_type' => 'blocks',
        ];

        $post_id = wp_insert_post($post_data);

        if (!is_wp_error($post_id)) {
            return Timber::get_post($post_id);
        }

        return null;
    }

    private function createViewSettings($block_structure, $block): void
    {
        $side = Container::make('post_meta', __('Weergave instellingen'))
            ->where('post_type', '=', 'blocks')
            ->where('post_id', '=', $block->ID)
            ->add_fields([
                Field::make('checkbox', 'block_meta_show_content', __("Op alle pagina's deactiveren"))
                    ->set_help_text("
                                Wanneer het vakje geselecteerd is, wordt de block niet meer getoond op de website.
                                Hierdoor gaat geen data verloren. <br/><br/>
                                Dit geldt voor alle pagina's waaraan de block verbonden is"),
            ])
            ->set_context('side')
            ->set_priority('high');

        $query = $this->checkIfBlockIsUsedInAnyPages($block_structure->name);

        $pages = Field::make('set', 'block_meta_show_content_pages', __('Deactiveren per pagina'))
            ->set_help_text("Deactiveren van de block per pagina.");

        foreach ($query as $page) {
            $pages->add_options([
                $page->ID => $page->post_title
            ]);
        }

        $side->add_fields([
            $pages
        ]);
    }

    private function createPostMetaForBlock($block_structure, $block): void
    {
        Container::make('post_meta', __('Gegevens'))
            ->where('post_type', '=', 'blocks')
            ->where('post_id', '=', $block->ID)
            ->add_fields($block_structure->fields());
    }

    private function createGutenbergBlock($block, $block_structure, $context): void
    {
        Block::make($block_structure->title)
            ->set_category('nwire-dev', __('Nwire.dev'), 'smiley')
            ->add_fields([
                Field::make('html', 'crb_html', __('Section Description'))
                    ->set_html("
                    <div style='color:black; border: 1px solid black; border-radius: 10px; padding: 15px 10px;'>
                        <h3 style='font-weight: normal; margin-bottom:0;' >{$block_structure->title} <a style='font-size: 12px' href='" . admin_url('post.php?post=' . $block->ID) . '&action=edit' . "'>(edit)</a></h3>
                        <p style='margin-top: 0'>This is a custom block (made by <a href='https://nwire.dev'>nwire.dev</a>)</p>
                    </div>
                    ")
            ])
            ->set_render_callback(function ($fields, $attributes, $inner_blocks, $post_id) use ($block, $block_structure, $context) {
                if (!carbon_get_post_meta($block->ID, 'block_meta_show_content')) {
                    if (!in_array($post_id, carbon_get_post_meta($block->ID, 'block_meta_show_content_pages')))
                        Timber::render('blocks/' . sanitize_title($block_structure->title) . '.twig', $context);
                }
            });
    }

    private function setAdminNotice(): void
    {
        add_action('current_screen', function ($current_screen) {
            if (isset($_GET["post"])) {
                if ('blocks' == $current_screen->post_type && 'post' == $current_screen->base) {
                    $post = get_post((int)$_GET["post"]);

                    $args = [
                        'post_type' => 'page',
                        'posts_per_page' => -1, // Retrieve all pages
                        's' => 'wp:carbon-fields/' . sanitize_title($post->post_name),
                        'search_columns' => ['post_content'],
                    ];


                    $query = get_posts($args);
                    add_action('admin_notices', function () use ($query) {
                        ?>
                        <?php
                        if ($query != null) {
                            ?>
                            <div class="notice notice-info is-dismissible">
                                <p>
                                    Deze block wordt momenteel gebruikt op de volgende pagina's:
                                    <?php
                                    foreach ($query as $page)
                                        echo "<a href='" . get_edit_post_link($page) . "'>{$page->post_title}</a>&nbsp&nbsp"
                                    ?>
                                </p>
                            </div>
                            <?php
                        } else {
                            ?>
                            <div class="notice notice-error is-dismissible">
                                <p>
                                    Deze block wordt momenteel (nog) niet gebruikt
                                </p>
                            </div>
                            <?php
                        }
                    });
                }
            }
        });
    }
}
