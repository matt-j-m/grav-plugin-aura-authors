<?php
namespace Grav\Plugin;

use Grav\Common\Data;
use Grav\Common\Plugin;
use RocketTheme\Toolbox\Event\Event;

/**
 * Class AuraAuthorsPlugin
 * @package Grav\Plugin
 */
class AuraAuthorsPlugin extends Plugin
{

    /** @var array */
    static protected $authorList = array();

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'onPluginsInitialized' => ['onPluginsInitialized', 0],
            'onTwigTemplatePaths' => ['onTwigTemplatePaths', 0],
            'onPageInitialized' => ['onPageInitialized', 0],
            'onTwigSiteVariables' => ['onTwigSiteVariables', 0],
            'onBlueprintCreated' => ['onBlueprintCreated', 0],
        ];
    }

    /**
     * Initialize configuration
     */
    public function onPluginsInitialized()
    {

        // Add author to site taxonomies
        $taxonomies = $this->config->get('site.taxonomies');
        $taxonomies[] = 'author';
        $this->config->set('site.taxonomies', $taxonomies);

        // Populate author list for use in blueprint
        $authors = $this->grav['config']->get('plugins.aura-authors.authors');
        if ($authors) {
            foreach ($authors as $author) {
                if (!array_key_exists($author['label'], self::$authorList)) {
                    self::$authorList[$author['label']] = $author['name'];
                }
            }
        }
        asort(self::$authorList);
    }


    /**
     * Extend page blueprints with additional configuration options.
     *
     * @param Event $event
     */
    public function onBlueprintCreated(Event $event)
    {
        static $inEvent = false;

        /** @var Data\Blueprint $blueprint */
        $blueprint = $event['blueprint'];
        if (!$inEvent && $blueprint->get('form/fields/tabs', null, '/')) {
            $inEvent = true;
            $blueprints = new Data\Blueprints(__DIR__ . '/blueprints/');
            $extends = $blueprints->get('aura-authors');
            $blueprint->extend($extends, true);
            $inEvent = false;
        }
    }

    /**
     * Add plugin directory to twig lookup paths
     */
    public function onTwigTemplatePaths()
    {
        $this->grav['twig']->twig_paths[] = __DIR__ . '/templates';
    }

    /**
     * Add author to page taxonomy
     *
     * @param Event $e
     */
    public function onPageInitialized()
    {
        if ($this->isAdmin()) {
            return;
        }
        $page = $this->grav['page'];
        $header = $page->header();
        if ((isset($header->aura['author'])) && ($header->aura['author'] != '')) {
            $taxonomy = $page->taxonomy();
            $taxonomy['author'][] = $header->aura['author'];
            $page->taxonomy($taxonomy);
        }
    }

    /**
     * Create structured authors array and expose to Twig
     */
    public function onTwigSiteVariables()
    {
        $authors = array();
        $raw = $this->grav['config']->get('plugins.aura-authors.authors');
        if ($raw) {
            foreach ($raw as $author) {
                $authors[$author['label']] = $author;
            }
        }
        $this->grav['twig']->twig_vars['authors'] = $authors;
    }

    public static function listAuthors() {
        return self::$authorList;
    }

}
