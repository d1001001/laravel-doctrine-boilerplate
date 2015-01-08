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

    public function boot()
    {
        $this->view = $this->app->make('view');
        $this->router = $this->app->make('router');
        $this->blade = $this->app->make('blade.compiler');
        $frontPath = app_path('Frontend/');
        $backPath = app_path('Backend/');
//        $this->view->addLocation(__DIR__.'/Frontend/views');

        $this->view->addNamespace('backend', $backPath . 'views');
        $this->view->addNamespace('frontend', $frontPath . 'views');
        require $backPath . 'routes.php';
        require $backPath . 'filters.php';
        require $frontPath . 'routes.php';
        require $frontPath . 'filters.php';
        require app_path('blade_extensions.php');
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