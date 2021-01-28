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
     * DirectusSEOPlugin constructor.
     * @param $name
     * @param Grav $grav
     * @param Config|null $config
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function __construct($name, Grav $grav, Config $config = null)
    {
        parent::__construct($name, $grav, $config);
        $this->seoFile = $this->grav['page']->path() . '/seo.json';
    }

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
        ]);

    }

    public function onPageInitialized() {
        //$this->initializeDirectusUtility();
       // $this->processSeo();
    }

    /**
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    protected function initializeDirectusUtility() {
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
        dump($this->config());
        dump($this->grav['uri']->route());
        dump($this->grav['page']->path());
        dump($this->seoFile);
    }
}
