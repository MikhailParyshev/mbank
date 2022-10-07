<?php

use PHPUnit\Framework\TestCase;
use App\MulticurrencyAccount;

use App\Currency;

class CurrencyAccountCornerCasesTest extends TestCase
{   
    public function testGetSumFromInactiveCurrencyAccount()
    {
        $account = new MulticurrencyAccount();

        $this->expectException(LogicException::class);
        $this->expectExceptionMessage(E_GET_SUM_FROM_INACTIVE);

        $money = $account->balance(Currency::rub);
    }

    public function testPutMoneyOnInactiveCurrencyAccount()
    {
        $account = new MulticurrencyAccount();

        $this->expectException(LogicException::class);
        $this->expectExceptionMessage(E_DEPOSIT_TO_INACTIVE);

        $account->deposit(2000, Currency::rub);
    }

    public function testPutZeroSumOnCurrencyAccount()
    {
        $account = new MulticurrencyAccount();
        $account->addCurrency(Currency::rub);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage(E_ADD_ZERO);

        $account->deposit(0, Currency::rub);
    }

    public function testPutNegativeSumOnCurrencyAccount()
    {
        $account = new MulticurrencyAccount();
        $account->addCurrency(Currency::rub);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage(E_ADD_NEGATIVE);

        $account->deposit(-2000, Currency::rub);
    }

    public function testWithdrawFromInactiveCurrencyAccount()
    {
        $account = new MulticurrencyAccount();

        $this->expectException(LogicException::class);
        $this->expectExceptionMessage(E_WITHDRAW_FROM_INACTIVE);

        $account->withdraw(2000, Currency::rub);
    }

    public function testWithdrawZeroFromCurrencyAccount()
    {
        $account = new MulticurrencyAccount();
        $account->addCurrency(Currency::rub);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage(E_SUBTRACT_ZERO);

        $account->withdraw(0, Currency::rub);
    }

    public function testWithdrawNegativeNumberFromCurrencyAccount()
    {
        $account = new MulticurrencyAccount();
        $account->addCurrency(Currency::rub);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage(E_SUBTRACT_NEGATIVE);

        $account->withdraw(-3000, Currency::rub);
    }

    public function testWithdrawFromZeroCurrencyAccount()
    {
        $account = new MulticurrencyAccount();
        $account->addCurrency(Currency::rub);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage(E_SUBTRACT_FROM_ZERO);

        $account->withdraw(3000, Currency::rub);
    }

    public function testWithdrawMoreMoneyThanAvailableOnCurrencyAccount()
    {
        $account = new MulticurrencyAccount();
        $account->addCurrency(Currency::rub);
        $account->deposit(2000, Currency::rub);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage(E_SUBTRACT_OVER);

        $account->withdraw(3000, Currency::rub);
    }

    public function testDisableNonZeroCurrencyAccount()
    {
        $account = new MulticurrencyAccount();
        $account->addCurrency(Currency::rub);
        $account->deposit(2000, Currency::rub);

        $this->expectException(LogicException::class);
        $this->expectExceptionMessage(E_DISABLE_NON_ZERO_ACCOUNT);

        $account->disableCurrency(Currency::rub);
    }
}