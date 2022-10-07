<?php

use PHPUnit\Framework\TestCase;
use App\MulticurrencyAccount;

use App\Currency;

class DemoTest extends TestCase
{
    public function testMulticurrencyAccountCreated()
    {
        $account = new MulticurrencyAccount;
        $this->assertInstanceOf(MulticurrencyAccount::class, $account);

        return $account;
    }

    /**
     * @depends testMulticurrencyAccountCreated
     */
    public function testNewCurrencyRubAdded(MulticurrencyAccount $account)
    {
        $account->addCurrency(Currency::rub);
        $this->assertSame($account->getAvailableCurrencies(), [Currency::rub]);

        return $account;
    }

    /**
     * @depends testNewCurrencyRubAdded
     */
    public function testNewCurrencyEurAdded(MulticurrencyAccount $account)
    {
        $account->addCurrency(Currency::eur);
        $this->assertTrue($account->getAvailableCurrencies() === [Currency::rub, Currency::eur]);

        return $account;
    }

    /**
     * @depends testNewCurrencyEurAdded
     */
    public function testNewCurrencyUsdAdded(MulticurrencyAccount $account)
    {
        $account->addCurrency(Currency::usd);
        $this->assertSame($account->getAvailableCurrencies(), [Currency::rub, Currency::usd, Currency::eur]);

        return $account;
    }

    /**
     * @depends testNewCurrencyUsdAdded
     */
    public function testPrimaryCurrencyIsNotSetYet(MulticurrencyAccount $account)
    {
        $this->assertNull($account->getPrimaryCurrency());

        return $account;
    }

    /**
     * @depends testPrimaryCurrencyIsNotSetYet
     */
    public function testSetRubPrimaryCurrency(MulticurrencyAccount $account)
    {
        $account->setPrimaryCurrency(Currency::rub);
        $this->assertSame($account->getPrimaryCurrency(), Currency::rub);

        return $account;
    }

    /**
     * @depends testSetRubPrimaryCurrency
     */
    public function testNowAvailableCurrenciesAreRubUsdAndEur(MulticurrencyAccount $account)
    {
        $this->assertSame($account->getAvailableCurrencies(), [Currency::rub, Currency::usd, Currency::eur]);

        return $account;
    }

    /**
     * @depends testNowAvailableCurrenciesAreRubUsdAndEur
     */
    public function testDeposit1000RublesDone(MulticurrencyAccount $account)
    {
        $account->deposit(100000, Currency::rub);
        $this->assertSame($account->balance(Currency::rub), 100000);

        return $account;
    }

    /**
     * @depends testDeposit1000RublesDone
     */
    public function testDeposit50EurosDone(MulticurrencyAccount $account)
    {
        $account->deposit(5000, Currency::eur);
        $this->assertSame($account->balance(Currency::eur), 5000);

        return $account;
    }

    /**
     * @depends testDeposit50EurosDone
     */
    public function testDeposit50DollarsDone(MulticurrencyAccount $account)
    {
        $account->deposit(5000, Currency::usd);
        $this->assertSame($account->balance(Currency::usd), 5000);

        return $account;
    }

    /**
     * @depends testDeposit50DollarsDone
     */
    public function testBalanceInPrimaryCurrencyCalculatedAndEquals8500Rub(MulticurrencyAccount $account)
    {
        $this->assertSame($account->balance(), 850000);
        $this->assertSame($account->getPrimaryCurrency(), Currency::rub);

        return $account;
    }

    /**
     * @depends testBalanceInPrimaryCurrencyCalculatedAndEquals8500Rub
     */
    public function testBalanceInUsdEquals50Dollars(MulticurrencyAccount $account)
    {
        $this->assertSame($account->balance(Currency::usd), 5000);

        return $account;
    }

    /**
     * @depends testBalanceInUsdEquals50Dollars
     */
    public function testBalanceInEurEquals50Euros(MulticurrencyAccount $account)
    {
        $this->assertSame($account->balance(Currency::eur), 5000);

        return $account;
    }

    /**
     * @depends testBalanceInEurEquals50Euros
     */
    public function testdeposit1000RubDoneAndNowIts2000Rub(MulticurrencyAccount $account)
    {
        $account->deposit(100000, Currency::rub);
        $this->assertSame($account->balance(Currency::rub), 200000);

        return $account;
    }

    /**
     * @depends testdeposit1000RubDoneAndNowIts2000Rub
     */
    public function testdeposit50EurDoneAndNowIts100Eur(MulticurrencyAccount $account)
    {
        $account->deposit(5000, Currency::eur);
        $this->assertSame($account->balance(Currency::eur), 10000);

        return $account;
    }

    /**
     * @depends testdeposit50EurDoneAndNowIts100Eur
     */
    public function testWithdraw10UsdAndNowIts40UsdLeft(MulticurrencyAccount $account)
    {
        $account->withdraw(1000, Currency::usd);
        $this->assertSame($account->balance(Currency::usd), 4000);

        return $account;
    }

    /**
     * @depends testWithdraw10UsdAndNowIts40UsdLeft
     */
    public function testExhangeRateEurRubIsSetTo150(MulticurrencyAccount $account)
    {
        $account->setExchangeRate(150, Currency::eur, Currency::rub);
        $this->assertSame($account->getExchangeRate(Currency::eur, Currency::rub), 150.0);

        return $account;
    }

