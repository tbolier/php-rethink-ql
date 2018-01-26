<?php


namespace TBolier\RethinkQL\Serializer;


use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\LogicException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use TBolier\RethinkQL\Query\MessageInterface;
use TBolier\RethinkQL\Query\OptionsInterface;

class QueryNormalizer extends AbstractNormalizer
{
    /**
     * {@inheritdoc}
     */
    public function normalize($object, $format = null, array $context = array())
    {
        if ($this->isCircularReference($object, $context)) {
            return $this->handleCircularReference($object);
        }

        if (!$object instanceof \JsonSerializable && !$object instanceof \stdClass) {
            throw new InvalidArgumentException(sprintf('The ' . get_class($object) . ' must implement "%s".', \JsonSerializable::class));
        }

        if (!$this->serializer instanceof NormalizerInterface) {
            throw new LogicException('Cannot normalize object because injected serializer is not a normalizer');
        }

        if ($object instanceof \stdClass) {
            return (array)$object;
        }

        if ($object instanceof OptionsInterface) {
            return (object)$object->jsonSerialize();
        }

        return $this->serializer->normalize($object->jsonSerialize(), $format, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null)
    {
        return \is_object($data) && $format === 'json';
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function denormalize($data, $class, $format = null, array $context = array())
    {
        throw new LogicException(sprintf('Cannot denormalize with "%s".', __CLASS__));
    }
}
