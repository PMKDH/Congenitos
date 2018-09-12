<?php
/** 
 * Configuración básica de WordPress.
 *
 * Este archivo contiene las siguientes configuraciones: ajustes de MySQL, prefijo de tablas,
 * claves secretas, idioma de WordPress y ABSPATH. Para obtener más información,
 * visita la página del Codex{@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} . Los ajustes de MySQL te los proporcionará tu proveedor de alojamiento web.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** Ajustes de MySQL. Solicita estos datos a tu proveedor de alojamiento web. ** //
/** El nombre de tu base de datos de WordPress */
define('DB_NAME', 'prueba');

/** Tu nombre de usuario de MySQL */
define('DB_USER', 'eloylopezpmk');

/** Tu contraseña de MySQL */
define('DB_PASSWORD', '123456');

/** Host de MySQL (es muy probable que no necesites cambiarlo) */
define('DB_HOST', 'localhost');

/** Codificación de caracteres para la base de datos. */
define('DB_CHARSET', 'utf8mb4');

/** Cotejamiento de la base de datos. No lo modifiques si tienes dudas. */
define('DB_COLLATE', '');

/**#@+
 * Claves únicas de autentificación.
 *
 * Define cada clave secreta con una frase aleatoria distinta.
 * Puedes generarlas usando el {@link https://api.wordpress.org/secret-key/1.1/salt/ servicio de claves secretas de WordPress}
 * Puedes cambiar las claves en cualquier momento para invalidar todas las cookies existentes. Esto forzará a todos los usuarios a volver a hacer login.
 *
 * @since 2.6.0
 */
define('AUTH_KEY', '>C;-wYSFfZY6?+[NZe|d]+[5RY6$H@Su@s-A?w_c,yo@=2.$J:AH>[QR<KGG49c(');
define('SECURE_AUTH_KEY', 'Rw/.JLP*->h+JzRihFR.#BB70+lxNBG|6nbp1L94$?lHSX]SV.~syTwG^F7OuM[:');
define('LOGGED_IN_KEY', '~^K/QmyfeI!ID#Y+]+p_^t0DI:.qrH>o9w6UG>REt&={)o0kE/OHh-#*vn;Vtle4');
define('NONCE_KEY', 'qHI>vTbQh2wm9~j ]ak l%n8D=#7)-:=#hA9S%,gHL[o<HoPt.w>b2R44PNCU>K9');
define('AUTH_SALT', 'YketLh]/w<K^1,[|(o,EnW5>:xS(diK9xv.PE=qd}DEw$g9meI`X([R/P[r/`C+p');
define('SECURE_AUTH_SALT', 'eCFr=X2o%pkJd1Q/*ezmtJdgm B<6)TkBVeHCAJS+=#8ZBS#|0c0k*!Hl$MhmU3x');
define('LOGGED_IN_SALT', 'zoq+{~TJ)5D _}9QaB@J4oohS[DD+o!8#]XmA{1p{!3Er?d[6Q+9%Jm}`O{36t6`');
define('NONCE_SALT', 'G] -{BDr0UlGR5|8$ihD=n.ZFg4 JOz}09{H0kN7f~K_ti%<D*WD=#:HQ/gtG^}-');

/**#@-*/

/**
 * Prefijo de la base de datos de WordPress.
 *
 * Cambia el prefijo si deseas instalar multiples blogs en una sola base de datos.
 * Emplea solo números, letras y guión bajo.
 */
$table_prefix  = 'wp_';


/**
 * Para desarrolladores: modo debug de WordPress.
 *
 * Cambia esto a true para activar la muestra de avisos durante el desarrollo.
 * Se recomienda encarecidamente a los desarrolladores de temas y plugins que usen WP_DEBUG
 * en sus entornos de desarrollo.
 */
define('WP_DEBUG', false);

/* ¡Eso es todo, deja de editar! Feliz blogging */

/** WordPress absolute path to the Wordpress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

