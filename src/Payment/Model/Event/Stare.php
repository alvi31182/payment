<?php

declare(strict_types=1);

namespace App\Payment\Model\Event;

use App\EventStorage\Application\Command\AppendEventCommand;
use App\EventStorage\Model\EventStorage;
use App\Payment\Model\Event\Handler\EventHandlerInterface;
use React\ChildProcess\Process;
use React\Promise\PromiseInterface;
use React\Promise\Deferred;

#[AsyncEventHandler]
class Stare implements EventHandlerInterface
{
    public function handle(Deferred $deferred): PromiseInterface
    {
        dd($deferred);
    }

    private function createEventStorage(string $eventName, string $eventData): EventStorage
    {
        return EventStorage::appendEvent(
            new AppendEventCommand(eventName: $eventName, eventData: $eventData)
        );
    }

    private function saveEventInChildProcess(EventStorage $eventStorage): PromiseInterface
    {
        $deferred = new Deferred();
        $stdout = ''; // Инициализация переменной для вывода
        $stderr = '';
        $serializedData = base64_encode(serialize($eventStorage));
        $projectRoot = dirname(__DIR__, 4);

        $cmd = sprintf('bin/console ', $projectRoot);

        $process = new Process('bin/console',null,null);
        $process->start();
      //  dd($process);

        $process->stdout->on('data', function ($data) use (&$stdout) {
           // dd($data);
            $stdout .= $data;
        });

        $process->stderr->on('data', function ($data) use (&$stderr) {
            $stderr .= $data;
        });

        $process->stdin->write($serializedData);
        $process->stdin->end();

        $process->on('exit', function ($code, $term) use ($deferred, &$stdout, &$stderr) {

            if ($term === null) {
                echo 'exit with code ' . $code . PHP_EOL;
            } else {
                echo 'terminated with signal ' . $term . PHP_EOL;
            }
        });



        $process->on('error', function (\Exception $e) use ($deferred) {
            $deferred->reject($e);
        });

        $process->stdin->end();
        return $deferred->promise();
    }
}