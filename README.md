# Pagelist
[![Software License](https://img.shields.io/badge/license-GPLv2-brightgreen.svg?style=flat)](LICENSE.txt)
[![Packagist](https://img.shields.io/packagist/v/t3brightside/pagelist.svg?style=flat)](https://packagist.org/packages/t3brightside/pagelist)
[![Downloads](https://poser.pugx.org/t3brightside/pagelist/downloads)](https://packagist.org/packages/t3brightside/pagelist)
[![Brightside](https://img.shields.io/badge/by-t3brightside.com-orange.svg?style=flat)](https://t3brightside.com)

**TYPO3 CMS extension to create page lists and add custom page types.**
Page lists from selected page records or subpages.
**[Demo](https://microtemplate.t3brightside.com/)**

### Breaking Changes
- **v2.6** see the [ChangeLog](ChangeLog)

### Features
- Custom page types for articles, events, products and vacancies
- List of sub pages
- List of selected pages
- Exclude pages from lists
- Category filtering
- Set start from, limit and sort options
- Pagination with items per page and unique to the content element with [paginatedprocessors](https://github.com/t3brightside/paginatedprocessors)
- Connection to [personnel](https://github.com/t3brightside/personnel) for authors and contact persons
- Personnel fields can be enabled/disabled per page type
- Easy to add custom templates
- Base templates and CSS for cards and lists

### System requirements
- TYPO3 8.7 LTS – 11.5 LTS
- fluid_styled_content
- paginatedprocessors

### Conflicts with
- t3g/blog

### Installation

 - **composer req t3brightside/pagelist** or from TYPO3 extension repository **[pagelist](https://extensions.typo3.org/extension/pagelist/)**
 - Include static template
 - Enable page types for news, events, and products in extension configuration
 - Recommended for author records **[t3brightside/personnel](https://extensions.typo3.org/extension/personnel/)**

### Usage
Add as any other content element. Select desired pages, template and options in content element settings.

#### Add custom template
**TypoScript**
Check the constant editor.

**PageTS**
```typoscript
TCEFORM.tt_content.tx_pagelist_template.addItems {
  minilist = Mini list
}
```
**Fluid**
Add new section with IF condition to determine template name 'minilist' to: _Resources/Private/Templates/Pagelist.html_
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

#### routeEnhancers
For the pagination routing check [t3brightside/paginatedprocessors](https://github.com/t3brightside/paginatedprocessors#readme)

```json
  /* only TYPO3 10.4 and below */
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
-  [GitHub](https://github.com/t3brightside/pagelist)
-  [Packagist](https://packagist.org/packages/t3brightside/pagelist)
-  [TER](https://extensions.typo3.org/extension/pagelist/)

### Development & maintenance
[Brightside OÜ – TYPO3 development and hosting specialised web agency](https://t3brightside.com/)
