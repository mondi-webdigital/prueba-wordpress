<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'db111ac3615ffc4ddfaf82a2f70040b8ca');

/** MySQL database username */
define('DB_USER', 'opgjgcjdaogkduns');

/** MySQL database password */
define('DB_PASSWORD', 'MJmFcAeTEYKV4H7vJVZWVGwRHujnFfSEYyv73dgXePVJWrtqZCavz33fmB6PqYny');

/** MySQL hostname */
define('DB_HOST', '111ac361-5ffc-4ddf-af82-a2f70040b8ca.mysql.sequelizer.com');

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
define('AUTH_KEY',         '{9F~df&(Q${J@K]V{9qX|9vs4=G}kxvko}C?3 a]9kBSX41{(Ouze{f;m2)=Kms%');
define('SECURE_AUTH_KEY',  'py%iOh=*K*<jO&+Zz0VtpCuo+c8(zkA-Jq hA1Qe<^=6*(1420+*_avM!.PBZ/ei');
define('LOGGED_IN_KEY',    'fd4_Qbx c(hBhlk3q2xy-5a4O*Bw9.2tk]DTcZPLH5J[O,g*tc(TQTDb=[?7KngI');
define('NONCE_KEY',        'sT|m+lI+J^O.r yS)lmO$*4S]zwzjn5o{MAOt(2ZjX{a,~i-d{=n)W12[!cT:B0_');
define('AUTH_SALT',        '$Sr9>m!hrw}&F}fBmFjvBp>v8V0ET41{j(5S-h>(9moc`%rG.@E`)2h2K=}!R?{j');
define('SECURE_AUTH_SALT', 'RL$@n [&Cqy*alr:Qz$Rt{6,HM[C_! =s/<9T35LuJ!5$YrVFcDxhJ-p*og}2%?S');
define('LOGGED_IN_SALT',   '[leA;ZwKe`ob?cB6HSnEEe:D^.2iYKr*bO9(JG]m?h7.^{crf`RP[5Ks:: RWE*!');
define('NONCE_SALT',       '*NHxl0;f,&;Db3(ogg -,)$s(gjL`?mC`y/1,^P^f}R+njjRr5z&*SN?Nqd1[>v|');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
