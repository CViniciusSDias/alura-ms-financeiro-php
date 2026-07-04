<?php

namespace Alura\Financeiro\Client\App\EnrollClient;

use Alura\Financeiro\Client\Domain\Card\CardExpirationDate;
use Alura\Financeiro\Client\Domain\Card\CardInformation;
use Alura\Financeiro\Client\Domain\Card\CardNumber;
use Alura\Financeiro\Client\Domain\Card\OwnerFullName;
use Alura\Financeiro\Client\Domain\Card\SecurityCode;
use Alura\Financeiro\Client\Domain\Document;
use Alura\Financeiro\Client\Domain\Email;
use Alura\Financeiro\Shared\App\Scheduler;

class EnrollClient
{
    public function __construct(
        private Scheduler $taskScheduler
    ) {
    }

    public function __invoke(EnrollClientInputData $data): void
    {
        new Document($data->clientDocument);
        new CardInformation(
            new OwnerFullName($data->cardOwnerFullName),
            new CardNumber($data->cardNumber),
            new CardExpirationDate($data->cardExpirationMonth, $data->cardExpirationYear),
            new SecurityCode($data->cardSecurityCode),
        );
        new Email($data->email);

        $this->taskScheduler->schedule('process_payment', $data);
    }
}
