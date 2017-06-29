

//Api routes

PAGES
------
/pages/:route
page_title
page_body
page_icon
banner_url
subpages

TALKS
------
*add genre field 
*add author field as object 

**routes**
/api/talks/search
?author=[Int(ref: Author.id)]
?startDate=Unix Timestamp
?endDate=Unix Timestamp
?genre=[Int(ref: Genre.id)]
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


