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
define( 'DB_NAME', 'wordpress' );

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
define( 'AUTH_KEY',         '-=wPw}@!>p_7[vgsgQ,IQ?cZP))rv;!3;i9y{on<Lo<~q>+p@!!>40v%}T1c/$k2' );
define( 'SECURE_AUTH_KEY',  '6Z;Wm!D&eBl^#A!sX&Y0e<W&J]lHu+t}97Pz!ZnU6$tO&WEsr{7;#74-ADe7NB|#' );
define( 'LOGGED_IN_KEY',    '=7$U(8^2jjq+wx1m,=hz|]<49K8BY[!F4DI$*pY**21;o4mc^gw89:N;KNT}AKB=' );
define( 'NONCE_KEY',        '?8jrdJtw+ilLFTer]^o`Yna4oaxt7P[5h1a1&~M{q87?K~}(@|H}=?r+0_TM|Tyr' );
define( 'AUTH_SALT',        'C5dR&NmS!c$9p67~YffghmDwg*]Bs]_1|=t$1+Q*@R|bGgOc#DJ|^!W6e%de k<+' );
define( 'SECURE_AUTH_SALT', '*])a=tZ[E6s,{}x)BC>V~yWwt&}M/^-fG5L=aq3/l2/z9zF))u_:2j<;BnXx5co!' );
define( 'LOGGED_IN_SALT',   '-{NmK Gup,0qYaW<.7Z5(y= #t6EuWOggPm(o);jarim.xd 9@-RHm^~dLQ%0^@$' );
define( 'NONCE_SALT',       'c8wAtkY%B.xHa@=V[1d;*R)7+%.9%DhqdozO]5Ise%BwUZx,%!#fS<D^:nw<&:A~' );

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
