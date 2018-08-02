page.includeCSS.pagelist = {$pagelist.styles}

[globalVar = LIT:0<{$pagelist.enablejQueryBreakpoints}]
  page.jsFooterInline {
    109823122 = TEXT
    109823122.value (
  		jQuery( document ).ready(function() {
  			jQuery('.pagelist.cards').each(function() {
  			  if (jQuery(this).width() < {$pagelist.cardsBreakTwo}){
  			  	jQuery(this).addClass( 'break-two' );
  			  } else{
  					jQuery(this).removeClass('break-two')
  				}
          if (jQuery(this).width() < {$pagelist.cardsBreakOne}){
            jQuery(this).removeClass('break-two')
  			  	jQuery(this).addClass( 'break-one' );
  			  } else{
  					jQuery(this).removeClass('break-one')
  				}
  			});
  		});
  		jQuery(window).resize(function(){
  		  jQuery('.pagelist.cards').each(function() {
          if (jQuery(this).width() < {$pagelist.cardsBreakTwo}){
  			  	jQuery(this).addClass( 'break-two' );
  			  } else{
  					jQuery(this).removeClass('break-two')
  				}
          if (jQuery(this).width() < {$pagelist.cardsBreakOne}){
            jQuery(this).removeClass('break-two')
  			  	jQuery(this).addClass( 'break-one' );
  			  } else{
  					jQuery(this).removeClass('break-one')
  				}
  			});
  		});
  	)
  }
}
[global]

tt_content.defaultpagelist =< lib.fluidContent
tt_content.defaultpagelist.templateRootPaths.10 = EXT:pagelist/Resources/Private/Templates/
tt_content.defaultpagelist.templateRootPaths.20 = {$pagelist.templateRootPaths}
tt_content.defaultpagelist.partialRootPaths.10 = EXT:pagelist/Resources/Private/Partials/
tt_content.defaultpagelist.partialRootPaths.20 = {$pagelist.partialRootPaths}

tt_content.pagelist_sub =< tt_content.defaultpagelist
tt_content.pagelist_sub {
  templateName = Pagelist
  dataProcessing.10 = TYPO3\CMS\Frontend\DataProcessing\DatabaseQueryProcessor
  dataProcessing.10 {
    #  if.isTrue.field = title
    table = pages

    pidInList.field = pages
    orderBy.field = tx_pagelist_orderby
		max.field = tx_pagelist_limit
		begin.field = tx_pagelist_startfrom
    languageField = sys_language_uid
    as = pagelist

    dataProcessing {
      10 = TYPO3\CMS\Frontend\DataProcessing\FilesProcessor
      10.references.fieldName = media
      20 = TYPO3\CMS\Frontend\DataProcessing\FilesProcessor
      20.references.fieldName = tx_pagelist_images
      20.as = tx_pagelist_images
    }
  }
  extbase {
    pluginName = Pagelist
    controllerName = Pagelist
    controllerExtensionName = pagelist
    controllerActionName = show
  }
  stdWrap {
    editIcons = tt_content: header [header_layout], pages
    editIcons {
      iconTitle.data = LLL:EXT:fluid_styled_content/Resources/Private/Language/FrontendEditing.xlf:editIcon.menu
    }
  }
}

tt_content.pagelist_selected =< tt_content.defaultpagelist
tt_content.pagelist_selected {
  templateName = Pagelist
  dataProcessing {
    10 = TYPO3\CMS\Frontend\DataProcessing\MenuProcessor
    10 {
      special = list
			special.value.field = pages
			alternativeSortingField.field = tx_pagelist_orderby
			maxItems.field = tx_pagelist_limit
			begin.field = tx_pagelist_startfrom
      as = pagelist
      dataProcessing {
        10 = TYPO3\CMS\Frontend\DataProcessing\FilesProcessor
        10.references.fieldName = media
        20 = TYPO3\CMS\Frontend\DataProcessing\FilesProcessor
        20.references.fieldName = tx_pagelist_images
        20.as = tx_pagelist_images
      }
    }
  }
  extbase {
    pluginName = Pagelist
    controllerName = Pagelist
    controllerExtensionName = pagelist
    controllerActionName = show
  }
  stdWrap {
    editIcons = tt_content: header [header_layout], pages
    editIcons {
      iconTitle.data = LLL:EXT:fluid_styled_content/Resources/Private/Language/FrontendEditing.xlf:editIcon.menu
    }
  }
}

# tt_content.pagelist_category < tt_content.pagelist_selected
# tt_content.pagelist_category.dataProcessing.10.special = categories
# tt_content.pagelist_category.dataProcessing.10.special.value.field = selected_categories
# tt_content.pagelist_category.dataProcessing.10.special.relation.field = category_field
# tt_content.pagelist_category.dataProcessing.10.special.sorting.field = sorting
