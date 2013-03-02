<?php

/**
 * This file is part of the DVO package.
 *
 * (c) Bobby DeVeaux <me@bobbyjason.co.uk> / t: @bobbyjason
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace DVO\Entity;

/**
 * Abstract Entity.
 *
 * @package default
 * @author
 **/
abstract class EntityAbstract
{
    protected $data;
    /**
     * Magic function to capture getters & setters.
     *
     * @param string $name      The name of the function.
     * @param array  $arguments An array of arguments.
     *
     * @return mixed
     */
    public function __call($name, array $arguments)
    {
        $type     = substr($name, 0, 3);
        $variable = strtolower(substr($name, 3));
        switch ($type) {
            case 'get':
                return $this->$variable;
                break;
            case 'set':
                $this->$variable = $arguments[0];
                break;
            default:
                return $this->invalid($type);
                break;
        }
    }

    /**
     * Get the data.
     *
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Magic function to capture getters.
     *
     * @param string $name Name of the variable.
     *
     * @return mixed
     */
    public function __get($name)
    {
        if (true === array_key_exists($name, $this->data)) {
            return $this->data[$name];
        } else {
            throw new Exception('Param ' . $name . ' not found in ' . get_called_class());
        }
    }

    /**
     * Magic function to capture setters.
     *
     * @param string $name  The name of the var.
     * @param string $value The value for the var.
     *
     * @return mixed
     */
    public function __set($name, $value)
    {
        if (true === array_key_exists($name, $this->data)) {
            $this->data[$name] = $value;
        } else {
            throw new Exception('Param ' . $name . ' not found in ' . get_called_class());
        }
    }

    /**
     * Called when invalid function is called.
     *
     * @param string $type The requested method.
     *
     * @throws Exception Throws an exception.
     * @return void
     */
    public function invalid($type)
    {
        throw new Exception('Error: Invalid handler in ' . get_called_class());
    }
}
