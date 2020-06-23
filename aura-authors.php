<?php
namespace Grav\Plugin;

use Grav\Common\Plugin;
use Grav\Common\Page\Page;
use RocketTheme\Toolbox\Event\Event;

/**
 * Class AuraAuthorsPlugin
 * @package Grav\Plugin
 */
class AuraAuthorsPlugin extends Plugin
{

    /** @var array */
    static protected $authors = [];

    public static function getAuthors()
    {
        return self::$authors;
    }

    public function onPluginsInitialized()
    {
        // Admin only events
        if ($this->isAdmin()) {
            $this->enable([
                'onGetPageBlueprints' => ['onGetPageBlueprints', 0],
                'onAdminSave' => ['onAdminSave', 0],
            ]);
            return;
        }

        // Frontend events
        $this->enable([
            'onTwigSiteVariables' => ['onTwigSiteVariables', 0],
            'onTwigTemplatePaths' => ['onTwigTemplatePaths', 0],
        ]);

    }

    /**
     * Add plugin directory to twig lookup paths
     */
    public function onTwigTemplatePaths()
    {
        $this->grav['twig']->twig_paths[] = __DIR__ . '/templates';
    }

    /**
     * Extend page blueprints with additional configuration options.
     *
     * @param Event $event
     */
    public function onGetPageBlueprints($event)
    {

        self::$authors = $this->grav['config']->get('plugins.aura-authors.authors');

        $types = $event->types;
        $types->scanBlueprints('plugins://' . $this->name . '/blueprints');
    }

    public function onAdminSave(Event $event)
    {
        // Don't proceed if Admin is not saving a Page
        if (!$event['object'] instanceof Page) {
            return;
        }

        $page = $event['object'];
        if (isset($page->header()->aura['author'])) {

            // TODO: tidy this section
            // Also consider how to remove an author (currently need to go to expert mode)
            // Also what if someone wants to set multiple author tags? should proably allow but only consider the aura one for meta output
            if (isset($page->header()->taxonomy)) {
                $taxonomy = $page->header()->taxonomy;
            } else {
                $taxonomy = [];
            }
            $taxonomy['author'] = array($page->header()->aura['author']);
            $page->header()->taxonomy = $taxonomy;
        }
    }

    public static function listAuthors() {
        $authorList = [];
        $authors = self::getAuthors();
        foreach ($authors as $author) {
            $authorList[$author['label']] = $author['name'];
        }
        asort($authorList);
        return $authorList;
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

}