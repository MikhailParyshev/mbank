<?php

namespace App;

use App\CurrencyAccount;
use App\Currency;

use LogicException;
use InvalidArgumentException;
use DomainException;

class MulticurrencyAccount
{
    static float $usd_rub = 70;
    static float $eur_rub = 80;
    static float $eur_usd = 1;

    private string $uid;

    private CurrencyAccount $rub;
    private CurrencyAccount $usd;
    private CurrencyAccount $eur;

    private ?Currency $primaryCurrency = null;

    public function __construct()
    {
        $this->uid = generateUID();

        $this->rub = new CurrencyAccount($this->uid, Currency::rub);
        $this->usd = new CurrencyAccount($this->uid, Currency::usd);
        $this->eur = new CurrencyAccount($this->uid, Currency::eur);
    }

    public function addCurrency(Currency $currency): void
    {
        $this->{$currency->name}->activate();
    }

    public function disableCurrency(Currency $currency): void
    {
        if ($this->primaryCurrency === $currency)
            throw new LogicException(E_DISABLE_PRIMARY_CURRENCY);

        $this->{$currency->name}->disable();
    }

    public function setPrimaryCurrency(Currency $currency): void
    {
        if (!$this->{$currency->name}->isActive())
            throw new LogicException(E_SET_INACTIVE_CURRENCY_PRIMARY);

        $this->primaryCurrency = $currency;
    }

    public function getPrimaryCurrency(): ?Currency
    {
        if ($this->primaryCurrency)
            return $this->primaryCurrency;
        
        return null;
    }

    public function isSetPrimaryCurrency(): bool
    {
        if($this->primaryCurrency)
            return true;

        return false;
    }

    public function getAvailableCurrencies(): array
    {
        $availableCurrencies = [];

        foreach ([
            $this->rub,
            $this->usd,
            $this->eur]
        as $currency)
            {
                if ($currency->isActive())
                    $availableCurrencies[] = $currency->getCurrency();
            }

        return $availableCurrencies;
    }

    public function convert(Currency $from, Currency $to, int $sum = null): void
    {
        if (!$this->{$from->name}->isActive())
            throw new LogicException(E_CONVERT_FROM_INACTIVE);

        if (!$this->{$to->name}->isActive())
            throw new LogicException(E_CONVERT_TO_INACTIVE);

        if ($from === $to)
            throw new InvalidArgumentException(E_CONVERT_TO_SELF);
            
        if ($sum && $sum <= 0)
            throw new DomainException(E_CONVERT_NON_POSITIVE);

        if(!$sum)
            $sum = $this->{$from->name}->sum();

        $money = $this->withdraw($sum, $from);
        $convertedSum = $this->countConvertedSum($money, $from, $to);
        $this->deposit($convertedSum, $to);
    }

    public function deposit(float $sum, Currency $currency): void
    {
        $this->{$currency->name}->add($sum);
    }

    public function withdraw(int $sum, Currency $currency): int
    {
        return $this->{$currency->name}->take($sum);
    }

    public function setExchangeRate(float $newRate, Currency $from, Currency $to): void
    {
        if ($newRate == 0)
            throw new DomainException(E_SET_ZERO_EXCHANGE_RATE);

        if ($newRate < 0)
            throw new DomainException(E_SET_NEGATIVE_EXCHANGE_RATE);

        if ($from === $to)
            throw new InvalidArgumentException(E_SET_SELF_EXCHANGE_RATE);

        if ($rate = $this->rateExists($from, $to))
            static::$$rate = $newRate;

        else static::${$this->rateExists($to, $from)} = 1 / $newRate;
    }

    public function getExchangeRate(Currency $from, Currency $to): float
    {
        if ($from === $to)
            throw new InvalidArgumentException(E_GET_SELF_EXCHANGE_RATE);

        if ($rate = $this->rateExists($from, $to))
            return static::$$rate;

        return 1 / static::${$this->rateExists($to, $from)};
    }

    private function rateExists(Currency $from, Currency $to): ?string
    {
        if (property_exists($this, $rate = $from->name.'_'.$to->name))
            return $rate;

        return null;
    }

    public function balance(Currency $currency = null): int
    {
        if ($currency)
            return $this->{$currency->name}->sum();

        if (!$this->primaryCurrency)
            throw new LogicException(E_TOTAL_BALANCE_WITH_INACTIVE_PRIMARY_CURRENCY);

        return $this->{$this->primaryCurrency->name}->sum() + $this->getSecondaryCurrenciesSum();
    }

    private function getSecondaryCurrenciesSum(): int
    {
        $sum = 0;

        foreach ([$this->rub, $this->usd, $this->eur] as $currencyAccount)
        {
            if ($currencyAccount->isActive() && !$this->isPrimary($currencyAccount->getCurrency()))
            {
                $sum += $this->countConvertedSum(
                    $currencyAccount->sum(),
                    $currencyAccount->getCurrency(),
                    $this->primaryCurrency
                );
            }
        }

        return $sum;
    }

    private function countConvertedSum(int $sum, Currency $from, Currency $to): int
    {
        return intval($sum * $this->getExchangeRate($from, $to));
    }

    private function isPrimary(Currency $currency): bool
    {
        return $this->primaryCurrency === $currency;
    }
}