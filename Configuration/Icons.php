<?php
declare(strict_types=1);

use TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider;
use TYPO3\CMS\Core\Imaging\IconRegistry;

return [
    'mimetypes-x-content-pagelist' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:pagelist/Resources/Public/Icons/mimetypes-x-content-pagelist.svg',
    ],
    'pagelist-page-article' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:pagelist/Resources/Public/Icons/ico_article.svg',
    ],
    'pagelist-page-event' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:pagelist/Resources/Public/Icons/ico_event.svg',
    ],
    'pagelist-page-product' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:pagelist/Resources/Public/Icons/ico_product.svg',
    ],
    'pagelist-page-vacancy' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:pagelist/Resources/Public/Icons/ico_vacancy.svg',
    ],
];