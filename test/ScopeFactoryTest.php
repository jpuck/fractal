<?php

namespace League\Fractal\Test;

use League\Fractal\Manager;
use League\Fractal\Resource\ResourceInterface;
use League\Fractal\Scope;
use League\Fractal\ScopeFactory;

class ScopeFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testItImplementsScopeFactoryInterface()
    {
        $this->assertInstanceOf('League\\Fractal\\ScopeFactoryInterface', $this->createSut());
    }

    public function testItCreatesScopes()
    {
        $sut = $this->createSut();

        $resource = $this->createResource();
        $scopeIdentifier = 'foo_identifier';

        $scope = $sut->createScopeFor($resource, $scopeIdentifier);

        $this->assertInstanceOf('League\\Fractal\\Scope', $scope);
        $this->assertSame($resource, $scope->getResource());
        $this->assertSame($scopeIdentifier, $scope->getScopeIdentifier());
    }

    public function testItCreatesScopesWithParent()
    {
        $manager = $this->createManager();

        $scope = new Scope($manager, $this->createResource(), 'parent_identifier');
        $scope->setParentScopes([
            'parent_scope',
        ]);

        $resource = $this->createResource();
        $scopeIdentifier = 'foo_identifier';

        $expectedParentScopes = [
            'parent_scope',
            'parent_identifier',
        ];

        $sut = $this->createSut($manager);
        $scope = $sut->createChildScopeFor($scope, $resource, $scopeIdentifier);

        $this->assertInstanceOf('League\\Fractal\\Scope', $scope);
        $this->assertSame($resource, $scope->getResource());
        $this->assertSame($scopeIdentifier, $scope->getScopeIdentifier());
        $this->assertEquals($expectedParentScopes, $scope->getParentScopes());
    }

    /**
     * @param Manager $manager
     * @return ScopeFactory
     */
    private function createSut(Manager $manager = null)
    {
        if ($manager === null) {
            $manager = $this->createManager();
        }

        return new ScopeFactory($manager);
    }

    /**
     * @return Manager
     */
    private function createManager()
    {
        return $this->getMock('League\\Fractal\\Manager');
    }

    /**
     * @return ResourceInterface
     */
    private function createResource()
    {
        return $this->getMock('League\\Fractal\\Resource\\ResourceInterface');
    }
}
