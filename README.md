# Pagelist
[![Software License](https://img.shields.io/badge/license-GPLv2-brightgreen.svg?style=flat)](LICENSE.txt)
[![Packagist](https://img.shields.io/packagist/v/t3brightside/pagelist.svg?style=flat)](https://packagist.org/packages/t3brightside/pagelist)
[![Downloads](https://poser.pugx.org/t3brightside/pagelist/downloads)](https://packagist.org/packages/t3brightside/pagelist)
[![Brightside](https://img.shields.io/badge/by-t3brightside.com-orange.svg?style=flat)](https://t3brightside.com)

**TYPO3 CMS extension to list pages, news, events, products etc.**<br />
Adds new content elements and page types to create different lists.

**[Front-end Demo](https://microtemplate.t3brightside.com/)**

### System requirements

- TYPO3 8.7 LTS, from 2.2.0 9.5 & 10.4 LTS, from 2.5.1 11.5 LTS
- fluid_styled_content
- paginatedprocessors

### Conflicts with

- t3g/blog


### Features

- Custom page types for articles, events and products
- List of sub pages
- List of selected pages
- Category filtering
- Set start from, limit and sort options
- Pagination with items per page and unique to content element
- Dedicated page types for news, events and products
- Connection to ext:[Personnel][863416d1] for authors and contact persons
- ext:Personnel fields can be enabled/disabled per page type
- Easy to add custom templates
- Exclude pages from lists

  [863416d1]: https://extensions.typo3.org/extension/personnel/ "ext:Personnel"

### Installation

 - TER: **pagelist**, or **composer req t3brightside/pagelist**
 - Include static template
 - Enable page types for news, events, and products from extension configuration
 - Recommended for author records **t3brightside/personnel**

### Usage

Add as any other content element. Select desired pages, template and options in content element settings.

### Admin

#### Add custom template

**TypoScript**
<br />Change constants:
```typoscript
pagelist.styles = EXT:pagelist/Resources/Public/Styles/pagelist.css
pagelist.templateRootPaths = EXT:pagelist/Resources/Private/Templates/
pagelist.partialRootPaths = EXT:pagelist/Resources/Private/Partials/
```
**PageTS**
```typoscript
TCEFORM.tt_content.tx_pagelist_template.addItems {
  minilist = Mini list
}
```
**Fluid**<br />
Add new section with IF condition to determine template name 'minilist' intoto: _Resources/Private/Templates/Pagelist.html_
```xml
<f:if condition="{data.tx_pagelist_template} == minilist">
  <div class="pagelist custom template-{data.tx_pagelist_template}">
    <f:for each="{pagelist}" as="page" iteration="iterator">
      <f:render partial="Minilist" arguments="{_all}" />
    </f:for>
  </div>
</f:if>
```
Create new partial: _Resources/Private/Partials/Minilist.html_

**Route enhancers for pagination**<br />
for TYPO3 11.5 and above check [t3brightside/paginatedprocessors](https://github.com/t3brightside/paginatedprocessors#readme)

```json
  /* v10.4 and below */
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

### Known issues
Doesn't fully comply with the language modes. Does not respect '[FE][hidePagesIfNotTranslatedByDefault] = true' as 'TYPO3\CMS\Frontend\DataProcessing\DatabaseQueryProcessor' does not fully respect language modes while selecting pages yet.

### Sources

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
