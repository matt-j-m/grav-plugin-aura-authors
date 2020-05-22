<?php
namespace Grav\Plugin;

use Grav\Common\Plugin;
use Grav\Common\Page\Page;
use RocketTheme\Toolbox\Event\Event;
use RocketTheme\Toolbox\File\File;
use Symfony\Component\Yaml\Yaml;

/**
 * Class AuraAuthorsPlugin
 * @package Grav\Plugin
 */
class AuraAuthorsPlugin extends Plugin
{
    protected static $authorsFile = DATA_DIR . 'authors/authors.yaml';
    protected $route = 'authors';

    public static function getAuthors()
    {
        $fileInstance = File::instance(self::$authorsFile);
        if (!$fileInstance->content()) {
          return;
        }
        return Yaml::parse($fileInstance->content());
    }

    public function onPluginsInitialized()
    {
        // Admin only events
        if ($this->isAdmin()) {
            $this->enable([
                'onAdminMenu'         => ['onAdminMenu', 0],
                'onTwigTemplatePaths' => ['onTwigTemplatePaths', 0],
                'onPageInitialized'   => ['onPageInitialized', 0],
                'onGetPageBlueprints' => ['onGetPageBlueprints', 0],
                'onAdminSave' => ['onAdminSave', 0],
            ]);
            return;
        }

    }

    /**
     * Extend page blueprints with additional configuration options.
     *
     * @param Event $event
     */
    public function onGetPageBlueprints($event)
    {
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
            $author = array('author' => array($page->header()->aura['author']));
            $page->header()->taxonomy = array_merge($page->header()->taxonomy, $author);
        }

    }

    public function onPageInitialized()
    {
        $page = $this->grav['page'];
        if ($page->template() === 'authors') {
            $post = $this->grav['uri']->post();
            if ($post) {
                $authors = isset($post['data']['authors']) ? $post['data']['authors'] : [];
                $file = File::instance(self::$authorsFile);
                $file->save(Yaml::dump($authors));
                $admin = $this->grav['admin'];
                $admin->setMessage($admin::translate('PLUGIN_ADMIN.SUCCESSFULLY_SAVED'), 'info');
            }
        }
    }

    public function onTwigTemplatePaths()
    {
        $this->grav['twig']->twig_paths[] = __DIR__ . '/admin/templates';
    }

    public function onAdminMenu()
    {
        $this->grav['twig']->plugins_hooked_nav['Authors'] = ['route' => $this->route, 'icon' => 'fa-users'];
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

}