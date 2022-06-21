<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'octanewwp' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'rLHnkRNohWnLeL6ERs9M&WQvB3v' );

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
define( 'AUTH_KEY',         'W]S{xij&x_/nO>fcouR>G{#PIWX0]ljTMlZ(SLN^rDP#CV034D=2UY|KUvLD I|i' );
define( 'SECURE_AUTH_KEY',  'I&7IV4b.c^::YV(Lm-=692opUIs<6mchp,ehlul0B9e7tiln:,B/Ch4zl.k hyD1' );
define( 'LOGGED_IN_KEY',    '6}AWf8*gIR~5rXw*~cU?>!:?Tm1<6 5kY:ar>,}dc%+vx~D,Wa5^}T-p}hh>&8}l' );
define( 'NONCE_KEY',        'bV}diMcL1PjT+BCA3~C]X%=rt-mb9_Rc49rw8DVuB@KhZXmJ|F:pCNl^5p5{H!6e' );
define( 'AUTH_SALT',        '0?%W5%pEJl^df@oEo&2jZw^P)U<@Q(j7i([;)AHwt$J8w82KH&,I1^z }Po+);Uo' );
define( 'SECURE_AUTH_SALT', 'D-2fk:~wt  abcARU7jBW,&f({3J6Y[|L<Q,>:h]J,f/#C=6[q_XR?}I&6`-=/$=' );
define( 'LOGGED_IN_SALT',   '!FI,[yQwG=%Xe28u3 ?XYUWtEE_TSt^#oI@jl~G`Bt<6:v$qZDGce?7gc0);Qj+$' );
define( 'NONCE_SALT',       '+oR4Ej^-gMQ)zrO|(>bg.SKn.5>,ji+pvI3n5SOXCrzN_M%?NiMMm~-HL~ovr{[f' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'owp_';

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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
define('FS_METHOD', 'direct');