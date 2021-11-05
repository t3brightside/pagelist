<?php
declare(strict_types = 1);

namespace Brightside\Pagelist\DataProcessing;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\DataProcessing\DatabaseQueryProcessor;
use TYPO3\CMS\Core\Pagination\ArrayPaginator;
use TYPO3\CMS\Extbase\Pagination\QueryResultPaginator;
use TYPO3\CMS\Core\Pagination\SimplePagination;

/**
 * Adds pagination API to the DatabaseQueryProcessor
 */

class PagelistDatabaseQueryProcessor extends DatabaseQueryProcessor
{
    public function process(
        ContentObjectRenderer $cObj,
        array $contentObjectConfiguration,
        array $processorConfiguration,
        array $processedData
    ) {

        $allProcessedData = parent::process($cObj, $contentObjectConfiguration, $processorConfiguration, $processedData);
        $paginationSettings = $contentObjectConfiguration['paginator.'];
        $paginationIsActive = (int)($cObj->stdWrapValue('isActive', $paginationSettings ?? []));
        $currentElement = (int)$cObj->getRequest()->getQueryParams()[$paginationSettings['elementUrlKey']] ? : 1;
        $elementUid = $processedData['data']['uid'];

        if ($paginationIsActive) {
          if($currentElement == $elementUid) {
            $currentPage = (int)$cObj->getRequest()->getQueryParams()[$paginationSettings['pageUrlKey']] ? : 1;
          } else {
            $currentPage = 1;
          }
          $itemsToPaginate = $allProcessedData[$processorConfiguration['as']];
          $itemsPerPage = (int)($cObj->stdWrapValue('itemsPerPage', $paginationSettings ?? []));
          $paginator = new ArrayPaginator($itemsToPaginate, $currentPage, $itemsPerPage);
          $pagination = new SimplePagination($paginator);
          $combinedData = array(
            'settings' => $contentObjectConfiguration['settings.'],
            'variables' => $contentObjectConfiguration['variables.'],
            'data' => $allProcessedData['data'],
            $processorConfiguration['as'] => $paginator->getPaginatedItems(),
            'pagination' => array(
              'numberOfPages' => $paginator->getNumberOfPages(),
              'current' => $paginator->getCurrentPageNumber(),
              'paginationPages' => $pagination->getAllPageNumbers(),
              'previousPage' => $pagination->getPreviousPageNumber(),
              'nextPage' => $pagination->getNextPageNumber()
            )
          );
          return $combinedData;
        } else {
          return $allProcessedData;
        }
    }
}
