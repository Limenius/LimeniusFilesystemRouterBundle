<?php


namespace Limenius\Bundle\FilesystemRouterBundle\Routing;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route as SymfonyRoute;
use Symfony\Cmf\Component\Routing\RouteProviderInterface;
use Limenius\Bundle\FilesystemRouterBundle\Routing\Route;
use Symfony\Component\Filesystem\Filesystem;

class FilesystemProvider implements RouteProviderInterface
{
    private $basePath;

    public function __construct($basePath)
    {
        $this->basePath = $basePath;
    }

    /**
     * @inheritdoc
     */
    public function getRouteCollectionForRequest(Request $request)
    {
        return $this->buildRoutes();
    }

    /**
     * @inheritdoc
     */
    public function getRouteByName($name)
    {
        $routes = $this->buildRoutes();
        foreach ($routes as $route) {
            if ($name == $route->getRouteKey()) {
                return $route;
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function getRoutesByNames($names = null)
    {
        if (null === $names) {
            return $this->getAllRoutes();
        }

        $routes = [];
        foreach ($names as $name) {
            $routes[] = $this->getRouteByName($name);
        }
        return $routes;
    }

    /**
     * Get all the routes in the filesystem.
     *
     * @return array
     */
    private function getAllRoutes()
    {
        return $this->buildRoutes();
    }

    private function buildRoutes()
    {
        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($this->basePath));
        $collection = new RouteCollection();
        $fs = new Filesystem();
        foreach ($iterator as $fileinfo) {
            $this->buildRoute($collection, $fileinfo, $fs);
        }
        $collection->addPrefix('doc');
        return $collection;
    }

    private function buildRoute(RouteCollection $collection, $fileinfo, Filesystem $fs) {
        if ($fileinfo->isReadable() && $fileinfo->isFile() && $fileinfo->getExtension() == 'html') {
            $file = $fileinfo->openFile();
            $route = new Route();
            $relativePath = $fs->makePathRelative($file->getPath(), $this->basePath);
            if ('./' === $relativePath) {
                $relativePath = '';
            }
            $path = $relativePath.$file->getFilename();
            $route->setPath($path);
            $name = preg_replace('/[^a-z0-9]+/', '_', strtolower($path));
            $route->setRouteKey($name);
            $route->setContent($file->fread($file->getSize()));
            if ($route instanceof SymfonyRoute) {
                $collection->add($name, $route);
            }
        }
    }
}
