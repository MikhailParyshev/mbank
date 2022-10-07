<?php

const E_ADD_ZERO = 'Attempt to add 0 to currency account';
const E_ADD_NEGATIVE = 'Attempt to add negative number to currency account';
const E_SUBTRACT_ZERO = 'Attempt to subtract 0 from currency account';
const E_SUBTRACT_NEGATIVE = 'Attempt to subtract a negative number from currency account';
const E_SUBTRACT_FROM_ZERO = 'Attempt to subtract from zero balance currency account';
const E_SUBTRACT_OVER = 'Attempt to subtract more money than is available on the currency account';
const E_DISABLE_NON_ZERO_ACCOUNT = 'Attempt to disable an account with a non-zero balance';
const E_DISABLE_PRIMARY_CURRENCY = 'Attempt to disable the primary currency';
const E_CONVERT_FROM_INACTIVE = 'Attempt to convert money from inactive currency account';
const E_CONVERT_TO_INACTIVE = 'Attempt to convert money to inactive currency account';
const E_CONVERT_TO_SELF = 'Attempt to convert money to the same currency account';
const E_CONVERT_NON_POSITIVE = 'Attempt to convert non-positive sum';
const E_SET_ZERO_EXCHANGE_RATE = 'Attempt to set zero exhange rate';
const E_SET_NEGATIVE_EXCHANGE_RATE = 'Attempt to set negative exhange rate';
const E_SET_SELF_EXCHANGE_RATE = 'Attempt to set exhange rate between same currencies';
const E_GET_SELF_EXCHANGE_RATE = 'Attempt to get exhange rate between same currencies';
const E_TOTAL_BALANCE_WITH_INACTIVE_PRIMARY_CURRENCY = 'Attempt to check total balance while primary currency inactive';
const E_GET_SUM_FROM_INACTIVE = 'Attempt to get sum from inactive currency account';
const E_DEPOSIT_TO_INACTIVE = 'Attempt to deposit to inactive currency account';
const E_WITHDRAW_FROM_INACTIVE = 'Attempt to withdraw from inactive currency account';
const E_SET_INACTIVE_CURRENCY_PRIMARY = 'Attempt to set inactive currency primary';