<?php

namespace ATM\Core;

use ATM\Interfaces\ATMInterface;

/**
 * Class ATM
 */
class ATM implements ATMInterface
{

    CONST PARS = [10, 20, 50, 100, 200, 500];
    CONST MAX_VAULT_LIFETIME = 120;
    CONST MAX_AMOUNT = 15000;
    CONST MAX_BILLS = 200;
    CONST VAULT = './data/vault.json';
    CONST VAULT_FOLDER = './data';

    /**
     * @param int $cashAmount
     * @return int
     */
    public static function withdrawCash(int $cashAmount = 0): int
    {
        self::checkVault();

        if (self::_checkWithdrawalAmount($cashAmount)) {
            return self::isWithdrawal($cashAmount);
        }

        return -1;
    }

    /**
     * @return ATMStatus
     */
    public static function getATMStatus(): ATMStatus
    {
        $atmStatus = new ATMStatus();

        if (!is_dir(self::VAULT_FOLDER)) {
            mkdir(self::VAULT_FOLDER, 0755, true);
        }

        $pars = self::getVault('billable');

        if (!count(array_filter($pars))) {
            $atmStatus->set('isWorking', false);
            $atmStatus->set('enoughCash', false);
        }

        return $atmStatus;
    }

    private static function _checkWithdrawalAmount($cashAmount = null)
    {
        return in_array($cashAmount, range(1, self::MAX_AMOUNT));
    }

    private static function isWithdrawal($amount = 0)
    {
        $pars = array_reverse(array_filter(self::getVault()), true);

        foreach ($pars['billable'] as $par => $billsAmount) {
            $bills = floor($amount / $par);

            $billsToWithdraw = $bills > $billsAmount ? $billsAmount : $bills;

            $amount = $amount - ($billsToWithdraw * $par);
            $pars['billable'][$par] = $pars['billable'][$par] - $billsToWithdraw;
        }

        $pars['time'] = time();

        if (!$amount) {
            self::setVault($pars);
            return 1;
        }

        return -1;
    }

    private static function checkVault()
    {
        $vaultTime = self::getVault('time');

        if ((abs($vaultTime - time())) > self::MAX_VAULT_LIFETIME) {
            self::setVault(self::generateVault());
        }
    }

    /**
     * @return array
     */
    private static function generateVault(): array
    {
        $pars = [];
        $bills = 0;

        foreach (array_reverse(self::PARS) as $par) {
            $billsToAdd = $bills < self::MAX_BILLS ? rand(1, self::MAX_BILLS - $bills) : 0;
            $bills += $billsToAdd;

            $pars['billable'][$par] = $billsToAdd;
        }

        $pars['time'] = time();
        return $pars;
    }

    /**
     * @param bool $key
     * @return array|int|mixed
     */
    private static function getVault($key = false)
    {

        if (is_file(self::VAULT)) {
            $vault = json_decode(file_get_contents(self::VAULT), 1);
            return $key ? $vault[$key] : $vault;
        }

        $vault = self::generateVault();

        if (self::setVault($vault)) {
            return $key ? $vault[$key] : $vault;
        }

        return -1;
    }

    /**
     * @param array $vault
     * @return int
     */
    private static function setVault($vault = []): int
    {
        return file_put_contents(self::VAULT, json_encode($vault));
    }

}