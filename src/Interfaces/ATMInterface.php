<?php
namespace ATM\Interfaces;

use ATM\Core\ATMStatus;

/**
 * Interface ATMInterface
 */
interface ATMInterface
{

    /**
     * @param int $cashAmount
     * @return int
     */
    public static function withdrawCash(int $cashAmount = 0): int;

    /**
     * @return ATMStatus
     */
    public static function getATMStatus(): ATMStatus;
}