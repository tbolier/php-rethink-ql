<?php
declare(strict_types=1);

namespace TBolier\RethinkQL\UnitTest\Serializer;

use Mockery;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Serializer;
use TBolier\RethinkQL\Query\Options;
use TBolier\RethinkQL\Serializer\QueryNormalizer;

class QueryNormalizerTest extends TestCase
{
    /**
     * @var QueryNormalizer
     */
    private $normalizer;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->normalizer = new QueryNormalizer();

        $serializer = new Serializer([$this->normalizer]);

        $this->normalizer->setSerializer($serializer);

    }

    /**
     * @return void
     * @throws \Exception
     */
    public function testNormalizeWithStdClass(): void
    {
        $object = new \stdClass();
        $object->foo = 'bar';

        $data = $this->normalizer->normalize($object);

        $this->assertEquals(['foo' => 'bar'], $data);
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function testNormalizeWithOptions(): void
    {
        $object = new Options();
        $object->setDb('foobar');

        $expectedObject = new \stdClass();
        $expectedObject->db = [0 => 14, 1 => ['foobar']];

        $data = $this->normalizer->normalize($object);

        $this->assertEquals($expectedObject, $data);
    }

    /**
     * @expectedException \Symfony\Component\Serializer\Exception\CircularReferenceException
     * @expectedExceptionMessage A circular reference has been detected when serializing the object of class "stdClass" (configured limit: 1)
     * @return void
     */
    public function testNormalizeWithCircularReference(): void
    {
        $object = new \stdClass();
        $object->foo = 'bar';

        $context = [
            'circular_reference_limit' => [
                spl_object_hash($object) => 1
            ]
        ];

        $this->normalizer->normalize($object, null, $context);
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function testNormalizeWithJsonSerializable(): void
    {
        $expectedReturn = ['foo' => 'bar'];

        $object = Mockery::mock('\JsonSerializable');
        $object->shouldReceive('jsonSerialize')->andReturn($expectedReturn);


        $data = $this->normalizer->normalize($object);

        $this->assertEquals($expectedReturn, $data);
    }
    
    /**
     * @expectedException \Symfony\Component\Serializer\Exception\InvalidArgumentException
     * @expectedExceptionMessage The ArrayObject must implement "JsonSerializable"
     * @return void
     */
    public function testInvalidArgumentExceptionThrownOnInvalidClass(): void
    {
        $object = new \ArrayObject();

        $this->normalizer->normalize($object);
    }

    /**
     * @expectedException \Symfony\Component\Serializer\Exception\LogicException
     * @expectedExceptionMessage Cannot normalize object because injected serializer is not a normalizer
     * @return void
     */
    public function testLogicExceptionThrownOnInvalidNormalizer(): void
    {
        $object = new \stdClass();
        $object->foo = 'bar';

        $serializerMock = Mockery::mock('\Symfony\Component\Serializer\SerializerInterface');
        $this->normalizer->setSerializer($serializerMock);

        $this->normalizer->normalize($object);
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function testSupportsDenormalizationReturnsFalse(): void
    {
        $this->assertFalse($this->normalizer->supportsDenormalization('foo', 'foo', 'foo'));
    }

    /**
     * @expectedException \Symfony\Component\Serializer\Exception\LogicException
     * @expectedExceptionMessage Cannot denormalize with "TBolier\RethinkQL\Serializer\QueryNormalizer".
     * @return void
     */
    public function testIfDenormalizeThrowsLogicException(): void
    {
        $this->normalizer->denormalize('foo', 'bar');
    }
}
