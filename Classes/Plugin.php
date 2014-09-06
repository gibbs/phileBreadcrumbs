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
    protected $breadcrumbs = array();

    /**
     * Register plugin events via the constructor
     *
     * @return void
     */
    public function __construct()
    {
        \Phile\Event::registerEvent('request_uri', $this);
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
        if($eventKey == 'request_uri')
        {
            $page = new \Phile\Repository\Page();
            $path = array_filter(explode('/', $data['uri']) );

            // Add the homepage to the start of the path
            $path = array_merge(array(''), $path);

            $breadcrumbs  = array();
            $current_path = null;

            foreach($path as $crumb) {
                $current_path .=  '/' . $crumb;
                $current_page = $page->findByPath($current_path);

                $uri = $current_page->getUrl();

                // Check and remove 'index' from the end of the path if enabled
                if($this->settings['strip_index'] === true) {
                    if (substr($uri, strlen($uri) - 5) == 'index') {
                        $uri = substr_replace($uri, '', strlen($uri) - 5);
                    }
                }

                // Create the breadcrumb
                $breadcrumbs[] = array(
                    'active' => $crumb == end($path) ? true : false,
                    'meta'   => $current_page->getMeta()->getAll(),
                    'uri'    => ltrim($uri, '/'),
                    'url'    => \Phile\Utility::getBaseUrl() . $uri
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
