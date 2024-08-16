<?php
namespace Brightside\Pagelist\DataProcessing;

use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\DataProcessing\DatabaseQueryProcessor;
use Brightside\Paginatedprocessors\Processing\DataToPaginatedData;

class PaginatedPagelistDatabaseQueryProcessor extends DatabaseQueryProcessor
{
    /**
     * Fetch records from the database with additional filters and pagination.
     *
     * @param ContentObjectRenderer $cObj
     * @param array $contentObjectConfiguration
     * @param array $processorConfiguration
     * @param array $processedData
     *
     * @return array
     */
    public function process(
        ContentObjectRenderer $cObj,
        array $contentObjectConfiguration,
        array $processorConfiguration,
        array $processedData
    ): array {
        // Ensure processorConfiguration is an array
        $processorConfiguration = $processorConfiguration ?: [];

        $joinClauses = [];

        // Handle categories filtering
        $selectedCategories = $cObj->data['selected_categories'] ?? [];
        if (!empty($selectedCategories)) {
            $joinClauses[] = 'sys_category_record_mm ON uid = sys_category_record_mm.uid_foreign AND sys_category_record_mm.uid_local IN(' . $selectedCategories . ') AND sys_category_record_mm.tablenames=\'pages\' AND sys_category_record_mm.fieldname=\'categories\'';
        }

        // Handle Personnel filtering
        $authors = $cObj->data['tx_pagelist_authors'] ?? [];
        if (!empty($authors)) {
            $joinClauses[] = 'tx_personnel_mm ON uid = tx_personnel_mm.uid_foreign AND tx_personnel_mm.uid_local IN(' . $authors . ') AND tx_personnel_mm.tablenames=\'pages\' AND tx_personnel_mm.fieldname=\'tx_personnel\'';
        }

        // Add join to configuration
        if ($joinClauses) {
            $processorConfiguration['join'] = implode(' JOIN ', $joinClauses);
        }

        // Call parent process method
        $processedData = parent::process($cObj, $contentObjectConfiguration, $processorConfiguration, $processedData);

        // Apply pagination
        $paginationSettings = $processorConfiguration['pagination.'] ?? [];
        $paginationIsActive = (int)($cObj->stdWrapValue('isActive', $paginationSettings) ?? 0);

        if ($paginationIsActive) {
            $paginatedData = new DataToPaginatedData();
            $processedData = $paginatedData->getPaginatedData(
                $cObj,
                $contentObjectConfiguration,
                $processorConfiguration,
                $processedData,
                $processedData[$processorConfiguration['as']],
                $processorConfiguration['as']
            );
        }
        return $processedData;
    }
}
