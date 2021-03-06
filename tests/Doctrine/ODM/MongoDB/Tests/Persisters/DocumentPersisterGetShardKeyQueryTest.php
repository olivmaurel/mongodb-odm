<?php

namespace Doctrine\ODM\MongoDB\Tests\Persisters;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Doctrine\ODM\MongoDB\Persisters\DocumentPersister;
use Doctrine\ODM\MongoDB\Tests\BaseTest;

class DocumentPersisterGetShardKeyQueryTest extends BaseTest
{
    public function testGetShardKeyQueryScalars()
    {
        $o = new ShardedByScalars();
        $o->int = 1;
        $o->string = 'hi';
        $o->bool = true;
        $o->float = 1.2;

        /** @var DocumentPersister $persister */
        $persister = $this->uow->getDocumentPersister(get_class($o));

        $method = new \ReflectionMethod($persister, 'getShardKeyQuery');
        $method->setAccessible(true);

        $this->assertSame(
            array('int' => $o->int, 'string' => $o->string, 'bool' => $o->bool, 'float' => $o->float),
            $method->invoke($persister, $o)
        );
    }

    public function testGetShardKeyQueryObjects()
    {
        $o = new ShardedByObjects();
        $o->oid = '54ca2c4c81fec698130041a7';
        $o->bin = 'hi';
        $o->date = new \DateTime();

        /** @var DocumentPersister $persister */
        $persister = $this->uow->getDocumentPersister(get_class($o));

        $method = new \ReflectionMethod($persister, 'getShardKeyQuery');
        $method->setAccessible(true);
        $shardKeyQuery = $method->invoke($persister, $o);

        $this->assertInstanceOf(\MongoDB\BSON\ObjectId::class, $shardKeyQuery['oid']);
        $this->assertSame($o->oid, (string) $shardKeyQuery['oid']);

        $this->assertInstanceOf(\MongoDB\BSON\Binary::class, $shardKeyQuery['bin']);
        $this->assertSame($o->bin, $shardKeyQuery['bin']->getData());

        $this->assertInstanceOf(\MongoDB\BSON\UTCDateTime::class, $shardKeyQuery['date']);
        $this->assertEquals($o->date->getTimestamp(), $shardKeyQuery['date']->toDateTime()->getTimestamp());

        $this->assertSame(
            (int) $o->date->format('v'),
            (int) $shardKeyQuery['date']->toDateTime()->format('v')
        );
    }

    public function testShardById()
    {
        $o = new ShardedById();
        $o->identifier = new \MongoDB\BSON\ObjectId();

        /** @var DocumentPersister $persister */
        $persister = $this->uow->getDocumentPersister(get_class($o));

        $method = new \ReflectionMethod($persister, 'getShardKeyQuery');
        $method->setAccessible(true);
        $shardKeyQuery = $method->invoke($persister, $o);

        $this->assertSame(array('_id' => $o->identifier), $shardKeyQuery);
    }

    public function testShardByReference()
    {
        $o = new ShardedByReferenceOne();

        $userId = new \MongoDB\BSON\ObjectId();
        $o->reference = new \Documents\User();
        $o->reference->setId($userId);

        $this->dm->persist($o->reference);

        /** @var DocumentPersister $persister */
        $persister = $this->uow->getDocumentPersister(get_class($o));

        $method = new \ReflectionMethod($persister, 'getShardKeyQuery');
        $method->setAccessible(true);
        $shardKeyQuery = $method->invoke($persister, $o);

        $this->assertSame(array('reference.$id' => $userId), $shardKeyQuery);
    }
}

/**
 * @ODM\Document
 * @ODM\ShardKey(keys={"int"="asc","string"="asc","bool"="asc","float"="asc"})
 */
class ShardedByScalars
{
    /** @ODM\Id */
    public $id;

    /** @ODM\Field(type="int") */
    public $int;

    /** @ODM\Field(type="string") */
    public $string;

    /** @ODM\Field(type="boolean") */
    public $bool;

    /** @ODM\Field(type="float") */
    public $float;
}

/**
 * @ODM\Document
 * @ODM\ShardKey(keys={"oid"="asc","bin"="asc","date"="asc"})
 */
class ShardedByObjects
{
    /** @ODM\Id */
    public $id;

    /** @ODM\Field(type="object_id") */
    public $oid;

    /** @ODM\Field(type="bin") */
    public $bin;

    /** @ODM\Field(type="date") */
    public $date;
}

/**
 * @ODM\Document
 * @ODM\ShardKey(keys={"_id"="asc"})
 */
class ShardedById
{
    /** @ODM\Id */
    public $identifier;
}

/**
 * @ODM\Document
 * @ODM\ShardKey(keys={"reference"="asc"})
 */
class ShardedByReferenceOne
{
    /** @ODM\Id */
    public $id;

    /** @ODM\ReferenceOne(targetDocument="Documents\User") */
    public $reference;
}
