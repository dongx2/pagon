<?php

/**
 * Classic route
 *
 * It's classic "controller/action" path mapping
 *
 * You can set route
 *
 *  $app->all('man/:action', 'ManController')
 *
 * Then add method to your 'ManController'
 *
 *  class ManController extend \Pagon\Route\Classic {
 *      function actionLogin() {
 *          // to do some thing
 *      }
 *  }
 *
 * Then you can visit
 *
 *  GET http://domain/man/login
 *
 */

namespace Pagon\Route;

class Classic extends \Pagon\Route
{
    protected $params;

    public function run()
    {
        $action = $this->input->param('action');

        $this->params = $this->input->params;

        if (!$action) {
            throw new \RuntimeException('Route need ":action" param');
        }

        $method = 'action' . $action;

        // Check method
        if (method_exists($this, $method)) {
            $this->$method($this->input, $this->output);
        } elseif ($this->next) {
            $this->next();
        } else {
            $this->app->handleError('404');
        }
    }
}