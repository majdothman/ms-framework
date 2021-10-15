<?php

namespace MS\Core\Bootstrap;

use MS\Core\Controller\CoreException;

class Route
{
    protected static $instance = null;

    /**
     * Get instance of this Controller
     *
     * @return Route|null
     */
    public static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * get Controller, Action, ...etc From REQUEST
     *
     * @param $request
     * @return array|null
     */
    public static function parse(string $request)
    {
        try {
            if ($request) {
                $request = trim($request);
                $explode_url = explode('/', $request);
                $request = null;

                if (isset($explode_url[REQUEST_CONTROLLER_IN])) {
                    $request["controller"] = $explode_url[REQUEST_CONTROLLER_IN];
                }
                if (isset($explode_url[REQUEST_ACTION_IN])) {
                    $request["action"] = $explode_url[REQUEST_ACTION_IN];
                }

                return $request;
            } else {
                return null;
            }
        } catch (\Exception $exception) {
            CoreException::writeError("Route", $exception->getMessage(), "1540465678", __FILE__, __LINE__);

            return null;
        }
    }
}
