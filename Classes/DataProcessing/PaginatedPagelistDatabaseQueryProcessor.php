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

        // Categories filtering
        $targetContentElementUid = $cObj->getRequest()->getQueryParams()['pagelistTarget'] ?? '';
        $currentContentElementUid = $cObj->data['uid'] ?? 0;

        // Get the category parameter as a comma-separated string
        $feSelectedCategories = $cObj->getRequest()->getQueryParams()['pagelistCat'] ?? '';

        // Split the string into an array of category IDs
        $feSelectedCategoriesArray = array_filter(explode(',', $feSelectedCategories), 'ctype_digit');

        // Determine the condition to apply: 'AND' or 'OR'
        $catCondition = strtoupper($cObj->getRequest()->getQueryParams()['catCondition'] ?? 'OR'); // Default to OR

        // Retrieve the categories from the backend content element configuration
        $selectedCategories = $cObj->data['selected_categories'] ?? '';

        if ($targetContentElementUid == $currentContentElementUid) {
            if (!empty($selectedCategories) && !empty($feSelectedCategoriesArray)) {
                $selectedCategoriesArray = explode(',', $selectedCategories);

                if ($catCondition === 'OR') {
                    // For OR condition, intersect backend and frontend categories
                    $matchingCategoriesArray = array_intersect($selectedCategoriesArray, $feSelectedCategoriesArray);
                } else {
                    // For AND condition, merge backend and frontend categories
                    $matchingCategoriesArray = array_unique(array_merge($selectedCategoriesArray, $feSelectedCategoriesArray));
                }

                $selectedCategories = implode(',', $matchingCategoriesArray);
            } elseif (empty($selectedCategories) && !empty($feSelectedCategoriesArray)) {
                // If no backend categories, use the frontend selected categories
                $selectedCategories = implode(',', $feSelectedCategoriesArray);
            }
        }

        if (!empty($selectedCategories)) {
            // Handle the SQL query differently based on AND/OR condition
            if ($catCondition === 'AND') {
                foreach ($feSelectedCategoriesArray as $categoryId) {
                    $joinClauses[] = 'sys_category_record_mm AS cat_' . $categoryId . ' ON uid = cat_' . $categoryId . '.uid_foreign AND cat_' . $categoryId . '.uid_local = ' . $categoryId . ' AND cat_' . $categoryId . '.tablenames=\'pages\' AND cat_' . $categoryId . '.fieldname=\'categories\'';
                }
            } else {
                // OR condition can be handled by a simple IN clause
                $joinClauses[] = 'sys_category_record_mm ON uid = sys_category_record_mm.uid_foreign AND sys_category_record_mm.uid_local IN(' . $selectedCategories . ') AND sys_category_record_mm.tablenames=\'pages\' AND sys_category_record_mm.fieldname=\'categories\'';
            }
        } elseif (!empty($feSelectedCategoriesArray)) {
            // If no valid categories, make sure the query returns no results
            $joinClauses[] = 'sys_category_record_mm ON uid = sys_category_record_mm.uid_foreign AND sys_category_record_mm.uid_local IN(0) AND sys_category_record_mm.tablenames=\'pages\' AND sys_category_record_mm.fieldname=\'categories\'';
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
