<?php

class ObjectChainTest extends PHPUnit_Framework_TestCase
{
    protected function buildStdClass()
    {
        return json_decode('{"int":123,"string":"xxx","obj":{"inside":"inside-value"},"array":[1,2,3]}');
    }

    public function testArrayStyle()
    {
        $o = $this->buildStdClass();

        $data = new \Colin\ObjectChain($o);

        $this->assertEquals(123, $data['int']->value());
        $this->assertEquals("xxx", $data['string']->value());
        $this->assertEquals("inside-value", $data['obj']['inside']->value());
        $this->assertEquals(array(1,2,3), $data['array']->value());
        $this->assertNull($data['x']['y']['z']->value());
    }

    public function testObjectStyle()
    {
        $o = $this->buildStdClass();

        $data = new \Colin\ObjectChain($o);

        $this->assertEquals(123, $data->{'int'}->value());
        $this->assertEquals("xxx", $data->{'string'}->value());
        $this->assertEquals("inside-value", $data->{'obj'}->{'inside'}->value());
        $this->assertEquals(array(1,2,3), $data->{'array'}->value());
        $this->assertNull($data->{'x'}->{'y'}->{'z'}->value());
    }

    public function testSelf()
    {
        $o = $this->buildStdClass();

        $data = new \Colin\ObjectChain(new \Colin\ObjectChain($o));

        $this->assertEquals(123, $data->{'int'}->value());
        $this->assertEquals("xxx", $data->{'string'}->value());
        $this->assertEquals("inside-value", $data->{'obj'}->{'inside'}->value());
        $this->assertEquals(array(1,2,3), $data->{'array'}->value());
        $this->assertNull($data->{'x'}->{'y'}->{'z'}->value());
    }

    public function testIterator()
    {
        $o = $this->buildStdClass();

        $data = new Colin\ObjectChain($o);

        foreach ($data as $k => $v) {
            $this->assertEquals($o->{$k}, $v->value());
        }
    }
}
