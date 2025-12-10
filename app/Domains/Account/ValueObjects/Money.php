<?php

namespace App\Domains\Account\ValueObjects;


use InvalidArgumentException;

class Money
{
    public function __construct(
        private float $amount,
        private string $currency = 'USD',
    ) {
        if ($amount < 0) {
            throw new InvalidArgumentException('Money amount cannot be negative.');
        }
    }

    public static function fromString(string $value, string $currency = 'USD'): self
    {
        return new self((float) $value, $currency);
    }

    public static function zero(string $currency = 'USD'): self
    {
        return new self(0.0, $currency);
    }

    public function amount(): float
    {
        return $this->amount;
    }

    public function currency(): string
    {
        return $this->currency;
    }

    public function add(Money $other): self
    {
        $this->assertSameCurrency($other);

        return new self($this->amount + $other->amount, $this->currency);
    }

    public function subtract(Money $other): self
    {
        $this->assertSameCurrency($other);

        $result = $this->amount - $other->amount;

        if ($result < 0) {
            throw new InvalidArgumentException('Resulting money cannot be negative.');
        }

        return new self($result, $this->currency);
    }

    public function isZero(): bool
    {
        return $this->amount === 0.0;
    }

    protected function assertSameCurrency(Money $other): void
    {
        if ($this->currency !== $other->currency) {
            throw new InvalidArgumentException('Currencies must match.');
        }
    }
}
