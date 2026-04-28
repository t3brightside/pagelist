<?php
namespace Brightside\Pagelist\DataProcessing;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\ContentDataProcessor;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;
use TYPO3\CMS\Frontend\DataProcessing\DatabaseQueryProcessor;
use Brightside\Paginatedprocessors\Processing\DataToPaginatedData;

// 1. Implement DataProcessorInterface instead of extending DatabaseQueryProcessor
class SelectedPagesDatabaseQueryProcessor implements DataProcessorInterface
{
    // 2. Add a private property to hold the ContentDataProcessor
    private ContentDataProcessor $contentDataProcessor;

    public function __construct(ContentDataProcessor $contentDataProcessor = null)
    {
        // 3. Store the injected (or manually instantiated) processor in our local property.
        // No need to call parent::__construct() anymore!
        $this->contentDataProcessor = $contentDataProcessor ?? GeneralUtility::makeInstance(ContentDataProcessor::class);
    }

    public function process(
        ContentObjectRenderer $cObj,
        array $contentObjectConfiguration,
        array $processorConfiguration,
        array $processedData
    ) {
        if (isset($processorConfiguration['if.']) && !$cObj->checkIf($processorConfiguration['if.'])) {
            return $processedData;
        }

        $tableName = $cObj->stdWrapValue('table', $processorConfiguration);
        if (empty($tableName)) {
            return $processedData;
        }
        unset($processorConfiguration['table'], $processorConfiguration['table.']);

        $targetVariableName = $cObj->stdWrapValue('as', $processorConfiguration, 'records');

        if (
            isset($cObj->data['tx_pagelist_orderby'])
            && $cObj->data['tx_pagelist_orderby'] !== ""
            && $cObj->data['tx_pagelist_orderby'] !== "0"
        ) {
            $processorConfiguration['orderBy'] = $cObj->data['tx_pagelist_orderby'];
        }

        $records = $cObj->getRecords($tableName, $processorConfiguration);

        $processedRecordVariables = [];
        foreach ($records as $record) {
            $recordContentObjectRenderer = GeneralUtility::makeInstance(ContentObjectRenderer::class);
            $recordContentObjectRenderer->start($record, $tableName);

            $processedRecordVariables[$record['uid']] = ['data' => $record];
            
            // This safely uses our local $this->contentDataProcessor property now
            $processedRecordVariables[$record['uid']] =
                $this->contentDataProcessor->process(
                    $recordContentObjectRenderer,
                    $processorConfiguration,
                    $processedRecordVariables[$record['uid']]
                );
        }

        if (
            isset($cObj->data['tx_pagelist_orderby'])
            && $cObj->data['CType'] === "pagelist_selected"
            && !empty($cObj->data['pages'])
        ) {
            $defaultSorting = array_flip(GeneralUtility::intExplode(",", $cObj->data['pages']));

            $sorted = array_filter(
                array_replace($defaultSorting, $processedRecordVariables),
                fn($item) => !is_int($item)
            );

            if ($sorted) {
                $processedRecordVariables = $sorted;
            }
        }

        $processedData[$targetVariableName] = $processedRecordVariables;

        // --- THE MAGIC HAPPENS HERE ---
        
        // 4. Instantiate the core DatabaseQueryProcessor manually
        $databaseProcessor = GeneralUtility::makeInstance(DatabaseQueryProcessor::class);

        // 5. Let the core processor handle its part
        $allProcessedData = $databaseProcessor->process(
            $cObj,
            $contentObjectConfiguration,
            $processorConfiguration,
            $processedData
        );

        // ------------------------------

        $paginationSettings = $processorConfiguration['pagination.'] ?? [];

        if ((int)$cObj->stdWrapValue('isActive', $paginationSettings)) {
            $paginatedData = new DataToPaginatedData();
            $allProcessedData = $paginatedData->getPaginatedData(
                $cObj,
                $contentObjectConfiguration,
                $processorConfiguration,
                $allProcessedData,
                $allProcessedData[$processorConfiguration['as']],
                $processorConfiguration['as']
            );
        }

        return $allProcessedData;
    }
}