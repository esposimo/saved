<?php

namespace smn\lazyc\dbc\test;


class MyExtension implements \PHPUnit\Runner\BeforeTestHook, \PHPUnit\Runner\AfterTestHook
{

    /**
     * @inheritDoc
     */
    public function executeAfterTest(string $test, float $time): void
    {
        //printf('%s msecs\n', $time);
    }

    public function executeBeforeTest(string $test): void
    {
        //printf('Testing %s ', $test);
    }
}