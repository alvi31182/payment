<?php

declare(strict_types=1);

namespace App\EventStorage\Application\Console;

use App\EventStorage\Model\WriteEventStorage;
use App\Payment\Infrastructure\Persistence\AsyncTransactionProcessor;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'async:save',
    description: 'Save domain event from child process.'
)]
final class SaveEventInStorage extends Command
{
    public function __construct(
        private WriteEventStorage $writeEventStorage,
        private AsyncTransactionProcessor $transactionProcessor,
        private LoggerInterface $logger
    )
    {
    }

    protected function configure(): void
    {

        $this->addArgument(
            name: 'event',
            mode: InputArgument::REQUIRED,
            description: 'Event storage object (entity)'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        dd($input);
        $this->logger->info((string)$input->getArgument('event'));
        dd($input->getArgument('event'));
    }
}