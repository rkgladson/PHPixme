<?php
/**
 * Created by PhpStorm.
 * User: rgladson
 * Date: 1/7/2016
 * Time: 11:58 AM
 */

namespace PHPixme\tests;
use PHPixme as P;

class AttemptSpec extends \PHPUnit_Framework_TestCase
{
    public function testCompanionReturnsChildren() {
        $this->assertInstanceOf(
            P\Success
            , P\Attempt(function(){})
            , "No thrown values produce a Success"
        );
        $this->assertInstanceOf(
            P\Failure
            , P\Attempt(function (){ throw new \Exception(); })
            , 'Throwing an exception produces a Failure'
        );
    }
}
