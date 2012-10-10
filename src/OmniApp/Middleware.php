<?php

namespace OmniApp;

class Middleware
{
    const _CLASS_ = __CLASS__;

    /**
     * @var Middleware
     */
    protected $next;

    /**
     * @var \Closure middleware
     */
    private $_origin;

    /**
     * @var Http\Request|Cli\Input
     */
    protected $request;

    /**
     * @var Http\Response|Cli\Output
     */
    protected $response;

    /**
     * @param callable $middleware
     */
    public function __construct(\Closure $middleware = null)
    {
        if ($middleware) $this->_origin = $middleware;
    }

    /**
     *  Default call
     *
     * @return bool
     */
    public function call()
    {
        if ($this->_origin) {
            $self = $this;
            call_user_func_array($this->_origin, array($this->request, $this->response, function () use ($self) {
                $self->getNext()->call();
            }));
            return true;
        }
        return false;
    }

    /**
     * Set next middleware
     *
     * @param Middleware               $middleware
     * @param App                      $app
     */
    final public function setNext(Middleware $middleware, App $app)
    {
        $this->next = $middleware;
        $this->request = $app->request;
        $this->response = $app->response;
    }

    /**
     * Get next middleware
     *
     * @return Middleware
     */
    final public function getNext()
    {
        return $this->next;
    }

    /**
     * Call next
     */
    final protected function next()
    {
        $this->next->call();
    }
}
