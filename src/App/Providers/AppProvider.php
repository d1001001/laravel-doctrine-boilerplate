<?php namespace App\Providers;

use DebugBar\Bridge\DoctrineCollector;
use Illuminate\Support\ServiceProvider;

class AppProvider extends ServiceProvider {

    private $controllerServices = [
        'View' => 'Illuminate\View\Factory',
        'Response' => 'Illuminate\Support\Facades\Response',
    ];

    private $composers = [
        'App\LayoutComposer' => 'layout',
    ];

    private $controllers = [
        'BaseController' => '',
        'HomeController' => '',
    ];

    public function register()
    {
        $this->setupControllers();

        $this->setupViews();
    }

    private function setupControllers()
    {
        $services = $this->controllerServices;
        $this->app->resolvingAny(function ($object) use ($services) {
            if (!is_string($object) && isset($this->controllers[get_class($object)]) ) {
                foreach ($services as $service => $class) {
                    call_user_func([$object, 'set' . $service], $this->app->make($class));
                }
            }
        });
    }

    private function setupViews()
    {
        $this->app->view->composers($this->composers);
    }

}