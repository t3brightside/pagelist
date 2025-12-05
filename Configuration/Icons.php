<?php
declare(strict_types=1);

use TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider;
use TYPO3\CMS\Core\Imaging\IconRegistry;

return [
    'mimetypes-x-content-pagelist' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:pagelist/Resources/Public/Icons/mimetypes-x-content-pagelist.svg',
    ],
    'mimetypes-x-page-article' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:pagelist/Resources/Public/Icons/ico_article.svg',
    ],
    'mimetypes-x-page-event' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:pagelist/Resources/Public/Icons/ico_event.svg',
    ],
    'mimetypes-x-page-product' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:pagelist/Resources/Public/Icons/ico_product.svg',
    ],
    'mimetypes-x-page-vacancy' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:pagelist/Resources/Public/Icons/ico_vacancy.svg',
    ],
];