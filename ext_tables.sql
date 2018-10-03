CREATE TABLE tt_content (
	tx_pagelist_template int(11) DEFAULT '0' NOT NULL,
	tx_pagelist_orderby tinytext,
	tx_pagelist_disableimages int(1) DEFAULT '0' NOT NULL,
	tx_pagelist_disableabstract int(1) DEFAULT '0' NOT NULL,
	tx_pagelist_startfrom varchar(25),
	tx_pagelist_limit varchar(25),
	tx_pagelist_paginate int(1) DEFAULT '0' NOT NULL,
	tx_pagelist_paginateitems varchar(25),
);

CREATE TABLE pages (
	tx_pagelist_images int(11) unsigned DEFAULT '0',
	tx_pagelist_datetime int(11) unsigned NOT NULL DEFAULT '0',
	tx_pagelist_eventfinish int(11) unsigned NOT NULL DEFAULT '0',
	tx_pagelist_eventlocation tinytext,
	tx_pagelist_eventlocationlink tinytext,
	tx_pagelist_productprice varchar(255),
	tx_pagelist_notinlist int(1) DEFAULT '0' NOT NULL,
	tx_pagelist_authors tinytext,
);

CREATE TABLE pages_language_overlay (
	tx_pagelist_images int(11) unsigned DEFAULT '0',
	tx_pagelist_datetime int(11) unsigned NOT NULL DEFAULT '0',
	tx_pagelist_eventfinish int(11) unsigned NOT NULL DEFAULT '0',
	tx_pagelist_eventlocation tinytext,
	tx_pagelist_eventlocationlink tinytext,
	tx_pagelist_productprice tinytext,
	tx_pagelist_authors tinytext,
);
