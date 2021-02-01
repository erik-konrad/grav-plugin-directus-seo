<?php
namespace Grav\Plugin;

use Composer\Autoload\ClassLoader;
use Grav\Common\Config\Config;
use Grav\Common\Grav;
use Grav\Common\Plugin;
use Grav\Plugin\DirectusSEO\Utility\DirectusSEOUtility;

/**
 * Class DirectusSEOPlugin
 * @package Grav\Plugin
 */
class DirectusSEOPlugin extends Plugin
{
    /**
     * @var string
     */
    protected $seoFile = '';

    /**
     * @var DirectusSEOUtility
     */
    protected $directusUtility;




    /**
     * @return array
     *
     * The getSubscribedEvents() gives the core a list of events
     *     that the plugin wants to listen to. The key of each
     *     array section is the event that the plugin listens to
     *     and the value (in the form of an array) contains the
     *     callable (or function) as well as the priority. The
     *     higher the number the higher the priority.
     */
    public static function getSubscribedEvents(): array
    {
        return [
            'onPluginsInitialized' => [
                ['autoload', 100000], // TODO: Remove when plugin requires Grav >=1.7
                ['onPluginsInitialized', 0]
            ]
        ];
    }

    /**
    * Composer autoload.
    *is
    * @return ClassLoader
    */
    public function autoload(): ClassLoader
    {
        return require __DIR__ . '/vendor/autoload.php';
    }

    /**
     * Initialize the plugin
     */
    public function onPluginsInitialized(): void
    {
        // Don't proceed if we are in the admin plugin
        if ($this->isAdmin()) {
            return;
        }

        // Enable the main events we are interested in
        $this->enable([
            'onPageInitialized' => ['onPageInitialized', 0],
            'onTwigSiteVariables' => ['onTwigSiteVariables', 0],
        ]);

    }

    public function onPageInitialized() {
        $this->initialize();
        $this->processSeo();
    }

    public function onTwigSiteVariables() {

        $data = [];
        if ( file_exists( $this->seoFile ) )
        {
            $contents = file_get_contents( $this->seoFile );
            $data = json_decode( $contents, true );
            if ( count($data['data']) > 1 )
            {
                $data = $data['data'];
            }
            else if (isset($data['data'][0]))
            {
                $data = $data['data'][0];
            }
        }

        $this->grav['twig']->twig_vars['directus_seo'] = $data;
    }

    /**
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    protected function initialize() {
        $this->seoFile = $this->grav['page']->path() . '/seo.json';
        $this->directusUtility = new DirectusSEOUtility(
            $this->config["plugins.directus"]['directus']['directusAPIUrl'],
            $this->grav,
            $this->config["plugins.directus"]['directus']['email'],
            $this->config["plugins.directus"]['directus']['password'],
            $this->config["plugins.directus"]['directus']['token'],
            isset($this->config["plugins.directus"]['disableCors']) && $this->config["plugins.directus"]['disableCors']
        );
    }

    private function processSeo() {
        if(isset($this->grav['page']->header()->directus['remote_meta']) && $this->grav['page']->header()->directus['remote_meta']) {
            $filter = [
                $this->config()['seo_slugField'] => [
                    'operator' => '_eq',
                    'value' => $this->grav['uri']->route()
                ]
            ];

            if(!file_exists($this->seoFile)) {
                try {
                    $request = $this->directusUtility->generateRequestUrl($this->config()['seo_table'], 0, 2, $filter);
                    $data = $this->directusUtility->get($request);
                    $this->writeFileToFileSystem($data->toArray());
                } catch (\Exception $e) {
                    $this->grav['debugger']->addException($e);
                }
            }
        }
    }

    /**
     * @param array $data
     * @param string $path
     * @param string $filename
     */
    private function writeFileToFileSystem(array $data) {
        try {
            $fp = fopen($this->seoFile, 'w');
            fwrite($fp, json_encode($data));
            fclose($fp);
        } catch (\Exception $e) {
            $this->grav['debugger']->addException($e);
        }
    }
}
