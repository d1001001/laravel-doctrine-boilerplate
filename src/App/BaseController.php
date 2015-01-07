<?php namespace App;

use Illuminate\Support\Facades\Response;
use Illuminate\Routing\Controller;
use Illuminate\View\Factory as View;

class BaseController extends Controller {

    protected $view;

    protected $response;

    /**
     * Setup the layout used by the controller.
     *
     * @return void
     */
    protected function setupLayout()
    {
        if ( ! is_null($this->layout))
        {
            $this->layout = View::make($this->layout);
        }
    }

    public function setView( View $view )
    {
        $this->view = $view;
    }

    public function setResponse(Response $response)
    {
        $this->response = $response;
    }
}