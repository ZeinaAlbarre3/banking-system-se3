<?php

use App\Domains\Account\Data\InterestData;
use App\Domains\Account\Models\Account;
use App\Domains\Account\Strategy\CurrentInterestStrategy;
use App\Domains\Account\Strategy\InvestmentInterestStrategy;
use App\Domains\Account\Strategy\LoanInterestStrategy;
use App\Domains\Account\Strategy\SavingInterestStrategy;


it('saving strategy calculates interest correctly', function () {
    $account = new Account(['balance' => 1000]);

    $ctx = new InterestData(
        days: 30,
        market_rate: 0.05
    );

    $strategy = new SavingInterestStrategy();
    $interest = $strategy->calculate($account, $ctx);

    $expected = 1000 * 0.05 * (30 / 365);

    expect($interest)->toBeFloat()
        ->and($interest)->toEqualWithDelta($expected, 0.0001);
});

it('investment strategy adds bonus to market rate', function () {
    $account = new Account(['balance' => 2000]);

    $ctx = new InterestData(
        days: 30,
        market_rate: 0.05
    );

    $strategy = new InvestmentInterestStrategy();
    $interest = $strategy->calculate($account, $ctx);

    $expectedRate = 0.05 + 0.01;
    $expected = 2000 * $expectedRate * (30 / 365);

    expect($interest)->toEqualWithDelta($expected, 0.0001);
});

it('loan strategy returns negative interest (cost) if you designed it so', function () {
    $account = new Account(['balance' => 5000]);

    $ctx = new InterestData(days: 30, market_rate: 0.05);

    $strategy = new LoanInterestStrategy();
    $interest = $strategy->calculate($account, $ctx);

    expect($interest)->toBeFloat();
});

it('current strategy might return zero or low interest depending on your logic', function () {
    $account = new Account(['balance' => 1000]);

    $ctx = new InterestData(days: 30, market_rate: 0.05);

    $strategy = new CurrentInterestStrategy();
    $interest = $strategy->calculate($account, $ctx);

    expect($interest)->toBeFloat();
});