    /**
     * @depends testExhangeRateEurRubIsSetTo150
     */
    public function testExhangeRateUsdRubIsSetTo100(MulticurrencyAccount $account)
    {
        $account->setExchangeRate(100, Currency::usd, Currency::rub);
        $this->assertSame($account->getExchangeRate(Currency::usd, Currency::rub), 100.0);

        return $account;
    }

    /**
     * @depends testExhangeRateUsdRubIsSetTo100
     */
    public function testBalanceInPrimaryCurrencyCalculatedAndEquals21000Rub(MulticurrencyAccount $account)
    {
        $this->assertSame($account->balance(), 2100000);
        $this->assertSame($account->getPrimaryCurrency(), Currency::rub);

        return $account;
    }

    /**
     * @depends testBalanceInPrimaryCurrencyCalculatedAndEquals21000Rub
     */
    public function testSetPrimaryCurrencyToEur(MulticurrencyAccount $account)
    {
        $account->setPrimaryCurrency(Currency::eur);
        $this->assertSame($account->getPrimaryCurrency(), Currency::eur);

        return $account;
    }

    /**
     * @depends testSetPrimaryCurrencyToEur
     */
    public function testBalanceInPrimaryCurrencyCalculatedAndEquals153EurAnd33Cents(MulticurrencyAccount $account)
    {
        $this->assertSame($account->balance(), 15333);
        $this->assertSame($account->getPrimaryCurrency(), Currency::eur);

        return $account;
    }

    /**
     * @depends testBalanceInPrimaryCurrencyCalculatedAndEquals153EurAnd33Cents
     */
    public function testAll2000RubOfRubBalanceConvertedToEur(MulticurrencyAccount $account)
    {
        $account->convert(Currency::rub, Currency::eur, 200000);
        $this->assertSame($account->balance(Currency::rub), 0);
        $this->assertSame($account->balance(Currency::eur), 11333);

        return $account;
    }

    /**
     * @depends testAll2000RubOfRubBalanceConvertedToEur
     */
    public function testEurBalanceIs113EurAnd33CentsNow(MulticurrencyAccount $account)
    {
        $this->assertSame($account->balance(Currency::eur), 11333);

        return $account;
    }

    /**
     * @depends testEurBalanceIs113EurAnd33CentsNow
     */
    public function testExhangeRateEurRubIsSetTo120(MulticurrencyAccount $account)
    {
        $account->setExchangeRate(120, Currency::eur, Currency::rub);
        $this->assertSame($account->getExchangeRate(Currency::eur, Currency::rub), 120.0);

        return $account;
    }

    /**
     * @depends testExhangeRateEurRubIsSetTo120
     */
    public function testBalanceInEurRemainsTheSameAndEquals153EurAnd33Cents(MulticurrencyAccount $account)
    {
        $this->assertSame($account->balance(), 15333);
        $this->assertSame($account->getPrimaryCurrency(), Currency::eur);

        return $account;
    }

    /**
     * @depends testBalanceInEurRemainsTheSameAndEquals153EurAnd33Cents
     */
    public function testPrimaryCurrencyIsSetToRub(MulticurrencyAccount $account)
    {
        $account->setPrimaryCurrency(Currency::rub);
        $this->assertSame($account->getPrimaryCurrency(), Currency::rub);

        return $account;
    }

    /**
     * @depends testPrimaryCurrencyIsSetToRub
     */
    public function testAllEurConvertedToRub(MulticurrencyAccount $account)
    {
        $account->convert(Currency::eur, Currency::rub);
        $this->assertSame($account->balance(Currency::eur), 0);
        $this->assertSame($account->balance(Currency::rub), 1359960);

        return $account;
    }

    /**
     * @depends testAllEurConvertedToRub
     */
    public function testAllUsdConvertedToRub(MulticurrencyAccount $account)
    {
        $account->convert(Currency::usd, Currency::rub);
        $this->assertSame($account->balance(Currency::usd), 0);
        $this->assertSame($account->balance(Currency::rub), 1759960);

        return $account;
    }

    /**
     * @depends testAllUsdConvertedToRub
     */
    public function testCurrencyEurIsDisabled(MulticurrencyAccount $account)
    {
        $account->disableCurrency(Currency::eur);
        $this->assertSame($account->getAvailableCurrencies(), [Currency::rub, Currency::usd]);

        return $account;
    }

    /**
     * @depends testCurrencyEurIsDisabled
     */
    public function testCurrencyUsdIsDisabled(MulticurrencyAccount $account)
    {
        $account->disableCurrency(Currency::usd);
        $this->assertSame($account->getAvailableCurrencies(), [Currency::rub]);

        return $account;
    }

    /**
     * @depends testCurrencyEurIsDisabled
     */
    public function testRubIsTheOnlyAvailableCurrencyNow(MulticurrencyAccount $account)
    {
        $this->assertSame($account->getAvailableCurrencies(), [Currency::rub]);

        return $account;
    }

    /**
     * @depends testRubIsTheOnlyAvailableCurrencyNow
     */
    public function testFinallyBalanceIs17560Rub(MulticurrencyAccount $account)
    {
        $this->assertSame($account->balance(), 1759960);

        return $account;
    }
}