# by t3brightside.com

page.includeCSS.pagelist = EXT:pagelist/Resources/Public/Styles/pagelist.css

###
# List of subpages or selected pages as a special menu item
###

tt_content.defaultpagelist =< lib.fluidContent
tt_content.defaultpagelist.templateRootPaths.200 = EXT:pagelist/Resources/Private/Templates/
tt_content.defaultpagelist.partialRootPaths.200 = EXT:pagelist/Resources/Private/Partials
tt_content.pagelist_sub =< tt_content.defaultpagelist
tt_content.pagelist_sub {
    templateName = Pagelist
    dataProcessing {
        10 = TYPO3\CMS\Frontend\DataProcessing\MenuProcessor
        10 {
            special = directory
			special.value.field = pages
			alternativeSortingField.field = tx_pagelist_orderby
			maxItems.field = tx_pagelist_limit
			begin.field = tx_pagelist_startfrom
            dataProcessing {
                10 = TYPO3\CMS\Frontend\DataProcessing\FilesProcessor
                10 {
                    references.fieldName = media
                }
            }
        }
    }
    stdWrap {
        editIcons = tt_content: header [header_layout], pages
        editIcons {
            iconTitle.data = LLL:EXT:fluid_styled_content/Resources/Private/Language/FrontendEditing.xlf:editIcon.menu
        }
    }
}
tt_content.pagelist_selected < tt_content.pagelist_sub
tt_content.pagelist_selected.dataProcessing.10.special = list

tt_content.pagelist_category < tt_content.pagelist_sub
tt_content.pagelist_category.dataProcessing.10.special = categories
tt_content.pagelist_category.dataProcessing.10.special.value.field = selected_categories
tt_content.pagelist_category.dataProcessing.10.special.relation.field = category_field
tt_content.pagelist_category.dataProcessing.10.special.sorting = sorting
