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
define('DB_NAME', 'congenitos');

/** Tu nombre de usuario de MySQL */
define('DB_USER', 'josegonzalezpmk');

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
define('AUTH_KEY', 'Tu24KF ,%5N:&U`?Aodp`*SQ-H[`4w0pLONV*tyLgG>C*R:IOP,]@??(<DguEOoR');
define('SECURE_AUTH_KEY', 'l<DK,P^l*2V@$H2M.f_v(;eM#GTLbv[Ub[OXc5iYX[:KJmCUH_cZD$BB}6lQ0FI+');
define('LOGGED_IN_KEY', 'E8/)}gD!Rr+K*fxXHM=hU8} 14;>2OI~sa#.4`5o@BX^[}a1tFVLzLGty?Zwqe+d');
define('NONCE_KEY', 'PWt]Uv?VAQTnk6wk%;f~bza,1Uut3z95xB[W2GYKT$y:ApjC;k^4OZomMy9W!,U@');
define('AUTH_SALT', 'x33D%mx(X zL@CE84R<YYw{`F!)Ho00{MuG(zN9:Rz,cnj@e-t!99Fl^zh3+nn0Z');
define('SECURE_AUTH_SALT', '`P@LcFUx!x)N+1UNz;ZKk.} _gpYnpn@KiBsMb$_W#)Bfk DSYp=&!BRD)%zW9F(');
define('LOGGED_IN_SALT', 'FJ+;`RX,&&;+hb.[oN=j!ba}w+mpR];j9P1It{odpKu!sig-=XwFDD@lA[<1^5?u');
define('NONCE_SALT', ')kI[!Mf;z[AbK/2*:Y~V$u{/ZY^_JCF%wEN~;A.>Sy7+Td$N8%N]tdYnbPu:bL@E');

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

