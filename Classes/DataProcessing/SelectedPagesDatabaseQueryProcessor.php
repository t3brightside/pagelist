<?php
namespace Brightside\Pagelist\DataProcessing;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\ContentDataProcessor;
use TYPO3\CMS\Frontend\DataProcessing\DatabaseQueryProcessor;
use Brightside\Paginatedprocessors\Processing\DataToPaginatedData;

class SelectedPagesDatabaseQueryProcessor extends DatabaseQueryProcessor
{
    public function __construct(ContentDataProcessor $contentDataProcessor = null)
    {
        if ($contentDataProcessor === null) {
            $contentDataProcessor = GeneralUtility::makeInstance(ContentDataProcessor::class);
        }

        parent::__construct($contentDataProcessor);
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

        $allProcessedData = parent::process(
            $cObj,
            $contentObjectConfiguration,
            $processorConfiguration,
            $processedData
        );

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