<?php

use PHPUnit\Framework\TestCase;
use App\MulticurrencyAccount;

use App\Currency;

class MultycurrencyAccountCornerCasesTest extends TestCase
{   
    public function testDisablePrimaryCurrencyAccount()
    {
        $account = new MulticurrencyAccount();
        $account->addCurrency(Currency::eur);
        $account->setPrimaryCurrency(Currency::eur);

        $this->expectException(LogicException::class);
        $this->expectExceptionMessage(E_DISABLE_PRIMARY_CURRENCY);

        $account->disableCurrency(Currency::eur);
    }

    public function testSetInactiveCurrencyAccountPrimary()
    {
        $account = new MulticurrencyAccount();

        $this->expectException(LogicException::class);
        $this->expectExceptionMessage(E_SET_INACTIVE_CURRENCY_PRIMARY);

        $account->setPrimaryCurrency(Currency::eur);
    }

    public function testConvertFromInactiveCurrencyAccount()
    {
        $account = new MulticurrencyAccount();

        $this->expectException(LogicException::class);
        $this->expectExceptionMessage(E_CONVERT_FROM_INACTIVE);
        
        $account->convert(Currency::eur, Currency::usd);
    }

    public function testConvertToInactiveCurrencyAccount()
    {
        $account = new MulticurrencyAccount();
        $account->addCurrency(Currency::eur);

        $this->expectException(LogicException::class);
        $this->expectExceptionMessage(E_CONVERT_TO_INACTIVE);
        
        $account->convert(Currency::eur, Currency::usd);
    }

    public function testConvertToTheSameCurrencyAccount()
    {
        $account = new MulticurrencyAccount();
        $account->addCurrency(Currency::eur);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(E_CONVERT_TO_SELF);
        
        $account->convert(Currency::eur, Currency::eur);
    }

    public function testConvertNonPositiveNumber()
    {
        $account = new MulticurrencyAccount();
        $account->addCurrency(Currency::eur);
        $account->addCurrency(Currency::usd);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage(E_CONVERT_NON_POSITIVE);
        
        $account->convert(Currency::eur, Currency::usd, -1000);
    }

    public function testSetZeroExchangeRate()
    {
        $account = new MulticurrencyAccount();

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage(E_SET_ZERO_EXCHANGE_RATE);
        
        $account->setExchangeRate(0, Currency::eur, Currency::usd);
    }

    public function testSetNegativeExchangeRate()
    {
        $account = new MulticurrencyAccount();

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage(E_SET_NEGATIVE_EXCHANGE_RATE);
        
        $account->setExchangeRate(-234.79, Currency::eur, Currency::usd);
    }

    public function testSetExchangeRateToSameCurrency()
    {
        $account = new MulticurrencyAccount();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(E_SET_SELF_EXCHANGE_RATE);
        
        $account->setExchangeRate(250.4536, Currency::eur, Currency::eur);
    }

    public function testGetExchangeRateToSameCurrency()
    {
        $account = new MulticurrencyAccount();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(E_GET_SELF_EXCHANGE_RATE);
        
        $account->getExchangeRate(Currency::usd, Currency::usd);
    }

    public function testGetTotalBalanceWhilePrimaryCurrencyIsInactive()
    {
        $account = new MulticurrencyAccount();

        $this->expectException(LogicException::class);
        $this->expectExceptionMessage(E_TOTAL_BALANCE_WITH_INACTIVE_PRIMARY_CURRENCY);
        
        $account->balance();
    }
}