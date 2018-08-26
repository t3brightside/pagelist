TCAdefaults.pages {
	tx_pagelist_datetime = now
}

TCEFORM.pages {
	title.types.136.label = Title:
	title.types.137.label = Title:
	title.types.138.label = Name:
	abstract.types.136.label = Teaser:
	abstract.types.137.label = Teaser:
	abstract.types.138.label = Summary:
	tx_pagelist_images.types.136.label = List image:
	tx_pagelist_images.types.137.label = List image:
	tx_pagelist_images.types.138.label = List image:
	nav_title.types.136.disabled = 1
	nav_title.types.137.disabled = 1
	nav_title.types.138.disabled = 1
	tx_realurl_pathsegment.types.136.disabled = 1
	tx_realurl_pathsegment.types.137.disabled = 1
	tx_realurl_pathsegment.types.138.disabled = 1
	tx_realurl_exclude.types.136.disabled = 1
	tx_realurl_exclude.types.137.disabled = 1
	tx_realurl_exclude.types.138.disabled = 1
	tx_pagelist_date.types.137.label = Event start:
	tx_pagelist_authors.types.137.label = Contact person(s)
	tx_pagelist_authors.types.138.label = Contact person(s)
}
TCEFORM.pages_language_overlay {
	title.types.136.label = Title:
	title.types.137.label = Title:
	title.types.138.label = Name:
	abstract.types.136.label = Teaser:
	abstract.types.137.label = Teaser:
	abstract.types.138.label = Summary:
	tx_pagelist_images.types.136.label = List image:
	tx_pagelist_images.types.137.label = List image:
	tx_pagelist_images.types.138.label = List image:
	nav_title.types.136.disabled = 1
	nav_title.types.137.disabled = 1
	nav_title.types.138.disabled = 1
	tx_realurl_pathsegment.types.136.disabled = 1
	tx_realurl_pathsegment.types.137.disabled = 1
	tx_realurl_pathsegment.types.138.disabled = 1
	tx_realurl_exclude.types.136.disabled = 1
	tx_realurl_exclude.types.137.disabled = 1
	tx_realurl_exclude.types.138.disabled = 1
	tx_pagelist_date.types.137.label = Event start:
}

TCEFORM.tt_content.tx_pagelist_template.addItems {
	0 = Cards
	1 = List (dependent on page type)
}

mod.wizards.newContentElement.wizardItems.pagelist {
	after = common
	header = Page lists
 	elements {
    pagelist_selected {
			iconIdentifier = mimetypes-x-content-pagelist
			title = Page list: selected
			description = Shows selected pages.
			tt_content_defValues.CType = pagelist_selected
    }
    pagelist_sub {
			iconIdentifier = mimetypes-x-content-pagelist
			title = Page list: subpages
			description = Shows subpages of selected pages.
			tt_content_defValues.CType = pagelist_sub
    }
    pagelist_category {
			iconIdentifier = mimetypes-x-content-pagelist
			title = Page list: category
			description = Shows pages that belong to selected category(s).
			tt_content_defValues.CType = pagelist_category
    }
	}
  show := addToList(pagelist_selected,pagelist_sub,pagelist_category)
}
