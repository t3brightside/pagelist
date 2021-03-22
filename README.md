# Pagelist
[![Packagist](https://img.shields.io/packagist/v/t3brightside/pagelist.svg?style=flat)](https://packagist.org/packages/t3brightside/pagelist)
[![Software License](https://img.shields.io/badge/license-GPLv3-brightgreen.svg?style=flat)](LICENSE)
[![Brightside](https://img.shields.io/badge/by-t3brightside.com-orange.svg?style=flat)](https://t3brightside.com)

**TYPO3 CMS extension to list pages, news, events, products etc.**

Adds new content elements and page types to create different lists.

**[Front-end Demo](https://microtemplate.t3brightside.com/)**

## System requirements

- TYPO3 8.7 LTS, since 2.2.0 9.5 & 10.4 LTS
- fluid_styled_content

## Conflicts with

- t3g/blog


## Features

- Custom page types for articles, events and products
- List of sub pages
- List of selected pages
- List of pages in category
- Set start from, limit and sort options
- Enable pagination with items per page
- Dedicated page types for news, events and products
- Connection to ext:[Personnel][863416d1] for authors and contact persons
- ext:Personnel fields can be enabled/disabled per page type
- Easy to add custom templates
- Exclude pages from lists

  [863416d1]: https://extensions.typo3.org/extension/personnel/ "ext:Personnel"

## Installation

 - From TER: **pagelist**, or composer: **t3brightside/pagelist**
 - Include static template
 - Enable page types for news, events, and products from extension configuration
 - Recommended for author records ext:Personnel / **t3brightside/personnel**

## Usage

Add as any other content element. Select desired pages, template and options in content element settings.

## Admin

### Add custom template

**PageTS**

Add new template number '2' and name it:
```typoscript
TCEFORM.tt_content.tx_pagelist_template.addItems {
  2 = My New Template
}
```

**TypoScript**

Change constants:
```typoscript
pagelist.styles = EXT:pagelist/Resources/Public/Styles/pagelist.css
pagelist.templateRootPaths = EXT:pagelist/Resources/Private/Templates/
pagelist.partialRootPaths = EXT:pagelist/Resources/Private/Partials/
```

**Fluid**

Add new section wheres IF condition determines template nr '2' to: _Resources/Private/Templates/Pagelist.html_
```xml
<f:if condition="{data.tx_pagelist_template} == 2">
  <div class="pagelist custom template-{data.tx_pagelist_template}">
    <f:for each="{pagelist}" as="page" iteration="iterator">
      <f:render partial="MyCustomPartial" arguments="{_all}" />
    </f:for>
  </div>
</f:if>
```
Create new partial: _Resources/Private/Partials/MyCustomPartial.html_

**Route enhancers for pagination**
```json
routeEnhancers:
  Pagelist:
    type: Plugin
    routePath: '/page/{@widget_0/currentPage}'
    namespace: 'tx_pagelist_pagelist'
    aspects:
      '@widget_0/currentPage':
        type: StaticRangeMapper
        start: '1'
        end: '999'
```

## Known issues
Doesn't fully comply with the language modes. Does not respect '[FE][hidePagesIfNotTranslatedByDefault] = true' as 'TYPO3\CMS\Frontend\DataProcessing\DatabaseQueryProcessor' does not fully respect language modes while selecting pages yet.

## Sources

-  [GitHub][a47ab545]
-  [Packagist][40819ab1]
-  [TER][15e0f507]

  [a47ab545]: https://github.com/t3brightside/pagelist "GitHub"
  [40819ab1]: https://packagist.org/packages/t3brightside/pagelist "Packagist"
  [15e0f507]: https://extensions.typo3.org/extension/pagelist/ "Typo3 Extension Repository"

Development and maintenance
---------------------------

[Brightside OÜ – TYPO3 development and hosting specialised web agency][ab26eed2]

  [ab26eed2]: https://t3brightside.com/ "TYPO3 development and hosting specialised web agency"
