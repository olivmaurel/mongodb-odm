<?php

namespace Doctrine\ODM\MongoDB\Tests\Mapping\Driver;

use Doctrine\ODM\MongoDB\Mapping\ClassMetadata;
use TestDocuments\PrimedCollectionDocument;
use PHPUnit\Framework\TestCase;

require_once 'fixtures/InvalidPartialFilterDocument.php';
require_once 'fixtures/PartialFilterDocument.php';
require_once 'fixtures/PrimedCollectionDocument.php';
require_once 'fixtures/User.php';
require_once 'fixtures/EmbeddedDocument.php';
require_once 'fixtures/QueryResultDocument.php';

abstract class AbstractDriverTest extends TestCase
{
    protected $driver;

    public function setUp()
    {
        // implement driver setup and metadata read
    }

    public function tearDown()
    {
        unset ($this->driver);
    }

    public function testDriver()
    {

        $classMetadata = new ClassMetadata('TestDocuments\User');
        $this->driver->loadMetadataForClass('TestDocuments\User', $classMetadata);

        $this->assertEquals(array(
            'fieldName' => 'id',
            'id' => true,
            'name' => '_id',
            'type' => 'id',
            'isCascadeDetach' => false,
            'isCascadeMerge' => false,
            'isCascadePersist' => false,
            'isCascadeRefresh' => false,
            'isCascadeRemove' => false,
            'isInverseSide' => false,
            'isOwningSide' => true,
            'nullable' => false
        ), $classMetadata->fieldMappings['id']);

        $this->assertEquals(array(
            'fieldName' => 'username',
            'name' => 'username',
            'type' => 'string',
            'isCascadeDetach' => false,
            'isCascadeMerge' => false,
            'isCascadePersist' => false,
            'isCascadeRefresh' => false,
            'isCascadeRemove' => false,
            'isInverseSide' => false,
            'isOwningSide' => true,
            'nullable' => false,
            'unique' => true,
            'sparse' => true,
            'strategy' => ClassMetadata::STORAGE_STRATEGY_SET,
        ), $classMetadata->fieldMappings['username']);

        $this->assertEquals(array(
            array(
                'keys' => array('username' => 1),
                'options' => array('unique' => true, 'sparse' => true)
            )
        ), $classMetadata->getIndexes());

        $this->assertEquals(array(
            'fieldName' => 'createdAt',
            'name' => 'createdAt',
            'type' => 'date',
            'isCascadeDetach' => false,
            'isCascadeMerge' => false,
            'isCascadePersist' => false,
            'isCascadeRefresh' => false,
            'isCascadeRemove' => false,
            'isInverseSide' => false,
            'isOwningSide' => true,
            'nullable' => false,
            'strategy' => ClassMetadata::STORAGE_STRATEGY_SET,
        ), $classMetadata->fieldMappings['createdAt']);

        $this->assertEquals(array(
            'fieldName' => 'tags',
            'name' => 'tags',
            'type' => 'collection',
            'isCascadeDetach' => false,
            'isCascadeMerge' => false,
            'isCascadePersist' => false,
            'isCascadeRefresh' => false,
            'isCascadeRemove' => false,
            'isInverseSide' => false,
            'isOwningSide' => true,
            'nullable' => false,
            'strategy' => ClassMetadata::STORAGE_STRATEGY_SET,
        ), $classMetadata->fieldMappings['tags']);

        $this->assertEquals(array(
            'association' => 3,
            'fieldName' => 'address',
            'name' => 'address',
            'type' => 'one',
            'embedded' => true,
            'targetDocument' => 'Documents\Address',
            'collectionClass' => null,
            'isCascadeDetach' => true,
            'isCascadeMerge' => true,
            'isCascadePersist' => true,
            'isCascadeRefresh' => true,
            'isCascadeRemove' => true,
            'isInverseSide' => false,
            'isOwningSide' => true,
            'nullable' => false,
            'strategy' => ClassMetadata::STORAGE_STRATEGY_SET,
        ), $classMetadata->fieldMappings['address']);

        $this->assertEquals(array(
            'association' => 4,
            'fieldName' => 'phonenumbers',
            'name' => 'phonenumbers',
            'type' => 'many',
            'embedded' => true,
            'targetDocument' => 'Documents\Phonenumber',
            'collectionClass' => null,
            'isCascadeDetach' => true,
            'isCascadeMerge' => true,
            'isCascadePersist' => true,
            'isCascadeRefresh' => true,
            'isCascadeRemove' => true,
            'isInverseSide' => false,
            'isOwningSide' => true,
            'nullable' => false,
            'strategy' => ClassMetadata::STORAGE_STRATEGY_PUSH_ALL,
        ), $classMetadata->fieldMappings['phonenumbers']);

        $this->assertEquals(array(
            'association' => 1,
            'fieldName' => 'profile',
            'name' => 'profile',
            'type' => 'one',
            'reference' => true,
            'storeAs' => ClassMetadata::REFERENCE_STORE_AS_ID,
            'targetDocument' => 'Documents\Profile',
            'collectionClass' => null,
            'cascade' => array('remove', 'persist', 'refresh', 'merge', 'detach'),
            'isCascadeDetach' => true,
            'isCascadeMerge' => true,
            'isCascadePersist' => true,
            'isCascadeRefresh' => true,
            'isCascadeRemove' => true,
            'isInverseSide' => false,
            'isOwningSide' => true,
            'nullable' => false,
            'strategy' => ClassMetadata::STORAGE_STRATEGY_SET,
            'inversedBy' => null,
            'mappedBy' => null,
            'repositoryMethod' => null,
            'limit' => null,
            'skip' => null,
            'orphanRemoval' => true,
            'prime' => [],
        ), $classMetadata->fieldMappings['profile']);

        $this->assertEquals(array(
            'association' => 1,
            'fieldName' => 'account',
            'name' => 'account',
            'type' => 'one',
            'reference' => true,
            'storeAs' => ClassMetadata::REFERENCE_STORE_AS_DB_REF,
            'targetDocument' => 'Documents\Account',
            'collectionClass' => null,
            'cascade' => array('remove', 'persist', 'refresh', 'merge', 'detach'),
            'isCascadeDetach' => true,
            'isCascadeMerge' => true,
            'isCascadePersist' => true,
            'isCascadeRefresh' => true,
            'isCascadeRemove' => true,
            'isInverseSide' => false,
            'isOwningSide' => true,
            'nullable' => false,
            'strategy' => ClassMetadata::STORAGE_STRATEGY_SET,
            'inversedBy' => null,
            'mappedBy' => null,
            'repositoryMethod' => null,
            'limit' => null,
            'skip' => null,
            'orphanRemoval' => false,
            'prime' => [],
        ), $classMetadata->fieldMappings['account']);

        $this->assertEquals(array(
            'association' => 2,
            'fieldName' => 'groups',
            'name' => 'groups',
            'type' => 'many',
            'reference' => true,
            'storeAs' => ClassMetadata::REFERENCE_STORE_AS_DB_REF,
            'targetDocument' => 'Documents\Group',
            'collectionClass' => null,
            'cascade' => array('remove', 'persist', 'refresh', 'merge', 'detach'),
            'isCascadeDetach' => true,
            'isCascadeMerge' => true,
            'isCascadePersist' => true,
            'isCascadeRefresh' => true,
            'isCascadeRemove' => true,
            'isInverseSide' => false,
            'isOwningSide' => true,
            'nullable' => false,
            'strategy' => ClassMetadata::STORAGE_STRATEGY_PUSH_ALL,
            'inversedBy' => null,
            'mappedBy' => null,
            'repositoryMethod' => null,
            'limit' => null,
            'skip' => null,
            'orphanRemoval' => false,
            'prime' => [],
        ), $classMetadata->fieldMappings['groups']);

        $this->assertEquals(
            array(
                'postPersist' => array('doStuffOnPostPersist', 'doOtherStuffOnPostPersist'),
                'prePersist' => array('doStuffOnPrePersist'),
            ),
            $classMetadata->lifecycleCallbacks
        );

        $this->assertEquals(
            array(
                "doStuffOnAlsoLoad" => array("unmappedField"),
            ),
            $classMetadata->alsoLoadMethods
        );

        $classMetadata = new ClassMetadata('TestDocuments\EmbeddedDocument');
        $this->driver->loadMetadataForClass('TestDocuments\EmbeddedDocument', $classMetadata);

        $this->assertEquals(array(
            'fieldName' => 'name',
            'name' => 'name',
            'type' => 'string',
            'isCascadeDetach' => false,
            'isCascadeMerge' => false,
            'isCascadePersist' => false,
            'isCascadeRefresh' => false,
            'isCascadeRemove' => false,
            'isInverseSide' => false,
            'isOwningSide' => true,
            'nullable' => false,
            'strategy' => ClassMetadata::STORAGE_STRATEGY_SET,
        ), $classMetadata->fieldMappings['name']);

        $classMetadata = new ClassMetadata('TestDocuments\QueryResultDocument');
        $this->driver->loadMetadataForClass('TestDocuments\QueryResultDocument', $classMetadata);

        $this->assertEquals(array(
            'fieldName' => 'name',
            'name' => 'name',
            'type' => 'string',
            'isCascadeDetach' => false,
            'isCascadeMerge' => false,
            'isCascadePersist' => false,
            'isCascadeRefresh' => false,
            'isCascadeRemove' => false,
            'isInverseSide' => false,
            'isOwningSide' => true,
            'nullable' => false,
            'strategy' => ClassMetadata::STORAGE_STRATEGY_SET,
        ), $classMetadata->fieldMappings['name']);

        $this->assertEquals(array(
            'fieldName' => 'count',
            'name' => 'count',
            'type' => 'integer',
            'isCascadeDetach' => false,
            'isCascadeMerge' => false,
            'isCascadePersist' => false,
            'isCascadeRefresh' => false,
            'isCascadeRemove' => false,
            'isInverseSide' => false,
            'isOwningSide' => true,
            'nullable' => false,
            'strategy' => ClassMetadata::STORAGE_STRATEGY_SET,
        ), $classMetadata->fieldMappings['count']);
    }

