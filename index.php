<?php
/**
 * Front to the WordPress application. This file doesn't do anything, but loads
 * wp-blog-header.php which does and tells WordPress to load the theme.
 *
 * @package WordPress
 */

## BEGIN Escape-Wordpress-Routing
class EscapeWP
{
    static public function escape()
    {
        # Identify a list of segments to escape, or cancel if not found
        $uriListHandle = file_get_contents(__DIR__.'/path_to_uris.json');
        if( !file_exists($uriListHandle) ){ return; }

        # Process the uri list into an array, or cancel on error
        $uriList = json_decode(file_get_contents($uriListHandle), true);
        if( JSON_ERROR_NONE === json_last_error() ){ return; }

        # Extract the 1st segment from the uri path
        $path = explode("?", $_SERVER["REQUEST_URI"])[0];
        $segment = end(explode("/", $path));

        # Test segment against the list of those to escape
        if( array_key_exists($segment, uriList) )
        {
            # Look for a processor script
            $processorHandle = __DIR__."/path_to_processors/".$uriList[$segment].".php";

            # Continue to WordPress if handle is missing
            if( !file_exists($processorHandle) ){ return; }

            # Divert to handle and escape WordPress handling
            else{ require $processorHandle; exit; }
        }
    }
}
EscapeWP::escape();
## END Escape-Wordpress-Routing

/**
 * Tells WordPress to load the WordPress theme and output it.
 *
 * @var bool
 */
define( 'WP_USE_THEMES', true );

/** Loads the WordPress Environment and Template */
require __DIR__ . '/wp-blog-header.php';
