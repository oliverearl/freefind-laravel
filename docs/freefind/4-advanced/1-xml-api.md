# XML Reference -- FreeFind.com

XML Reference

FreeFind subscribers may use our XML feed to get site search results directly from our servers in a format suitable for further programatic processing. Typically, it will be accessed from your web server or from the pages of your website via javascript.

The feed may only be accessed in response to a user entering a search term and may not be accessed for robotically generated queries.

The feed may only be accessed by subscription accounts.

Initiating API Requests

Requests to the FreeFind XML API are initiated by sending a GET request to:

		http://search.freefind.com/find.xml
	

or

		https://search.freefind.com/find.xml
	

and including a query string with the request parameters.

For example:

		https://search.freefind.com/find.xml?si=YOUR\_SITE\_ID&query=words+to+find
	

Only subscription accounts may use the XML feed, and only regular site search results are available.

The feed can be used by your server or can be used directly from your javascript code in your website's pages.

**Important Note:** The feed may only be accessed in response to a user entering a search term and may not be accessed for robotically generated queries.

Request Parameter Summary

All request parameters must be application/x-www-form-urlencoded.

Required Search Request Parameters

[si](#_si)

site ID (account ID)

and at least one of:

[query](#_query)  
[q1](#_q1)  
[q2](#_q2)  
[q3](#_q3)  
[q4](#_q4)

user-entered query. matching documents must match the query.  
matches must contain all these words _(ignored if <query> used)_  
matches must contain this phrase _(ignored if <query> used)_  
matches must contain any of these words _(ignored if <query> used)_  
matches must not contain any of these words _(ignored if <query> used)_

Optional Search Request Parameters

[asen](#_asen)  
[csen](#_csen)  
[dl](#_dl)  
[dtd](#_dtd)  
[fr](#_fr)  
[mode](#_mode)  
[rpp](#_rpp)  
[oq](#_oq)  
[s](#_s)  
[search](#_search)  
[srt](#_srt)  
[stm](#_stm)  
[xslt](#_xslt)  

accent sensitivity in matches  
case sensitivity in matches  
description text length  
send DTD (document type definition) with XML output _(new)_  
0-based index of first returned result  
matches must contain ANY or ALL query words _(only valid with <query>)_  
results per page  
old query for "refine" type search _(required if search=these)_  
subsection selection  
type of search: "new", "refine" or "web"  
result sorting  
stemming (looser word matching) options  
send XSLT statement with XML _(new)_

Other Search Request Parameters

[ics](#_ics)  
[id](#_id)  

input character set _(ignored for XML requests)_  
site ID (account ID) _(deprecated. see "si")_  

Response DTD

This DTD outlines the structure of the XML response.

	<!DOCTYPE ret \[
	  <!ELEMENT [ret](#ret) ([sts](#sts), [msg](#msg)?, [srch](#srch)?) >
	    <!ELEMENT [sts](#sts) (#PCDATA)>
	    <!ELEMENT [msg](#msg) (#PCDATA)>
	    <!ELEMENT [srch](#srch) ([nttl](#nttl), [nret](#nret), [idx](#idx), [q](#q), [qe](#qe), [spell](#spell)?, [spelle](#ret)?,
	                    [spelll](#spelll)?, [ss](#ss)?, [pl](#pl)?, [nl](#nl)?, [aor](#aor)?, [items](#items)?)>
	      <!ELEMENT [nttl](#nttl) (#PCDATA)>
	      <!ELEMENT [nret](#nret) (#PCDATA)>
	      <!ELEMENT [idx](#idx) (#PCDATA)>
	      <!ELEMENT [q](#q) (#PCDATA)>
	      <!ELEMENT [qe](#qe) (#PCDATA)>
	      <!ELEMENT [spell](#spell) (#PCDATA)>
	      <!ELEMENT [spelle](#spelle) (#PCDATA)>
	      <!ELEMENT [spelll](#spelll) (#PCDATA)>
	      <!ELEMENT [ss](#ss) (s)\*>
	        <!ELEMENT [s](#s) (#PCDATA)>
	      <!ELEMENT [pl](#pl) (#PCDATA)>
	      <!ELEMENT [nl](#nl) (#PCDATA)>
	      <!ELEMENT [aor](#aor) (#PCDATA)>
	      <!ELEMENT [items](#items) ([i](#i))\* >
	        <!ELEMENT [i](#i) ([n](#n)?, [t](#t)?, [d](#d)?, [u](#u), [tg](#tg)?, [du](#du), [dt](#dt)?)>
	          <!ELEMENT [n](#n) (#PCDATA)>
	          <!ELEMENT [t](#t) (#PCDATA)>
	          <!ELEMENT [d](#d) (#PCDATA)>
	          <!ELEMENT [u](#u) (#PCDATA)>
	          <!ELEMENT [tg](#tg) (#PCDATA)>
	          <!ELEMENT [du](#du) (#PCDATA)>
	          <!ELEMENT [dt](#dt) (#PCDATA)>
	\]>
	

Response Tag Summary

Response Tags

[<aor>](#aor)  
[<d>](#d)  
[<dt>](#dt)  
[<du>](#du)  
[<i>](#i)  
[<idx>](#idx)  
[<items>](#items)  
[<msg>](#msg)  
[<n>](#n)  
[<nl>](#nl)  
[<nret>](#nret)  
[<nttl>](#nttl)  
[<pl>](#pl)  
[<q>](#q)  
[<qe>](#qe)  
[<ret>](#ret)  
[<s>](#s)  
[<spell>](#spell)  
[<spelle>](#spelle)  
[<spelll>](#spelll)  
[<srch>](#srch)  
[<ss>](#ss)  
[<sts>](#sts)  
[<t>](#t)  
[<tg>](#tg)  
[<u>](#u)

mode  
description  
date  
URL  
single matching item  
0-based index of first item returned in this response  
of matching items  
message (for status code != 0)  
item number (1 based)  
next link  
number of matches returned in this result  
number of matches found  
previous link  
user query  
user query, encoded  
returned response object  
name of section(s) being searched  
spelling suggestion query  
spelling suggestion query, URL encoded  
link to run spelling suggestion query  
returned search results  
list of sections being searched  
status (error) code  
title  
link target  
click URL

Required Request Parameters

**Parameter:** `si`  
**For:** Account ID (site ID)  
**Type:** String  
**Value:** Assigned when user creates account  

**Parameter:** `query`  
**For:** User-entered query  
**Type:** String  
**Value:** <user provided words>  
**Notes:** Only one query value per request allowed.  
Simple word list or boolean expression. See search tips for query syntax details.  

**Parameter:** `q1`  
**For:** User-entered query. Result matches must contain _all_ of these words  
**Type:** String  
**Value:** <user provided words>  
**Notes:** Ignored if `query` parameter used  

**Parameter:** `q2`  
**For:** User-entered query. Result matches must contain all of these words as a _phrase_  
**Type:** String  
**Value:** <user provided words>  
**Notes:** Ignored if `query` parameter used  

**Parameter:** `q3`  
**For:** User-entered query. Result matches must contain _at least one_ of these words  
**Type:** String  
**Value:** <user provided words>  
**Notes:** Ignored if `query` parameter used  

**Parameter:** `q4`  
**For:** User-entered query. Result matches must _not_ contain _any_ of these words  
**Type:** String  
**Value:** <user provided words>  
**Notes:** Ignored if `query` parameter used  

Optional Request Parameters

**Parameter:** `asen`  
**For:** Accent sensitivity in matches  
**Type:** String: "y" or "n"  
**Value:** "n" accents are ignored, "y" accents in document must match query  
**Default:** "n"  
**Notes:** Optional  

**Parameter:** `csen`  
**For:** Case sensitivity in matches  
**Type:** String: "y" or "n"  
**Value:** "n" case is ignored, "y" case in document must match query  
**Default:** "n"  
**Notes:** Optional  

**Parameter:** `dl`  
**For:** Description text length  
**Type:** String: "m", "s" or "l"  
**Value:** "m" normal, "s" short, "l" long  
**Default:** "m"  
**Notes:** Optional  

**Parameter:** `dtd`  
**For:** Send DTD (document type definition) with XML output  
**Type:** String: "y" or "n"  
**Value:** "n" do not include DTD with XML results, "y" include DTD  
**Default:** "n"  
**Notes:** Optional  
DTD stands for "document type definition", part of the XML standard  

**Parameter:** `fr`  
**For:** 0-based index of first returned result  
**Type:** Integer  
**Value:** Any non-negative number  
**Default:** 0  
**Notes:** Optional  

**Parameter:** `mode`  
**For:** Matches must contain ANY or ALL query words  
**Type:** String: "any" or "all"  
**Value:** "any" matches must contain any query words, "all" matches must contain all query words  
**Default:** "all"  
**Notes:** Optional  
Only used if `query` field contains data  

**Parameter:** `rpp`  
**For:** Results per page  
**Type:** Integer  
**Value:** 1 - 25  
**Default:** 10  
**Notes:** Optional  
Only valid for site search results  

**Parameter:** `oq`  
**For:** Old query for "refine" type search  
**Type:** String  
**Value:** <user's previous query>  
**Default:**  
**Notes:** Optional  
Required if `search=these` (ie "refining" search results)  

**Parameter:** `s`  
**For:** Subsection selection  
**Type:** String  
**Value:** "site" search entire site, "<subsection name>" search specific subsection  
**Default:** "site"  
**Notes:** Optional  
May use multiple times in one request to search multiple subsections  

**Parameter:** `search`  
**For:** Type of search: "new", "refine" or "web"  
**Type:** String: "new", "these" or "web"  
**Value:** "new" for entirely new search, "these" to search within current results or "web" to search the web  
**Default:** "new"  
**Notes:** Optional  
When refining search ("these"), both `query` and `oq` fields are used and required  

**Parameter:** `srt`  
**For:** Result sorting  
**Type:** String: "r", "d"  
**Value:** "r" sort by relevance, "d" sort by date  
**Default:** "r"  
**Notes:** Optional  
See date sorting info in control center before using date sorting  

**Parameter:** `stm`  
**For:** Stemming (looser word matching) options  
**Type:** String, "", "n", "en", "pt"  
**Value:** "" (empty string) auto, "n" none, "en" English, "pt" Portuguese  
**Default: ""**  
**Notes:** Optional  

**Parameter:** `xslt`  
**For:** Send XSLT statement with returned XML _(new)_  
**Type:** String URL  
**Value:** URL of XSLT document (fully specified, http://....)  
**Default:**  
**Notes:** Optional  
Value must be URL encoded (percent-encoded)  

Deprecated Request Parameters

These parameters still work, but have been replaced with newer versions for new code.

**Parameter:** `ics`  
**For:** Input character set _(ignored for XML requests)_  
**Type:** String  
**Value:** "1" for UTF-8, or a character set name  
**Default:** 1  
**Notes:** The query is expected to be encoded in the given character set (then url encoded)  
Only applicable for HTML results. Not relevant for XML results.  

**Parameter:** `id`  
**For:** Account ID (site ID)  
**Type:** String  
**Value:** Assigned when user creates account  
**Notes:** Deprecated. See [`si`](#_si)  

Response Tags

This section lists all possible tags that may be part of an XML response.

**Tag:** `<aor>`  
**For:** Indicates auto-or mode occurred  
**Type:** Boolean, "0" or "1"  
**Value:** 0 or 1 or not present (0)  
**Default:** 0  
**Notes:** Only included in returned result when search engine enters auto-or mode  

**Tag:** `<d>`  
**For:** Result item description  
**Type:** HTML  
**Notes:** The description consists of illustrative excerpts extracted from the matching document  

**Tag:** `<dt>`  
**For:** Result item date  
**Type:** String  
**Notes:** "2006-03-03 22:56:49 GMT", for example  

**Tag:** `<du>`  
**For:** Display URL  
**Type:** HTML  
**Notes:** This is a shortened version of the click URL intended for display to the user  

**Tag:** `<i>`  
**For:** Contains single matching result item  
**Type:** XML container tag  
**Value:** `[n](#n)? [t](#t)? [d](#d)? [u](#u) [tg](#tg)? [du](#du) [date](#date)?`  

**Tag:** `<idx>`  
**For:** 0-based index of first item returned in this response  
**Type:** Integer  

**Tag:** `<items>`  
**For:** List of matching result items  
**Type:** XML container tag  
**Value:** `([i](#i))*`  
**Notes:**  

**Tag:** `<msg>`  
**For:** Error message (when status code != 0)  
**Type:** Text  
**Notes:** If status code == 0, this field is not included in the response  

**Tag:** `<n>`  
**For:** Result item number  
**Type:** Integer  
**Notes:** 1-based. Optional  

**Tag:** `<nl>`  
**For:** "Next" link  
**Type:** HTML  
**Notes:** Only included when there is another page of results available  

**Tag:** `<nret>`  
**For:** The total number of matches returned in this response  
**Type:** Integer  

**Tag:** `<nttl>`  
**For:** The total number of matches found  
**Type:** Integer  

**Tag:** `<pl>`  
**For:** "Previous" link  
**Type:** HTML  
**Notes:** Only included when there is a previous page of results available  

**Tag:** `<q>`  
**For:** User query  
**Type:** HTML  

**Tag:** `<qe>`  
**For:** URL encoded user query  
**Type:** HTML  
**Notes:** URL encoded (percent-encoded)  

**Tag:** `<ret>`  
**For:** Top level returned response object  
**Type:** XML container tag  
**Value:** `[sts](#sts) [msg](#msg)? [srch](#srch)`  

**Tag:** `<s>`  
**For:** Name of section being searched  
**Type:** Text  

**Tag:** `<spell>`  
**For:** Spelling suggestion query  
**Type:** HTML  
**Notes:** Optional query suggestion value. Would replace query value in its entirety.  

**Tag:** `<spelle>`  
**For:** Spelling suggestion query, URL encoded  
**Type:** HTML  
**Notes:** Optional URL encoded query suggestion value. Would replace query value in its entirety.  

**Tag:** `<spelll>`  
**For:** Optional link to run spelling suggestion query  
**Type:** HTML  

**Tag:** `<srch>`  
**For:** Returned search results  
**Type:** XML container tag  
**Value:** `[nttl](#nttl) [nret](#nret) [idx](#idx) [q](#q) [spell](#spell)? [ss](#ss)? [aor](#aor)? [items](#items)?`  

**Tag:** `<ss>`  
**For:** List of sections being searched  
**Type:** XML container tag  
**Value:** `([s](#s))*`  

**Tag:** `<sts>`  
**For:** Status (error) code  
**Type:** Number  
**Value:** 0 = no error, results returned  
1 = other error (more details in msg)  
2 = unauthorized access error  
3 = account closed (or account ID invalid) error (details in msg)  
4 = invalid parameter(s) in request (details in msg)  

**Tag:** `<t>`  
**For:** Title of a result item  
**Type:** HTML  

**Tag:** `<tg>`  
**For:** Result item link target  
**Type:** HTML  
**Notes:** "", "\_blank", "myframe", etc. Used with `[u](#u)`  

**Tag:** `<u>`  
**For:** Result item click URL  
**Type:** HTML  
**Notes:** Target for link, if any, is in `[tg](#tg)` tag  

Example

Request:

	https://search.freefind.com/find.xml?si=3225682&rpp=3&query=exlude+part+of+page

Response:

<?xml version="1.0" encoding="UTF-8" standalone="yes" ?> <!-- Copyright (c) FreeFind.com - All Rights Reserved --> <ret> <sts>0</sts> <srch> <nttl>67</nttl> <nret>3</nret> <idx>0</idx> <q>exlude part of page</q> <qe>exlude+part+of+page</qe> <spell>exclude part of page</spell> <spelle>exclude+part+of+page</spelle> <spelll>https://search.freefind.com/find.xml?pageid=r&id=3225682&query=exclude+part+of+page&ics=1&rpp=3&fr=0</spelll> <nl>https://search.freefind.com/find.xml?pageid=r&id=3225682&query=exlude+part+of+page&ics=1&rpp=3&fr=3</nl> <aor>1</aor> <items> <!-- search results copyright FreeFind.com. All rights reserved. Results may not be re-used, meta searched, or searched robotically --%gt; <i> <n>1</n> <t>How to Exclude <b>Pages</b> -- FreeFind.com</t> <d>If you want to prevent only <b>part</b> <b>of</b> a <b>page</b> from being indexed ... not be used. Excluding <b>Part</b> <b>of</b> a <b>Page</b> (top ) You can use ... engine tags to prevent <b>part</b> <b>of</b> a <b>page</b> from being indexed. If</d> <u>http://www.freefind.com/library/howto/exclude/</u> <tg></tg> <du>www.freefind.com/library/howto/exclude/</du> <dt></dt> </i> <i> <n>2</n> <t>Relevance Scoring Reference -- FreeFind.com</t> <d>Text relevance settings adjust which <b>parts</b> <b>of</b> your <b>page</b> get ... can prevent <b>parts</b> <b>of</b> your <b>page</b> from being ... each <b>part</b> <b>of</b> your HTML <b>page</b>. Select ignore if a <b>part</b> <b>of</b> the <b>page</b> should</d> <u>http://www.freefind.com/library/ref/relref/</u> <tg></tg> <du>www.freefind.com/library/ref/relref/</du> <dt></dt> </i> <i> <n>3</n> <t>HTML Tag Reference -- FreeFind.com</t> <d>Controls the "ranking" <b>of</b> a <b>page</b> by including ... to prevent links in <b>part</b> <b>of</b> a <b>page</b> from being followed. You ... javascript link extraction in <b>part</b> <b>of</b> a <b>page</b>. You bracket the javascript</d> <u>http://www.freefind.com/library/ref/tagref/</u> <tg></tg> <du>www.freefind.com/library/ref/tagref/</du> <dt></dt> </i> </items> </srch> </ret>

Parameter and Tag Index

XML Request Parameters

`[asen](#_asen)`  `[csen](#_csen)`  `[dl](#_dl)`  `[dtd](#_dtd)`  `[fr](#_fr)`  `[ics](#_ics)`  `[id](#_id)`  `[mode](#_mode)`  `[oq](#_oq)`  `[q1](#_q1)`  `[q2](#_q2)`  `[q3](#_q3)`  `[q4](#_q4)`  `[query](#_query)`  `[rpp](#_rpp)`  `[s](#_s)`  `[search](#_search)`  `[si](#_si)`  `[srt](#_srt)`  `[stm](#_stm)`  `[xslt](#_xslt)` 

XML Response Tags

`[<aor>](#aor)`  `[<d>](#d)`  `[<dt>](#dt)`  `[<du>](#du)`  `[<i>](#i)`  `[<idx>](#idx)`  `[<items>](#items)`  `[<msg>](#msg)`  `[<n>](#n)`  `[<nl>](#nl)`  `[<nret>](#nret)`  `[<nttl>](#nttl)`  `[<pl>](#pl)`  `[<q>](#q)`  `[<qe>](#qe)`  `[<ret>](#ret)`  `[<s>](#s)`  `[<spell>](#spell)`  `[<spelle>](#spelle)`  `[<spelll>](#spelll)`  `[<srch>](#srch)`  `[<ss>](#ss)`  `[<sts>](#sts)`  `[<t>](#t)`  `[<tg>](#tg)`  `[<u>](#u)` 
