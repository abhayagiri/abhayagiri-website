

//Api routes

SUBPAGES
--------
/subpages/:slug
page_title
page_body

PAGES
------
/pages/:page_slug
page_title
page_body
page_icon
banner_url
subpages

TALKS
------
*add genre field # TODO
*add author field as object 

**routes**
/api/talks
?author=[Int(ref: Author.id)]
?startDate=Unix Timestamp
?endDate=Unix Timestamp
?genre=[Int(ref: Genre.id)] # TODO
?page=Int
?pageSize=Int 
?filter=String


Authors
---------
**fields**
image
description
rank

**routes**
/api/author/
?filter=


Genre
-----
**fields**
id
title
image
rank

**routes**
/api/genre/
?filter=
?sortBy=


