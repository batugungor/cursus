<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'LMS' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'A>0Oopkd]]Zg*fvV&G^Q3%o|RYWcvwr2`+0jCBce1(sw;b0he-^t*Ao9Fgc6r&W+' );
define( 'SECURE_AUTH_KEY',  'X!!a=epTp qi@EqphoQ{n%H>3(lv%@ya/3F..NXIqziuWU</`.S###M*<JAYigjJ' );
define( 'LOGGED_IN_KEY',    'mk]=O7qO!fg}]!SQ8:k]5;:l Q]&3E$*Vn1S!S;&{INRhNQ<>b`Ls^n041%s|<]:' );
define( 'NONCE_KEY',        'S3#oavOvQe_8 b}@sk;gtR E9RWO>5TkKf&]SOCAz[Q@+S35,+8%JFxc^{{C(7R~' );
define( 'AUTH_SALT',        'aW`w:SxP,#Vc%%At1wb4L.iC@F~x?E=#LJ7a{_9DYl5R|4y%_2rqLVP-9y>x~4#i' );
define( 'SECURE_AUTH_SALT', '}g?,}!DY@qJ62m{a%;/lU@b5c.(mlZe%@}q(&3eMv3@m ;oT|-HWc}Dz[(|R2BlY' );
define( 'LOGGED_IN_SALT',   'LJo&/-i_J5{/^mRvk8Q*QsidwkU+VifpC?e#KJYQp$J`d0I]o+%Fgx,CtSf80$~G' );
define( 'NONCE_SALT',       'Q<p%}qVrm^87VKxwQ334XdWa_}(iQK~X!.8i5#F:W]yhd#{lj;v~0Np0p#B</7F<' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', true );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
