<?php

declare(strict_types=1);

namespace App\Payment\Model\Event;

use App\EventStorage\Application\Command\AppendEventCommand;
use App\EventStorage\Model\EventStorage;
use React\ChildProcess\Process;
use React\Promise\PromiseInterface;
use React\Promise\Deferred;

class Stare implements AsyncListenerInterface
{
    public function handle(Deferred $deferred): PromiseInterface
    {
            return $deferred->promise()->then(
                function (object $event){

                    $eventStorage = $this->createEventStorage(
                        $event::class,
                        json_encode($event->getData(), JSON_THROW_ON_ERROR)
                    );

                    return $this->saveEventInChildProcess(eventStorage: $eventStorage);
                }
            )->catch(
                onRejected: function (\Exception $exception){
                    throw $exception;
                }
            );
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

        $serializedData = base64_encode(serialize($eventStorage));

        $cmd = sprintf('bin/console async:save %s', escapeshellarg('echo base64_decode("' . $serializedData . '");'));
        $process = new Process($cmd,null,null);
        $process->start();


        $process->stdout->on('data', function ($data) use (&$stdout) {
           // dd($data);
            $stdout .= $data;
        });

        $process->stderr->on('data', function ($data) use (&$stderr) {
            $stderr .= $data;
        });

        $process->on('exit', function ($exitCode) use ($deferred, &$stdout, &$stderr) {

        //dd($stdout);
        });



        $process->on('error', function (\Exception $e) use ($deferred) {
            $deferred->reject($e);
        });
        $process->emit('sss');
        return $deferred->promise();
    }
}