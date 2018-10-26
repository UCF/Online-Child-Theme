<?php
/**
 * General utilities
 **/

/**
 * Returns a Online Child Theme mod's default value.
 *
 * @since 1.0.0
 * @param string $theme_mod The name of the theme mod
 * @return mixed Theme mod default value, or false if a default is not set
 **/
function online_get_theme_mod_default( $theme_mod ) {
	return ucfwp_get_theme_mod_default( $theme_mod, ONLINE_THEME_CUSTOMIZER_DEFAULTS );
}


/**
 * Returns a Online Child Theme mod value or a default value
 * if the theme mod value hasn't been set yet.
 *
 * @since 1.0.0
 * @param string $theme_mod The name of the theme mod
 * @return mixed Theme mod value or its default
 **/
function online_get_theme_mod_or_default( $theme_mod ) {
	return ucfwp_get_theme_mod_or_default( $theme_mod, ONLINE_THEME_CUSTOMIZER_DEFAULTS );
}
