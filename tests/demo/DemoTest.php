<?php

use PHPUnit\Framework\TestCase;
use App\MulticurrencyAccount;

class DemoTest extends TestCase
{
    public function testMultyCurrencyAccountCreated()
    {
        $account = new MulticurrencyAccount;
        $this->assertInstanceOf(MulticurrencyAccount::class, $account);
    }
}