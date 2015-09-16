# LimeniusFilesystemRouterBundle

This bundle provides a Symfony chained router that adds routes for static files in a directory.

Use case: You may want to integrate a product static content generator engine (Sphinx, Jekyll, you name it),
or you simply have some html files in a directory and you want every one of them to be a route
in your Symfony app, because you want, for instance, to...

* Embed a bunch of static html documents into a Twig template, with a menu, a sidebar.
* Generate routes to them.
* Preprocess them extracting for instance the title, or converting them from markdown to html.
* ...

## Usage
### Installation

Open a command console, enter your project directory and execute the following command to download the latest stable version of this bundle:

    $ composer require limenius/filesystem-router-bundle

### Configuration

This bundle uses the Symfony CMF Dynamic router, so we need to configure it in our `app/config.yml` file:

    cmf_routing:
        chain:
            routers_by_id:
                router.default: 200
                cmf_routing.dynamic_router: 100
        dynamic:
            route_provider_service_id: limenius_filesystem_router.route_provider
            controllers_by_type:
                filesystem_route: Acme\MyBundle\Controller\MyController::myAction

Where in the last line, we have specified the action that will deal with the static content.

Also, we add some lines to confgure the bundle

    limenius_filesystem_router:
        collections:
            Docs:
                path: "%kernel.root_dir%/Resources/my_static_directory/"
                prefix: doc

`collections` is a list of directories that you want to route.
Every collection have a required `path` and an optional `prefix`.
This bundle will create a route for every `html` file found recursively in the path.

### Create an action

The action will receive the contents of the file in the variable `$contentDocument`.

It can be as simple as:

    /**
     * @Template()
     */
    public function docsAction($contentDocument)
    {
        return array('content' => $contentDocument);
    }

With a template containing:

    {{ content |raw }}    

Of course you can preprocess the document dealing with `$contentDocument`, for instance crawling it to extract the title or other information.

Check out your new routes with the `app/console router:debug` command.

## License

This bundle is under the MIT license. See the complete license in the file:

    LICENSE.md

