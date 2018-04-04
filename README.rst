Page List
=============

**TYPO3 CMS extension for easy to configure teasers, news lists, product lists etc.**

System requirements
-------------------

- TYPO3 8.7 LTS – 9.*
- fluid_styled_content

Features
--------

- List of sub pages
- List of selected pages
- List of pages in category
– Set stat from, limit and sort by
- Easy to add custom templates

Installation
------------
-  Install from TER (**pagelist**) or Composer (**t3brightside/pagelist**)
-  Include static template

Usage
-----

Add as any other content element. Select desired template in content element settings.

Admin
-----

**Add new and/or overwrite templates to back end in PageTS:**

>>>
  TCEFORM.tt_content.tx_pagelist_template.addItems {
    2 = My Template Name
  }
<<<

**Removing default templates from back end:**

>>>
 TCEFORM.tt_content.tx_pagelist_template.removeItems = 0,1
<<<

**For customizing template take a look at:**

- *pagelist/Configuration/TypoScript/setup.ts* – how to change the location of the Fluid template and CSS file
- *pagelist/Resources/Private/Templates/Pagelist.html* – how to change the HTML template regarding the tx_pagelist_template number

Sources
-------

-  `GitHub`_
-  `Packagist`_
-  `TER`_

Development and maintenance
---------------------------

`Brightside OÜ`_ – TYPO3 specialized web agency

.. _GitHub: https://github.com/t3brightside/pagelist
.. _Packagist: https://packagist.org/packages/t3brightside/pagelist
.. _TER: https://extensions.typo3.org/extension/pagelist/
.. _Brightside OÜ: https://t3brightside.com/
