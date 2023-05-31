# Pagelist
[![License](https://poser.pugx.org/t3brightside/pagelist/license)](LICENSE.txt)
[![Packagist](https://img.shields.io/packagist/v/t3brightside/pagelist.svg?style=flat)](https://packagist.org/packages/t3brightside/pagelist)
[![Downloads](https://poser.pugx.org/t3brightside/pagelist/downloads)](https://packagist.org/packages/t3brightside/pagelist)
[![Brightside](https://img.shields.io/badge/by-t3brightside.com-orange.svg?style=flat)](https://t3brightside.com)

**TYPO3 CMS extension to create page lists and add custom page types.**
Page lists from selected page records or subpages.
**[Demo](https://microtemplate.t3brightside.com/)**

## Breaking Changes
- **v3.0.0** see the [ChangeLog](ChangeLog)

## Features
- Custom page types for articles, events, products and vacancies
- List of sub pages with recursive option
- List of selected pages
- Exclude pages from lists
- Category and author filtering
- Set start from, limit and sort options
- Shortcut new page types to documents, other pages or external urls
- Pagination with [paginatedprocessors](https://github.com/t3brightside/paginatedprocessors)
- Connection to [personnel](https://github.com/t3brightside/personnel) for authors and contact persons
- Base templates and CSS for cards and lists
- Easy to add custom templates
- vCal support for event pages

## System requirements
- TYPO3
- fluid_styled_content
- paginatedprocessors

## Conflicts with
- t3g/blog

## Installation
 - `composer req t3brightside/pagelist` or from TYPO3 extension repository **[pagelist](https://extensions.typo3.org/extension/pagelist/)**
 - Include static template
 - Include static template for Paginatedprocessors
 - Enable page types for news, events, and products in extension configuration
 - Recommended for author records **[t3brightside/personnel](https://extensions.typo3.org/extension/personnel/)**

## Usage
Add as any other content element. Select desired pages, template and options in content element settings.

### Add custom template
**TypoScript**
Check the constant editor.

**PageTS**
```
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

### routeEnhancers
For the pagination routing check [t3brightside/paginatedprocessors](https://github.com/t3brightside/paginatedprocessors#readme)

```yaml
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

## Known issues
Doesn't fully comply with the language modes. Does not respect '[FE][hidePagesIfNotTranslatedByDefault] = true' as 'TYPO3\CMS\Frontend\DataProcessing\DatabaseQueryProcessor' does not fully respect language modes while selecting pages yet.

Sys categories have to be saved somewhere in the same page root to show categories in page templates with the {page.categories}

## Sources
-  [GitHub](https://github.com/t3brightside/pagelist)
-  [Packagist](https://packagist.org/packages/t3brightside/pagelist)
-  [TER](https://extensions.typo3.org/extension/pagelist/)

## Development & maintenance
[Brightside OÜ – TYPO3 development and hosting specialised web agency](https://t3brightside.com/)
