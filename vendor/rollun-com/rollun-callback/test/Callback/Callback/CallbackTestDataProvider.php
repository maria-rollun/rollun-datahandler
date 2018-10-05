<?php

namespace rollun\test\callback\Callback;

require_once './src/Callback/src/Callback/Example/CallMe.php';

use rollun\callback\Callback\Callback;
use rollun\callback\Callback\Example\CallMe;
use rollun\callback\Callback\Interruptor\Http;
use rollun\callback\Callback\Interruptor\Process;
use rollun\callback\Callback\Promiser;
use rollun\dic\InsideConstruct;
use rollun\installer\Command;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2016-09-20 at 12:54:48.
 */
class CallbackTestDataProvider extends \PHPUnit_Framework_TestCase
{

    public function provider_mainType()
    {
        $stdObject = (object)['prop' => 'Hello '];

        //function
        return array(
            [
                'class_parents',
                self::class,
                [
                    'PHPUnit_Framework_TestCase' => "PHPUnit_Framework_TestCase",
                    'PHPUnit_Framework_Assert' => "PHPUnit_Framework_Assert"
                ]
            ],
            //closure
            [
                function ($val) {
                    return 'Hello ' . $val;
                },
                'World',
                'Hello World'
            ],
            //closure with uses
            [
                function ($val) use ($stdObject) {
                    return $stdObject->prop . $val;
                },
                'World',
                'Hello World'
            ],
            //invokable object
            [
                new CallMe(),
                'World',
                'Hello World'
            ],
            //method
            [
                [new CallMe(), 'method'],
                'World',
                'Hello World'
            ],
            //static method
            [
                [new CallMe(), 'staticMethod'],
                'World',
                'Hello World'
            ],
            [
                [CallMe::class, 'staticMethod'],
                'World',
                'Hello World'
            ],
            [
                '\\' . CallMe::class . '::staticMethod',
                'World',
                'Hello World'
            ],
        );
    }

    public function provider_multiplexerType()
    {
        $stdObject = (object)['prop' => 'Hello '];
        //function
        return array(
            [
                [
                    new Process(function ($val) {
                        return 'Hello ' . $val;
                    }),
                    new Process(function ($val) use ($stdObject) {
                        return $stdObject->prop . $val;
                    }),
                    new Process(new CallMe()),
                    new Process([new CallMe(), 'method']),
                    new Process([new CallMe(), 'staticMethod']),
                    new Process([CallMe::class, 'staticMethod']),
                    new Process('\\' . CallMe::class . '::staticMethod')
                ],
                "World"
            ],
            [
                [
                    new Process(function ($val) {
                        return 'Hello ' . $val;
                    }),
                    new Process(function ($val) use ($stdObject) {
                        throw new \Exception("some error");
                    }),
                    new Process(new CallMe()),
                    new Process([new CallMe(), 'method']),
                    new Process('\\' . CallMe::class . '::staticMethod')
                ],
                "World"
            ],
        );
    }

    public function providerTickerType()
    {
        return [
            /*
             * [
             *     $tickerCallback,
             *     $ticksCount,
             *     $tickDuration,
             *     $delayMC,
             * ]
             */
            [
                function ($val) {
                    file_put_contents($val, microtime(true) . "\n", FILE_APPEND);
                },
                10,
                1,
                0
            ],
            [
                function ($val) {
                    file_put_contents($val, microtime(true) . "\n", FILE_APPEND);
                },
                5,
                2,
                0
            ],
            [
                function ($val) {
                    file_put_contents($val, microtime(true) . "\n", FILE_APPEND);
                },
                2,
                1,
                3
            ],
            [
                function ($val) {
                    file_put_contents($val, microtime(true) . "\n", FILE_APPEND);
                },
                2,
                3,
                2
            ]

        ];
    }
}
