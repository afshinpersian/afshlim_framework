<?php
/**
 * Created by PhpStorm.
 * User: afshin
 * Date: 11/13/17
 * Time: 3:19 PM
 */

namespace Core\Helpers;

class Url
{
    public function urlFor($name, $params = array())
    {
        return $this->request->getRootUri() . $this->router->urlFor($name, $params);
    }
}