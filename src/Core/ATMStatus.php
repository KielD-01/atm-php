<?php
namespace ATM\Core;

/**
 * Class ATMStatus
 *
 * @property bool isWorking
 * @property bool enoughCash
 *
 */
class ATMStatus
{

    public $isWorking = true;
    public $enoughCash = true;

    public function __construct()
    {

    }

    /**
     * @param null $variable
     * @param null $value
     * @return bool
     */
    public function set($variable = null, $value = null): bool
    {

        if (!$variable) {
            return false;
        }

        $this->{$variable} = $value;
        return true;
    }

}