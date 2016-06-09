<?php

namespace tests\PHPixme;

use PHPixme\RootTypeTrait as subjectTrait;
use tests\PHPixme\RootTypeStub as subjectStub;
use PHPixme\exception\InvalidReturnException as invalidReturn;

class RootTypeTraitTest extends \PHPUnit_Framework_TestCase
{
  public function test_sanity()
  {
    // Test the Object itself that it is using the trait
    $traits = (new \ReflectionClass(subjectStub::class))->getTraitNames();
    self::assertContains(subjectTrait::class, $traits);
  }

  public function test_assertType()
  {
    $stub = new subjectStub();
    self::assertSame($stub, subjectStub::assertRootType($stub));
  }

  public function test_assertType_contract()
  {
    $this->expectException(invalidReturn::class);
    subjectStub::assertType(null);
  }

  public function test_assertRootType()
  {
    $stub = new subjectStub();
    self::assertTrue((new \ReflectionClass(subjectStub::class))->getMethod('assertRootType')->isFinal());
    self::assertSame($stub, subjectStub::assertRootType($stub));
  }

  public function test_assertRootType_contract()
  {
    $this->expectException(invalidReturn::class);
    subjectStub::assertType(null);
  }

  public function test_rootType() {
    self::assertTrue((new \ReflectionClass(subjectStub::class))->getMethod('rootType')->isFinal());
    self::assertSame(subjectStub::class, subjectStub::rootType());
  }

}