    public function testPartialFilterExpressions()
    {
        $classMetadata = new ClassMetadata('TestDocuments\PartialFilterDocument');
        $this->driver->loadMetadataForClass('TestDocuments\PartialFilterDocument', $classMetadata);

        $this->assertEquals([
            [
                'keys' => ['fieldA' => 1],
                'options' => [
                    'partialFilterExpression' => [
                        'version' => ['$gt' => 1],
                        'discr' => ['$eq' => 'default'],
                    ],
                ],
            ],
            [
                'keys' => ['fieldB' => 1],
                'options' => [
                    'partialFilterExpression' => [
                        '$and' => [
                            ['version' => ['$gt' => 1]],
                            ['discr' => ['$eq' => 'default']],
                        ],
                    ],
                ],
            ],
            [
                'keys' => ['fieldC' => 1],
                'options' => [
                    'partialFilterExpression' => [
                        'embedded' => ['foo' => 'bar'],
                    ],
                ],
            ],
        ], $classMetadata->getIndexes());
    }

    public function testCollectionPrimers()
    {
        $classMetadata = new ClassMetadata(PrimedCollectionDocument::class);
        $this->driver->loadMetadataForClass(PrimedCollectionDocument::class, $classMetadata);

        $this->assertEquals([
            'association' => 2,
            'fieldName' => 'references',
            'name' => 'references',
            'type' => 'many',
            'reference' => true,
            'storeAs' => ClassMetadata::REFERENCE_STORE_AS_DB_REF,
            'targetDocument' => PrimedCollectionDocument::class,
            'collectionClass' => null,
            'cascade' => [],
            'isCascadeDetach' => false,
            'isCascadeMerge' => false,
            'isCascadePersist' => false,
            'isCascadeRefresh' => false,
            'isCascadeRemove' => false,
            'isInverseSide' => false,
            'isOwningSide' => true,
            'nullable' => false,
            'strategy' => ClassMetadata::STORAGE_STRATEGY_PUSH_ALL,
            'inversedBy' => null,
            'mappedBy' => null,
            'repositoryMethod' => null,
            'limit' => null,
            'skip' => null,
            'orphanRemoval' => false,
            'prime' => [],
        ], $classMetadata->fieldMappings['references']);

        $this->assertEquals([
            'association' => 2,
            'fieldName' => 'inverseMappedBy',
            'name' => 'inverseMappedBy',
            'type' => 'many',
            'reference' => true,
            'storeAs' => ClassMetadata::REFERENCE_STORE_AS_DB_REF,
            'targetDocument' => PrimedCollectionDocument::class,
            'collectionClass' => null,
            'cascade' => [],
            'isCascadeDetach' => false,
            'isCascadeMerge' => false,
            'isCascadePersist' => false,
            'isCascadeRefresh' => false,
            'isCascadeRemove' => false,
            'isInverseSide' => true,
            'isOwningSide' => false,
            'nullable' => false,
            'strategy' => ClassMetadata::STORAGE_STRATEGY_PUSH_ALL,
            'inversedBy' => null,
            'mappedBy' => 'references',
            'repositoryMethod' => null,
            'limit' => null,
            'skip' => null,
            'orphanRemoval' => false,
            'prime' => ['references'],
        ], $classMetadata->fieldMappings['inverseMappedBy']);
    }
}
