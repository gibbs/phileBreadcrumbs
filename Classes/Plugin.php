<?php
/**
 * Return an array to help produce breadcrumbs
 *
 * @author   Dan Gibbs <daniel.gibbs@gmail.com>
 * @package  phile-breadcrumbs
 * @link     https://github.com/Gibbs/phileBreadcrumbs
 * @license  http://opensource.org/licenses/MIT
 */
namespace Phile\Plugin\Gibbs\phileBreadcrumbs;

class Plugin extends \Phile\Plugin\AbstractPlugin implements
    \Phile\Gateway\EventObserverInterface
{
    protected $breadcrumbs = null;

    /**
     * Register plugin events via the constructor
     *
     * @return void
     */
    public function __construct()
    {
        \Phile\Event::registerEvent('before_load_content', $this);
        \Phile\Event::registerEvent('template_engine_registered', $this);
    }

    /**
     * Listen to event triggers
     *
     * @param  string  $eventKey  Triggered event key
     * @param  array   $data      Array of event data
     * @return void    
     */
    public function on($eventKey, $data = null)
    {
        if($eventKey == 'before_load_content')
        {
            $root = realpath(CONTENT_DIR);

            // Remove the root directory to get the page path
            $page  = str_replace(
                realpath(CONTENT_DIR), '', realpath($data['filePath'])
            );

            // Current URI segments
            $uri_segments = explode('/', $page);

            $current_uri = array();
            $breadcrumbs = array();

            // Build each breadcrumb by uri segment
            foreach($uri_segments as $uri_segment) {

                // Add the current uri to an array
                $current_uri = array_merge( $current_uri, array($uri_segment) );

                $uri = implode('/', $current_uri);

                // The default array index
                $index = count($breadcrumbs);

                // Homepage
                if( empty($uri) )
                    $uri = '/';

                if( end($uri_segments) == $uri_segment ) {
                    $active = true;
                    $meta = new \Phile\Model\Meta(
                        file_get_contents($root.$uri)
                    );

                    // Add directory indexes to end of existing array
                    if (strpos($uri, 'index.md') !== false)
                        $index = (count($breadcrumbs) -1);

                    // Remove indexes from URI
                    $uri = str_replace(array('index', CONTENT_EXT), '', $uri);
                }
                else {
                    $active = false;
                    $meta = new \Phile\Model\Meta(
                        file_get_contents($root . $uri . '/index.md')
                    );
                }

                $breadcrumbs[$index] = array(
                    'active' => $active,
                    'meta'   => $meta,
                    'uri'    => $uri,
                );
            }

            $this->breadcrumbs = $breadcrumbs;
        }

        // Add the variable when the template engine is registered
        if($eventKey == 'template_engine_registered')
        {
            $data['data']['breadcrumbs'] = $this->breadcrumbs;
        }
    }
}
