<?php

use App\Http\Lumberjack;
use Carbon_Fields\Carbon_Fields;

require_once('vendor/autoload.php');

// Create the Application Container
$app = require_once('bootstrap/app.php');

if ( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

// Bootstrap Lumberjack from the Container
$lumberjack = $app->make(Lumberjack::class);
$lumberjack->bootstrap();

// Import our routes file
require_once('routes.php');

// Set global params in the Timber context
add_filter('timber_context', [$lumberjack, 'addToContext']);
