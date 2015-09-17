<?php


namespace Limenius\Bundle\FilesystemRouterBundle\Routing;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route as SymfonyRoute;
use Symfony\Cmf\Component\Routing\RouteProviderInterface;
use Symfony\Component\Filesystem\Filesystem;
use Limenius\Bundle\FilesystemRouterBundle\Routing\Route;
use Limenius\Bundle\FilesystemRouterBundle\Document\ContentDocument;

class FilesystemProvider implements RouteProviderInterface
{
    private $collections = [];

    public function addCollection($definition)
    {
        $this->collections[] = [
            'base_path' => $definition['path'],
            'prefix' => $definition['prefix'],
            'extensions_exposed' => $definition['extensions_exposed'],
            ];
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
        $fs = new Filesystem();
        foreach ($this->collections as $definition) {
            $basePath = $definition['base_path'];
            $path = $basePath . '/' . $name;
            if ($fs->exists($path)) {
                $fileInfo = new \SplFileInfo($path);
                $route = $this->buildRoute($fileInfo, $fs, $basePath, $definition['extensions_exposed']);
                if ($route && isset($definition['prefix']) && $definition['prefix']) {
                    $route->setPath('/'.$definition['prefix'].$route->getPath());
                }
                return $route;
            }
        }
        throw new RouteNotFoundException('Route ' . $name . ' not found');
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
        $routes = $this->buildRoutes();
        foreach ($names as $name) {
            foreach ($routes as $route) {
                if ($name == $route->getRouteKey()) {
                    $routes[] = $route;
                }
            }
        }
        return $routes;
    }

    /**
     * Get all the routes in the directory.
     *
     * @return array
     */
    private function getAllRoutes()
    {
        return $this->buildRoutes();
    }

    private function buildRoutes()
    {
        $allCollections = new RouteCollection();
        $fs = new Filesystem();
        foreach ($this->collections as $definition) {
            $collection = new RouteCollection();
            $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($definition['base_path']));
            foreach ($iterator as $fileinfo) {
                if ($route = $this->buildRoute($fileinfo, $fs, $definition['base_path'], $definition['extensions_exposed'])) {
                    $collection->add($route->getRouteKey(), $route);
                }
            }
            if (isset($definition['prefix']) && $definition['prefix']) {
                $collection->addPrefix($definition['prefix']);
            }
            $allCollections->addCollection($collection);
        }
        return $allCollections;
    }

    private function buildRoute(\SplFileInfo $fileinfo, Filesystem $fs, $basePath, $extensionsExposed) {
        if ($fileinfo->isReadable() && $fileinfo->isFile() && in_array($fileinfo->getExtension(), $extensionsExposed)) {
            $route = new Route();
            $relativePath = $fs->makePathRelative($fileinfo->getPath(), $basePath);
            if ('./' === $relativePath) {
                $relativePath = '';
            }
            $path = $relativePath.$fileinfo->getFilename();
            $route->setPath($path);
            $route->setRouteKey($path);
            $route->setContent(new ContentDocument($fileinfo->getRealPath()));
            return $route;
        } else
        return null;
    }
}
