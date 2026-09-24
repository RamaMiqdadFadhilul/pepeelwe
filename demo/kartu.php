<?php

interface TapPayment
{
    public function getAccountID(): int;
    public function getBalance(): float;
    public function pay(float $amount): bool;
}

interface EWallet extends TapPayment
{
    public function topUp(float $amount): void;
    public function getSaldoEWallet(): float;
    public function useEWallet(float $amount): bool;
}

interface VisaCard
{
    public function getCardNumber(): string;
    public function getSisaCredit(): float;
    public function charge(float $amount, int $cvv): bool;
}


// =========================
// SILVER CARD
// =========================

class SilverCard implements VisaCard
{
    private string $cardNumber;
    private float $sisaCredit;
    private int $cvv;

    public function __construct(
        string $cardNumber,
        float $sisaCredit,
        int $cvv
    ) {
        $this->cardNumber = $cardNumber;
        $this->sisaCredit = $sisaCredit;
        $this->cvv = $cvv;
    }

    public function getCardNumber(): string
    {
        return $this->cardNumber;
    }

    public function getSisaCredit(): float
    {
        return $this->sisaCredit;
    }

    public function charge(float $amount, int $cvv): bool
    {
        if ($cvv !== $this->cvv) {
            return false;
        }

        if ($amount > $this->sisaCredit) {
            return false;
        }

        $this->sisaCredit -= $amount;

        echo "Charged {$amount} to card {$this->cardNumber}. "
            . "Sisa credit: {$this->sisaCredit}<br>";

        return true;
    }
}


// =========================
// GOLD CARD
// =========================

class GoldCard implements VisaCard, TapPayment, EWallet
{
    private string $cardNumber;
    private float $sisaCredit;
    private int $cvv;

    private int $accountID;
    private float $balance;

    private float $saldoEWallet;

    public function __construct(
        string $cardNumber,
        float $sisaCredit,
        int $cvv,
        int $accountID,
        float $saldoEWallet
    ) {
        $this->cardNumber = $cardNumber;
        $this->sisaCredit = $sisaCredit;
        $this->cvv = $cvv;
        $this->accountID = $accountID;

        // Saldo awal
        $this->balance = $saldoEWallet;
        $this->saldoEWallet = $saldoEWallet;
    }


    // =========================
    // VisaCard
    // =========================

    public function getCardNumber(): string
    {
        return $this->cardNumber;
    }

    public function getSisaCredit(): float
    {
        return $this->sisaCredit;
    }

    public function charge(float $amount, int $cvv): bool
    {
        if ($cvv !== $this->cvv) {
            return false;
        }

        if ($amount > $this->sisaCredit) {
            return false;
        }

        $this->sisaCredit -= $amount;

        echo "Charged {$amount} to card {$this->cardNumber}. "
            . "Sisa credit: {$this->sisaCredit}<br>";

        return true;
    }


    // =========================
    // TapPayment
    // =========================

    public function getAccountID(): int
    {
        return $this->accountID;
    }

    public function getBalance(): float
    {
        return $this->balance;
    }

    public function pay(float $amount): bool
    {
        if ($amount > $this->balance) {
            return false;
        }

        $this->balance -= $amount;

        echo "Paid {$amount} from account {$this->accountID}. "
            . "Sisa balance: {$this->balance}<br>";

        return true;
    }


    // =========================
    // EWallet
    // =========================

    public function topUp(float $amount): void
    {
        $this->saldoEWallet += $amount;

        echo "Top up {$amount} to e-wallet. "
            . "Saldo: {$this->saldoEWallet}<br>";
    }

    public function getSaldoEWallet(): float
    {
        return $this->saldoEWallet;
    }

    public function useEWallet(float $amount): bool
    {
        if ($amount > $this->saldoEWallet) {
            return false;
        }

        $this->saldoEWallet -= $amount;

        echo "Used {$amount} from e-wallet. "
            . "Sisa saldo: {$this->saldoEWallet}<br>";

        return true;
    }
}
