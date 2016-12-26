<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, and ABSPATH. You can find more information by visiting
 * {@link https://codex.wordpress.org/Editing_wp-config.php Editing wp-config.php}
 * Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
//define('DB_NAME', 'zqhshop');
//define('WP_CACHE', true); //Added by WP-Cache Manager
define('DB_NAME', 'vj2016');

/** MySQL database username */
//define('DB_USER', 'root');
define('DB_USER', 'wwwuser');

/** MySQL database password */
//define('DB_PASSWORD', '1234');
define('DB_PASSWORD', 'tesT1234');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'A=%RSZrezokV|q?$cRRssbMjSBaPL-;p+F7O]%Askd{C#&)zPj$ak2end/0W`e?[');
define('SECURE_AUTH_KEY',  ';pyV+{Z;^LCl)?g*-H=%|cS&U9F`A&:6-y.^tYZDU@<HmP>8c@$3cA&)EbrB)US<');
define('LOGGED_IN_KEY',    'l|>.oVTTTYA QP}@)[C]+1xAJ<g+j0K&tyA?IdT;|-oOac;Sh-zgRR!?77~xzGsZ');
define('NONCE_KEY',        '%}HJT$q-qxQ=.-PuEw7>V#bu>t<z84ui+1kq#}j&Z:jD@Ici}S-IKwg]|?~G~Ovx');
define('AUTH_SALT',        'MWyz!&DmPsjWQv^eP&2R#$!^{SlNWVUtyjT#pF(v=x+xi$9`i9p^w0WU$)!Ag1d?');
define('SECURE_AUTH_SALT', 'Wc>,u1L0np,U}-qO| $$u<&rr/u32W_Wkap7XjSE:%{ksf<`<X$~)i-zJ`5Nsx T');
define('LOGGED_IN_SALT',   'bRrp$rccO02d58j+V)$f^sT;/H{7#t&>M]|q8O&$!Ta-bP#6YP9 NRpW2O)-F6.]');
define('NONCE_SALT',       '}>+?bmxr wl6|B0V?>r[` |}PoC`|Y=*^8vf86gW1KZL@Pw_VFX=`R*]aIZ[+8Cf');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
