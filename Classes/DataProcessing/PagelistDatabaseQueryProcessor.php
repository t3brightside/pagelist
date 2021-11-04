<?php
namespace Brightside\Pagelist\DataProcessing;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\DataProcessing\DatabaseQueryProcessor;
use TYPO3\CMS\Core\Pagination\ArrayPaginator;
use TYPO3\CMS\Extbase\Pagination\QueryResultPaginator;
use TYPO3\CMS\Core\Pagination\SimplePagination;

class PagelistDatabaseQueryProcessor extends DatabaseQueryProcessor
{
    public function process(
        ContentObjectRenderer $cObj,
        array $contentObjectConfiguration,
        array $processorConfiguration,
        array $processedData
    ) {
        $allProcessedData = parent::process($cObj, $contentObjectConfiguration, $processorConfiguration, $processedData);
        $paginationSettings = $processorConfiguration['paginate.'];
        $paginationIsActive = (int)($cObj->stdWrapValue('isActive', $paginationSettings ?? []));

        if ($paginationIsActive) {
          $parameter = $cObj->getRequest()->getQueryParams()[$paginationSettings['parameterIndex']];

          $itemsToPaginate = $allProcessedData[$processorConfiguration['as']];
          $currentPage = (int)$parameter['currentPage'] ? : 1;
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
