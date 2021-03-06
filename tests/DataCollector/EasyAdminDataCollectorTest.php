<?php

namespace EasyCorp\Bundle\EasyAdminBundle\Tests\Controller;

use EasyCorp\Bundle\EasyAdminBundle\Tests\Fixtures\AbstractTestCase;

class EasyAdminDataCollectorTest extends AbstractTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->initClient(['environment' => 'default_backend']);
    }

    public function testCollectorIsEnabled()
    {
        $this->client->enableProfiler();
        $this->requestListView();

        $this->assertNotNull($this->client->getProfile()->getCollector('easyadmin'));
    }

    public function testCollectorInformationForListView()
    {
        $this->client->enableProfiler();
        $this->requestListView();
        $collector = $this->client->getProfile()->getCollector('easyadmin');

        $this->assertSame(5, $collector->getNumEntities());

        $parameters = [
            'action' => 'list',
            'entity' => 'Category',
            'id' => null,
            'sort_field' => 'id',
            'sort_direction' => 'DESC',
        ];
        $this->assertSame($parameters, $collector->getRequestParameters());

        $currentConfiguration = $collector->getCurrentEntityConfig();
        $this->assertSame('Category', $currentConfiguration['name']);

        $backendConfig = $collector->getBackendConfig();
        $this->assertCount(5, $backendConfig['entities']);
    }

    public function testCollectorInformationForEditView()
    {
        $this->client->enableProfiler();
        $this->requestEditView();
        $collector = $this->client->getProfile()->getCollector('easyadmin');

        $this->assertSame(5, $collector->getNumEntities());

        $parameters = [
            'action' => 'edit',
            'entity' => 'Category',
            'id' => '200',
            'sort_field' => 'id',
            'sort_direction' => 'DESC',
        ];
        $this->assertSame($parameters, $collector->getRequestParameters());

        $currentConfiguration = $collector->getCurrentEntityConfig();
        $this->assertSame('Category', $currentConfiguration['name']);

        $backendConfig = $collector->getBackendConfig();
        $this->assertCount(5, $backendConfig['entities']);
    }

    public function testCollectorReset()
    {
        $this->client->enableProfiler();
        $this->requestListView();
        $collector = $this->client->getProfile()->getCollector('easyadmin');

        $this->assertSame(5, $collector->getNumEntities());
        $collector->reset();
        $this->assertSame(0, $collector->getNumEntities());
    }
}
