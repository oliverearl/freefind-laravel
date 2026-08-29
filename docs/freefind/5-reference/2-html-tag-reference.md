# HTML Tag Reference -- FreeFind.com

HTML Tag Reference

All of the tags that FreeFind currently supports are documented here. This includes both special tags and also other tags, like meta tags, which the spider (indexer) processes.

Search Results

[`<!-- FreeFind Keywords Words="keywords here" Count="5" -->`](#fkwc)

**Scope:** Page  
**Description:** Controls the "ranking" of a page by including the given words in the index and weighting them as if they occurred "count" number of times in the page. This tag can also be used to specify additional keywords for a page when you don't want to modify (or have) a standard keywords meta tag.  
**See also:** [`<meta name=keywords content="keywords here">`](https://freefind.com/library/ref/tagref/#mk)

From [HTML Tag Reference](https://freefind.com/library/ref/tagref/index.html)

[`<meta name=description content="the page description">`](#md)

**Scope:** Page  
**Description:** The standard page description meta tag. It specifies the description of the page when shown in search results. If duplicate page descriptions are detected they are not used, since many people use them to describe their _site_ instead of their _pages_.

From [HTML Tag Reference](https://freefind.com/library/ref/tagref/index.html)

[`<title>`](#t)

**Scope:** Page  
**Description:** The standard page title tag. This specifies the title of the page when show in search results.

From [HTML Tag Reference](https://freefind.com/library/ref/tagref/index.html)

Document date

[`<meta name="document-date" content="06 Nov 2004 14:49:37 GMT">`](#ddate)

**Scope:** Page  
**Description:** Normally FreeFind users the HTTP last-modified header to determine a document's date. However many servers do not send a last-modified header or send the wrong date in that header. The document date meta tag can be used to override the server's last-modified date. Use this meta tag to tell FreeFind what date you want associated with the document.

The content field of this meta tag should contain the document date in the format:

      06 Nov 2004 14:49:37 GMT-8

Note that you must include the time zone relative to GMT or you may not get the expected results.

From [HTML Tag Reference](https://freefind.com/library/ref/tagref/index.html)

What Gets Indexed

These tags control which words on a page are included in the index. For information on how to exclude pages from the index, see [_How to Exclude Pages from Search_](https://freefind.com/library/howto/exclude/).

[`<!-- FreeFind Begin No Index -->`](#fbni)

**Scope:** Sub-page  
**Description:** Begins an area of the page whose words will not be included in the index. The following of links by the spider is not affected by this tag.

**Related Tag:** [`<!-- FreeFind End No Index -->`](https://freefind.com/library/ref/tagref/#feni)

From [HTML Tag Reference](https://freefind.com/library/ref/tagref/index.html)

[`<!-- FreeFind End No Index -->`](#feni)

**Scope:** Sub-page  
**Description:** Ends an area of the page whose words will not be included in the index. The following of links by the spider is not affected by this tag.

**Related Tag:** [`<!-- FreeFind Begin No Index -->`](https://freefind.com/library/ref/tagref/#fbni)

From [HTML Tag Reference](https://freefind.com/library/ref/tagref/index.html)

[`<meta name=keywords content="keywords here">`](#mk)

**Scope:** Page  
**Description:** This is the standard keywords meta tag. By default, the words in this tag will be included in the index.  
**See also:** [`<!-- FreeFind Keywords Words="keywords here" Count="5" -->`](https://freefind.com/library/ref/tagref/#fkwc)

From [HTML Tag Reference](https://freefind.com/library/ref/tagref/index.html)

[`<!-- FreeFind No Index Page -->`](#fnip)

**Scope:** Page  
**Description:** Prevents the page from being included in the index, but allows the spider to follow any links it finds. It is generally preferable to use the Control Center to exclude the page from the index, and is always preferable if you don't want the links followed or don't care if they are not followed. For more information see [_How to Exclude Pages_](https://freefind.com/library/howto/exclude/).

From [HTML Tag Reference](https://freefind.com/library/ref/tagref/index.html)

Which Links the Spider Follows

[`<meta name=FreeFind content="stripQueries">`](#mfs)

**Scope:** Global  
**Description:** This tag makes the spider remove the query string from links before following each link. A query string is the part of the link after the `'?'` character (if any). For example, the link:

				https://example.com/cgi-bin/doit.cgi?this=that
			

has the query string:

				?this=that
			

By default the spider will not remove the query string before following a link. The spider never follows URLs that appear in "action" part of a form tag.  
**See also:** [`<meta name=FreeFind content="noFollowQueries">`](https://freefind.com/library/ref/tagref/#mfn)

From [HTML Tag Reference](https://freefind.com/library/ref/tagref/index.html)

[`<meta name=FreeFind content="noFollowQueries">`](#mfn)

**Scope:** Global  
**Description:** This tag prevents the spider from following links which contain query strings. A query string is the part of the link after the `'?'` character (if any). For example, the link:

				http://example.com/cgi-bin/doit.cgi?this=that
			

has the query string:

				?this=that
			

By default the spider will follow all links, regardless of whether they contain query strings are not. The spider never follows URLs that appear in "action" part of a form tag.  
**See also:** [`<meta name=FreeFind content="stripQueries">`](https://freefind.com/library/ref/tagref/#mfs)

From [HTML Tag Reference](https://freefind.com/library/ref/tagref/index.html)

[`<meta name=FreeFind content="neverFollowScript">`](#mfneverfollowscript)

**Scope:** Global  
**Description:** Use this tag to turn off javascript link extraction for all pages. By default the spider will attempt to extract links embedded javascript, though it cannot extract links that are built up from parts.  
**See also:** [`<meta name=FreeFind content="followScript, noFollowScript">`](https://freefind.com/library/ref/tagref/#mffollowscript)

From [HTML Tag Reference](https://freefind.com/library/ref/tagref/index.html)

[`<meta name=FreeFind content="noRobotsTag">`](#mfnorobotstag)

**Scope:** Global  
**Description:** This tag makes the spider ignore the standard robots meta tag. Use this tag if you do not want your robots meta tags to apply to your site search engine.

From [HTML Tag Reference](https://freefind.com/library/ref/tagref/index.html)

[`<meta name=FreeFind content="followScript, noFollowScript">`](#mffollowscript)

**Scope:** Page  
**Description:** Use this tag to turn javascript link extraction off or on for a single page. To turn it off set `content="noFollowScript"` and to turn it on set `content="followScript"`. By default the spider will attempt to extract links embedded javascript, though it cannot extract links that are built up from parts.  
**See also:** [`<meta name=FreeFind content="neverFollowScript">`](https://freefind.com/library/ref/tagref/#mfneverfollowscript)

From [HTML Tag Reference](https://freefind.com/library/ref/tagref/index.html)

[`<meta name=FreeFind content="follow, noFollow">`](#mffollow)

**Scope:** Page  
**Description:** This tag is similar to the standard robots meta tag, but it only controls whether links are followed and only this search engine uses it. You can place it after a robots meta tag to override it. (If you want all your standard robots meta tags to be ignored use the [`noRobotsTag`](https://freefind.com/library/ref/tagref/#mfnorobotstag).) To prevent links in the page from being followed set `content="noFollow"`, and to allow them to be followed set `content="follow"`.  
**See also:** [`<meta name=robots content="all, none, follow, noFollow">`](https://freefind.com/library/ref/tagref/#mranfn)

From [HTML Tag Reference](https://freefind.com/library/ref/tagref/index.html)

[`<meta name=robots content="all, none, follow, noFollow">`](#mranfn)

**Scope:** Page  
**Description:** This is the standard robots meta tag. By default, the spider will honor the follow/nofollow part of this tag _but it ignores the index/noindex part_. To prevent links in the page from being followed set `content="noFollow"`, and to allow them to be followed set `content="follow"`.  
**See also:** [`<meta name=FreeFind content="follow, noFollow">`](https://freefind.com/library/ref/tagref/#mffollow)

From [HTML Tag Reference](https://freefind.com/library/ref/tagref/index.html)

[`<!-- FreeFind nofollow -->`](#ffnofollow)

**Scope:** Sub-page  
**Description:** Use this tag to prevent links in _part_ of a page from being followed. You bracket the link(s) to ignore using the start and end versions of this tag. For example:

				<!-- FreeFind nofollow -->
				...link(s) to ignore here...
				<!-- FreeFind end nofollow -->
			

From [HTML Tag Reference](https://freefind.com/library/ref/tagref/index.html)

[`<!-- FreeFind end nofollow -->`](#ffendnofollow)

**Scope:** Sub-page  
**Description:** Use this tag to prevent links in _part_ of a page from being followed. You bracket the link(s) to ignore using the start and end versions of this tag. For example:

				<!-- FreeFind nofollow -->
				...link(s) to ignore here...
				<!-- FreeFind end nofollow -->
			

  
**See also:** [`<!-- FreeFind nofollow -->`](https://freefind.com/library/ref/tagref/#ffnofollow)

From [HTML Tag Reference](https://freefind.com/library/ref/tagref/index.html)

[`<nofollow>`](#nofollow)

**Deprecated:** Do not use in new code  
See instead: [`<!-- FreeFind nofollow -->`](https://freefind.com/library/ref/tagref/#ffnofollow)  
**Scope:** Sub-page  
**Description:** Use this tag to prevent links in _part_ of a page from being followed. You bracket the link(s) to ignore using the start end version of this tag. For example:

				<nofollow>
				...link(s) to ignore here...
				</nofollow>
			

  
**See also:** [`<!-- FreeFind nofollow -->`](https://freefind.com/library/ref/tagref/#ffnofollow)

From [HTML Tag Reference](https://freefind.com/library/ref/tagref/index.html)

[`<nofollowscript>`](#nofollowscript)

**Deprecated:** Do not use in new code  
**Scope:** Sub-page  
**Description:** Use this tag to prevent javascript link extraction in _part_ of a page. You bracket the javascript to ignore using the start end version of this tag. For example:

				<nofollowscript>
				  <script>
				  ...link(s) to ignore here...
				  </script>
				</nofollowscript>
			

  
**See also:** [`<!-- FreeFind nofollow -->`](https://freefind.com/library/ref/tagref/#ffnofollow)

From [HTML Tag Reference](https://freefind.com/library/ref/tagref/index.html)

[`<!-- FreeFind Links "http://example.com/yourdoc1.html" -->`](#fl)

**Scope:** Page  
**Description:** Use this tag to explicitly specify one or more pages in your web site that the search engine spider failed to locate. Normally the spider will find all of the pages in your web site. Occasionally, though, there is a page that it cannot find (for instance, when the URL is built up out of bits of text in some javascript). In these cases this tag allows you to explicitly specify the page so that FreeFind can locate it. To specify multiple pages, simple list them within the tag:

			  <!-- FreeFind Links "http://example.com/yourdoc1.html"
						"http://example.com/yourdoc2.html"
						"http://example.com/yourdoc3.html"
			      -->
			

Note: The specified page(s) must be in your web site. You cannot use this tag to "jump" to pages served from a different domain or directory tree unless a page in that site has been specified as an additional starting point. To specify an additional starting point, log in to [the Control Center](https://freefind.com/control.html) then go to the ![build index page](../assets/tabbuildindex.gif) page and use the set starting points link.

From [HTML Tag Reference](https://freefind.com/library/ref/tagref/index.html)

[`<!-- FreeFind No Parameters -->`](#fnp)

**Scope:** Global  
**Description:** Old tag. Use the [stripQueries meta tag](#mfs) in preference to this one. This tag makes the spider remove the query string from links before following each link.  
**See also:** [`<meta name=FreeFind content="stripQueries">`](https://freefind.com/library/ref/tagref/#mfs)

From [HTML Tag Reference](https://freefind.com/library/ref/tagref/index.html)

The Site Map

[`<!-- FreeFind No Map -->`](#fnm)

**Scope:** Page  
**Description:** This tag prevents a page from being included in your site map. It may be placed anywhere in the page. Note that unless the sub-pages are linked into your site someplace else, those pages will also be removed from the site map.

From [HTML Tag Reference](https://freefind.com/library/ref/tagref/index.html)

[`<!-- FreeFind Map Title="Title of this page in site map" -->`](#fmt)

**Scope:** Page  
**Description:** This tag sets the title of this page as it will appear in your site map and what's new listing. This does not affect the page title shown in the search results. It may be placed anywhere in the page.

From [HTML Tag Reference](https://freefind.com/library/ref/tagref/index.html)

The What's New Listing

[`<!-- FreeFind Not New -->`](#fnn)

**Scope:** Page  
**Description:** This tag prevents a page from being included in your what's new page. It may be placed anywhere in the page.

From [HTML Tag Reference](https://freefind.com/library/ref/tagref/index.html)

[`<!-- FreeFind New Date="May 5 2004" Icon="http://example.com/new.gif" Comment="All new!" -->`](#fndic)

**Scope:** Page  
**Description:** This tag controls how a page entry will appear in your what's new page. It may be placed anywhere in the page.

The `"Date"` attribute is the date that the page was last modified. If no date is provided the `"last-modified"` date as provided by your web page server will be used. If your server does not provide the that information the page will not be included in the what's new listing.

The `"Icon"` attribute specifies which image to display next to the what's new entry for this page. Specify the complete URL of the image to display, including the leading `"http://"`. If no icon attribute is provided, no image is displayed next to the entry for this page.

The `"Comment"` attribute provides text to display for this page's entry. Typically you would describe what is new or updated on the page.

There is no need to specify all of the attributes of this tag. You may supply any mix-and-match of `Date`, `Icon`, or `Comment`.

If more than one of these tags is present the attributes are combined, using the latest instance of each one. For example:

			  <!-- FreeFind New Date="May 5 2004" -->
			  <!-- FreeFind New Icon="http://example.com/new.gif" -->
			  <!-- FreeFind New Comment="All new!" -->
			  <!-- FreeFind New Date="May 7 2004" -->
			  <!-- FreeFind New Comment="Changed this and that." -->
			

is equivalent to:

			  <!-- FreeFind New Date="May 7 2004"
			  Icon="http://example.com/new.gif"
			  Comment="Changed this and that." -->
			

From [HTML Tag Reference](https://freefind.com/library/ref/tagref/index.html)

DataSearch

The tags in this section allow you to make the search engine operate in data search mode, and control which parts of your web pages are considered "items" to index.

[`<!-- FreeFind Index Listings Only id="YourSiteID" -->`](#filoi)

**Scope:** Global  
**Description:** This tag informs the search engine that you want to use the Data Search feature, not the regular page search. It must appear at the top of the first page that the spider examines - the one specified as the account's web site address.

_Note:_ You must replace `"YourSiteID"` with your actual site ID (provided in your signup email).  
**See also:** [`<!-- FreeFind Listing -->`](https://freefind.com/library/ref/tagref/#flisting)

From [HTML Tag Reference](https://freefind.com/library/ref/tagref/index.html)

[`<!-- FreeFind Listing -->`](#flisting)

**Scope:** Sub-page  
**Description:** Begins a list "item" to be included in the index. All the HTML appearing between this tag and the "end listing" tag will be considered one data "item". All of the addresses within the item _must_ start with `"http://..."` or they will not work when the item is displayed in the search results.  
**Related Tag:** [`<!-- FreeFind End Listing -->`](https://freefind.com/library/ref/tagref/#fel)  
**See also:** [`<!-- FreeFind Index Listings Only id="YourSiteID" -->`](https://freefind.com/library/ref/tagref/#filoi)

From [HTML Tag Reference](https://freefind.com/library/ref/tagref/index.html)

[`<!-- FreeFind End Listing -->`](#fel)

**Scope:** Sub-page  
**Description:** Ends a list "item" to be included in the index. All the HTML appearing between the "listing" tag and this tag will be considered one data "item". All of the addresses within the item _must_ start with `"http://..."` or `"https://..."` in order to work when the item is displayed in the search results.  
**Related Tag:** [`<!-- FreeFind Listing -->`](https://freefind.com/library/ref/tagref/#flisting)  
**See also:** [`<!-- FreeFind Index Listings Only id="YourSiteID" -->`](https://freefind.com/library/ref/tagref/#filoi)

From [HTML Tag Reference](https://freefind.com/library/ref/tagref/index.html)

[`<!-- FreeFind Category value='Category Name And HREF' -->`](#fcv)

**Scope:** Page  
**Description:** This tag is typically used to specify a category for all the listings on a given page. It is placed once at the top of the page, and applies to all of the items on that page. The value part of the tag may be any valid HTML. Single quotes `(')` enclose the value field instead of double quotes `(")`, and all addresses must start with `"http://..."` or `"https://..."` in order to work when the HTML is displayed in the search results.

In the search results each item begins with the HTML specified in this tag. It is usually used to describe and link to a page of similar items. Here's an example of a "sites that begin with F" category:

			  <!-- FreeFind Category value='<small>Category: <a
			  href="https://example.com/dsdemo/index.html">
			  F sites</a></small>' -->
			

  
**See also:** [`<!-- FreeFind Index Listings Only id="YourSiteID" -->`](https://freefind.com/library/ref/tagref/#filoi)

From [HTML Tag Reference](https://freefind.com/library/ref/tagref/index.html)
