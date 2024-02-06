<?php

declare(strict_types=1);

namespace App\DataFixtures\Document;

use Sulu\Bundle\DocumentManagerBundle\DataFixtures\DocumentFixtureInterface;
use Sulu\Bundle\PageBundle\Document\PageDocument;
use Sulu\Component\Content\Document\WorkflowStage;
use Sulu\Component\DocumentManager\DocumentManager;
use Sulu\Component\DocumentManager\Exception\MetadataNotFoundException;

class DocumentFixtures implements DocumentFixtureInterface
{
    public function load(DocumentManager $documentManager): void
    {

        $this->loadRestrictedPage($documentManager);

        $documentManager->flush();
        $documentManager->clear();
    }

    /**
     * @throws MetadataNotFoundException
     */
    private function loadRestrictedPage(DocumentManager $documentManager): void
    {
        $pageData = [
            'locale' => 'en',
            'title' => 'Secure Area',
            'url' => '/secure-area',
            'article' => '<p>Some sample text for this super secret area.</p>',
            'structureType' => 'default',
        ];

        $extensionData = [
            'seo' => [],
            'excerpt' => [],
        ];

        /** @var PageDocument $pageDocument */
        $pageDocument = $documentManager->create('page');

        $pageDocument->setNavigationContexts([]);
        $pageDocument->setLocale($pageData['locale']);
        $pageDocument->setTitle($pageData['title']);
        $pageDocument->setResourceSegment($pageData['url']);
        $pageDocument->setStructureType($pageData['structureType']);
        $pageDocument->setWorkflowStage(WorkflowStage::PUBLISHED);
        $pageDocument->getStructure()->bind($pageData);
        $pageDocument->setAuthor(1);
        $pageDocument->setExtensionsData($extensionData);

        $documentManager->persist(
            $pageDocument,
            'en',
            ['parent_path' => '/cmf/example/contents'],
        );
        $documentManager->publish($pageDocument, 'en');
    }

    public function getOrder(): int
    {
        return 100;
    }
}
