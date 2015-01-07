<?php namespace App\Providers;

use DebugBar\Bridge\DoctrineCollector;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\DBAL\Logging\DebugStack;
use Illuminate\Support\ServiceProvider;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

class DoctrineProvider extends ServiceProvider {

    public function register()
    {
        $this->app->bindShared('Doctrine\ORM\EntityManager', function () {
            $paths = array(base_path('src/App/Entity'));
            $isDevMode = true;

            $dbParams = array(
                'driver'   => 'pdo_mysql',
                'user'     => 'root',
                'password' => '',
                'dbname'   => 'eshop',
                'host' => '127.0.0.1'
            );

            AnnotationRegistry::registerLoader('class_exists');

            $config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode, null, null, false);

            $em = EntityManager::create($dbParams, $config);

            // Log doctrine queries in debug mode
            if( $this->app->config->get('app.debug') ) {
                $debugStack = new DebugStack();
                $em->getConnection()->getConfiguration()->setSQLLogger($debugStack);
                $debugbar = $this->app['debugbar'];
                $debugbar->addCollector(new DoctrineCollector($debugStack));
            }

            return $em;
        });
    }

}