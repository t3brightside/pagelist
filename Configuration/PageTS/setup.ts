
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
	tx_pagelist_datetime.types.137.label = Published at:
}

TCEFORM.tt_content.tx_pagelist_template.addItems {
	0 = Image and title as cards
	1 = Article list (title, date, teaser)
	2 = Article cards (image, title, date, teaser)
	3 = Event list (title, date, location)
	4 = Event cards (image, title, dates, location)
}

mod.wizards.newContentElement.wizardItems.pagelist {
	after = common
	header = Page lists
   elements {
      pagelist_selected {
         iconIdentifier = mimetypes-x-content-pagelist
         title = Pages: selected
         description = Shows selected pages.
         tt_content_defValues {
            CType = pagelist_selected
         }
      }
      pagelist_sub {
         iconIdentifier = mimetypes-x-content-pagelist
         title = Pages: subpages
         description = Shows subpages of selected pages.
         tt_content_defValues {
            CType = pagelist_sub
         }
      }
      pagelist_category {
         iconIdentifier = mimetypes-x-content-pagelist
         title = Pages: category
         description = Shows pages that belong to selected category(s).
         tt_content_defValues {
            CType = pagelist_category
         }
      }
			pagelist_articles_selected {
         iconIdentifier = mimetypes-x-content-pagelist
         title = Articles: selected
         description = Shows selected article pages.
         tt_content_defValues {
            CType = pagelist_articles_selected
         }
      }
      pagelist_articles_sub {
         iconIdentifier = mimetypes-x-content-pagelist
         title = Articles: subpages
         description = Shows article subpages of selected pages.
         tt_content_defValues {
            CType = pagelist_articles_sub
         }
      }
      pagelist_articles_category {
         iconIdentifier = mimetypes-x-content-pagelist
         title = Articles: category
         description = Shows article pages that belong to selected category(s).
         tt_content_defValues {
            CType = pagelist_articles_category
         }
      }
			pagelist_events_selected {
         iconIdentifier = mimetypes-x-content-pagelist
         title = Events: selected
         description = Shows selected event pages.
         tt_content_defValues {
            CType = pagelist_events_selected
         }
      }
      pagelist_events_sub {
         iconIdentifier = mimetypes-x-content-pagelist
         title = Events: subpages
         description = Shows event subpages of selected pages.
         tt_content_defValues {
            CType = pagelist_articles_sub
         }
      }
      pagelist_event_category {
         iconIdentifier = mimetypes-x-content-pagelist
         title = Events: category
         description = Shows event pages that belong to selected category(s).
         tt_content_defValues {
            CType = pagelist_events_category
         }
      }
   }
   show := addToList(pagelist_selected,pagelist_sub,pagelist_category,pagelist_articles_selected,pagelist_articles_sub,pagelist_articles_category,pagelist_events_selected,pagelist_events_sub,pagelist_events_category)
}
