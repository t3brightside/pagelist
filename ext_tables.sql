CREATE TABLE tt_content (
	tx_pagelist_template int(11) DEFAULT '0' NOT NULL,
	tx_pagelist_orderby tinytext,
	tx_pagelist_startfrom tinytext,
	tx_pagelist_limit tinytext,
);

CREATE TABLE pages (
  tx_pagelist_images int(11) unsigned DEFAULT '0'
);

CREATE TABLE pages_language_overlay (
  tx_pagelist_images int(11) unsigned DEFAULT '0'
);
