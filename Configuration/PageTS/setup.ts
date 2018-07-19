#TCEFORM.pages.tx_pagelist_images.types.136.disabled = 0

TCEFORM.tt_content.tx_pagelist_template.addItems {
	0 = Image and title as cards
	1 = Articles with images
	2 = Articles with no images
}

mod.wizards.newContentElement.wizardItems.common {
   elements {
      pagelist_selected {
         iconIdentifier = mimetypes-x-content-pagelist
         title = List of Pages
         description = Shows selected pages.
         tt_content_defValues {
            CType = pagelist_selected
         }
      }
      pagelist_sub {
         iconIdentifier = mimetypes-x-content-pagelist
         title = List of Subpages
         description = Shows subpages of selected pages.
         tt_content_defValues {
            CType = pagelist_sub
         }
      }
      pagelist_category {
         iconIdentifier = mimetypes-x-content-pagelist
         title = List of Pages in Category
         description = Shows pages that belong to selected category(s).
         tt_content_defValues {
            CType = pagelist_category
         }
      }
   }
   show := addToList(pagelist_selected,pagelist_sub,pagelist_category)
}
