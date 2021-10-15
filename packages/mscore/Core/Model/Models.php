<?php

namespace MS\Core\Model;

/**
 * Parent Class of Model
 * Class Models
 *
 * @package MS\Model
 */
class Models
{

    public function getObjectVar()
    {
        return get_object_vars($this);
    }
}
