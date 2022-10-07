<?php

namespace App;

use App\Currency;

use LogicException;
use DomainException;

class CurrencyAccount
{
    private int $sum = 0;
    private bool $active = false;
    private Currency $currency;
    private String $hostMulticurrencyAccountId;

    public function __construct(String $hostMulticurrencyAccountId, Currency $currency)
    {
        $this->currency = $currency;
        $this->hostMulticurrencyAccountId = $hostMulticurrencyAccountId;
    }

    public function sum(): int
    {
        if (!$this->active)
            throw new LogicException(E_GET_SUM_FROM_INACTIVE);

        return $this->sum;
    }

    public function add(int $sum): void
    {
        if (!$this->active)
            throw new LogicException(E_DEPOSIT_TO_INACTIVE);

        if ($sum === 0)
            throw new DomainException(E_ADD_ZERO);

        if ($sum < 0)
            throw new DomainException(E_ADD_NEGATIVE);

        $this->sum += $sum;
    }

    public function take(int $sum): int
    {
        if (!$this->active)
            throw new LogicException(E_WITHDRAW_FROM_INACTIVE);

        if ($sum === 0)
            throw new DomainException(E_SUBTRACT_ZERO);

        if ($sum < 0)
            throw new DomainException(E_SUBTRACT_NEGATIVE);

        if ($this->sum === 0)
            throw new DomainException(E_SUBTRACT_FROM_ZERO);

        if ($this->sum < $sum)
            throw new DomainException(E_SUBTRACT_OVER);

        $this->sum -= $sum;

        return $sum;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function activate(): void
    {
        $this->active = true;
    }

    public function disable(): void
    {
        if ($this->sum > 0)
            throw new LogicException(E_DISABLE_NON_ZERO_ACCOUNT);

        $this->active = false;
    }

    public function getCurrency(): Currency
    {
        return $this->currency;
    }
}