#TCEFORM {
#	pages {
#	    title.label = Article title
#	    subtitle.label = Sub note (event date, location etc...)
#	    abstract.label = Teaser (showin in news list)

#		tx_realurl_pathoverride.disabled = 1
#		tx_advancedtitle_absolute.disabled = 1
#		tx_advancedtitle_prefix.disabled = 1
#		tx_advancedtitle_sufix.disabled = 1
#		content_from_pid.disabled = 1
#		target.disabled = 0
#		cache_timeout.disabled = 1
#		cache_tags.disabled = 1
#		no_cache.disabled = 1
#		is_siteroot.disabled = 1
#		no_search.disabled = 0
#		php_tree_stop.disabled = 1
#		module.disabled = 1
#		categories.disabled = 0
#		media.disabled = 0
#		tx_metaplus_favicon.disabled = 1
#	}
#}

TCAdefaults.pages {
	tx_pagelist_datetime = now
}

TCEFORM.pages {
	title.types.136.label = Title:
	title.types.137.label = Title:
	abstract.types.136.label = Teaser:
	abstract.types.137.label = Teaser:
	tx_pagelist_images.types.136.label = List image:
	tx_pagelist_images.types.137.label = List image:
	nav_title.types.136.disabled = 1
	nav_title.types.137.disabled = 1
	tx_realurl_pathsegment.types.136.disabled = 1
	tx_realurl_pathsegment.types.137.disabled = 1
	tx_realurl_exclude.types.136.disabled = 1
	tx_realurl_exclude.types.137.disabled = 1
}

TCEFORM.tt_content.tx_pagelist_template.addItems {
	0 = Image and title as cards
	1 = Articles list (title, date, teaser)
	2 = Articles cards (image, title, date)
	3 = Events list (title, dates, teaser, location)
	4 = Events cards (image, title, dates, location)
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
