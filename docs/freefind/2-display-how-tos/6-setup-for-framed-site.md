# How to Setup your Framed Site -- FreeFind.com

How to Setup your Framed Site

Despite [serious inherent problems](#inherentproblems) framesets remain a relatively popular feature in website design. This how-to contains various hints for setting up your search engine to work smoothly with your framed site.

This tutorial is not a web/html primer and assumes that you already know how the process of "web surfing" is accomplished (i.e. a browser requests a page from a server which then returns the page to be viewed), what an HTML "form" is and how it works, and what a link "target" is. If you are not familiar with these concepts please read a basic web/html primer.

Overview

Most framed sites just have one frameset, which includes a "menu" frame on the left and a "content" (or "main") frame on the right. Getting the search engine to work with a site like this is fairly easy. The steps involved are:

1.  Target the search panel to make the results appear in your content frame,
2.  Exclude all non-content pages from the index, and
3.  Respider your site.

When these three things are done correctly your search engine will work fine with your singly-framed site.

If you use more than one frameset (it's unusual for a site to do this), then the technique is more complex. You will need to add some javascript to every page which gets indexed to ensure that it has the proper frameset around it. Doing a good job of this is difficult. For more info on this technique, look here: [https://irt.org/articles/js190/index.htm](https://irt.org/articles/js190/index.htm) (_Note:_ The irt.org website is not affiliated with FreeFind.com).

Targeting the Search Panel

Most framed sites will want to place the search panel in their "menu" frame on the left and then target it so that the search results appear in their "content" frame. To do this you will need to target the search panel's `form` tag as well as the regular links in the panel.

First, look for the search panel form tag. It will look something like this:

		<form action="http://search.freefind.com/find.html">
	

To target this so the results show up in the frame "content", modify the tag like this:

		<form action="http://search.freefind.com/find.html" target="content">
	

Now target the links in the search panel. For example change links like this:

		<a href="https://freefind.com//search.freefind.com/find.html?si=...">
	

To ones like this:

		<a href="https://freefind.com//search.freefind.com/find.html?si=..." target="content">
	

The FreeFind home page link should be targeted to "\_top" so that it breaks out of your frames:

		<a href="https://freefind.com/" target="\_top">
	

After targeting your search panel, using it will cause the search results to show up in your "content" frame. Test this, being sure to try the text links in addition to the buttons.

You may also need to make the search panel narrower to fit in your menu frame. Modifying the panel is in this way is fine, just be sure that it still works after you make your changes!

Now that your panel is ready you need to prevent pages which don't belong in your "content" frame from being indexed.

Excluding Non-content Pages from the Index

Most framed sites will just have a few pages which need to be removed from the index. These typically are the home page (with the frameset in it), the menu page, and possible a header and/or footer page.

To remove a page from the index, add the following tag to that page:

		<!-- FreeFind No Index Page -->
	

After updating all non-content pages in this way, re-spider your site.

_Note:_ This tag still allows the spider to find and follow the links on the page, it just prevents the page from being included in the index.

Multi-framed Sites

Setup for sites with more than one frameset is more complex, and because the variety of layouts these sites have we cannot anticipate your site's structure in order to give you a step-by-step procedure.

Most likely you will need to add some javascript to every page which gets indexed to ensure that it has the proper frameset around it. For more info on this technique, look here: [https://irt.org/articles/js190/index.htm](https://irt.org/articles/js190/index.htm) (_Note:_ The irt.org website is not affiliated with FreeFind.com).

In addition, you may also need to target the search panel and prevent certain pages from being indexed.

Inherent Problems with Framed Sites

We have discovered that lots of people don't realize the drawbacks of having a site which uses frames, including many professional web designers. Because of the seriousness of these drawbacks we're taking a moment to let you know of them here.

The fundamental problem is there is no standard way of creating a URL (web address) which specifies which pages to load into a particular frameset. This has a couple important consequences for your framed website:

Web search engines do not index framed sites

Most all web search engines (like AltaVista, Google, etc) will stop indexing your site as soon as they run into a frameset. The only pages to get indexed are the default pages loaded into your frameset (i.e. your "home page"). The rest of your site is ignored. This has serious implications for anyone trying to get their site to show up in web searches (and who isn't??).

Your visitors can only bookmark your home page

Because the address in the web browser window does not indicate which pages are currently loaded into the frames of your frameset, when one of your visitors thinks they are bookmarking a specific page of interest, they are typically just bookmarking your home (frameset) page.

For most webmasters these are serious problems! So next web site you design (or re-design) you should carefully consider whether your site is going to be better with or without framesets.
