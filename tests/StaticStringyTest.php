<?php

use PHPUnit\Framework\TestCase;
use Stringy\StaticStringy as S;

class StaticStringyTestCase extends TestCase
{
    public function testBadMethodCall()
    {
        $this->expectException(BadMethodCallException::class);
        $result = S::invalidMethod('foo');
    }

    public function testEmptyArgsInvocation()
    {
        $result = S::toLowerCase();
        $this->assertEquals('', $result);
    }

    public function testInvocation()
    {
        $result = S::toLowerCase('FOOBAR');
        $this->assertEquals('foobar', $result);
        $this->assertIsString($result);
    }

    public function testPartialArgsInvocation()
    {
        $result = S::slice('foobar', 0, 3);
        $this->assertEquals('foo', $result);
        $this->assertIsString($result);
    }

    public function testFullArgsInvocation()
    {
        $result = S::slice('fòôbàř', 0, 3, 'UTF-8');
        $this->assertEquals('fòô', $result);
        $this->assertIsString($result);
    }

    public function testArrayReturnValue()
    {
        $result = S::lines("a\nb");
        $this->assertEquals(['a', 'b'], $result);
        $this->assertIsArray($result);
        foreach ($result as $val) {
            $this->assertIsString($val);
        }
    }

    /**
     * Use reflection to ensure that all argument numbers are correct. Each
     * static method should accept 2 more arguments than their Stringy
     * equivalent.
     */
    public function testArgumentNumbers()
    {
        $staticStringyClass = new ReflectionClass('Stringy\StaticStringy');
        $stringyClass = new ReflectionClass('Stringy\Stringy');

        // getStaticPropertyValue can't access protected properties
        $properties = $staticStringyClass->getStaticProperties();

        foreach ($properties['methodArgs'] as $method => $expected) {
            $num = $stringyClass->getMethod($method)
                ->getNumberOfParameters() + 2;

            $this->assertEquals($expected, $num,
                'Invalid num args for ' . $method);
        }
    }
}
