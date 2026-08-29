# How to Restructure your Site Map -- FreeFind.com

How to Restructure your Site Map

This how-to discusses how to change the structure of your site map. Throughout this document we use the "outline" type of site map, as that one is most illustrative of the structure of your site.

Setup Overview

There are a few important points to remember about the site map.

It's an overview.

The site map is not intended to show all the pages of your site. For large sites that would just be confusing. Instead it is intended as an overview -- just showing the major areas of your site -- so your visitor can quickly get going the right direction. Typically a one page site map is plenty.

It shows how your site is linked together.

The structure of the site map is determined by how the pages of your site are linked together. It does not reflect the directory structure of your site on its server. This means to change the structure of your site map you need to change how it's linked or _change when and where the spider finds the existing links_.

"Up" links (like links back to your home page) are not shown.

This is to simplify the site map for your visitors and make it more useful.

A lot of the customization is done by adding HTML tags to your site.

There are some basic options available through the online interface, but since most customization issues deal with the particulars of how your site is structured, a lot of the customization is done using custom HTML tags in your site.

Basic Setup

There are three different types of site maps available: outline, table, and list. The outline type is usually the best and it is chosen to be the default site map shown.

You can select which types of site maps are available to your visitors - _or turn off the site maps altogether_ - by logging in to [the Control Center](https://freefind.com/control.html), going to the ![customize](../assets/tabcustomize.gif) page and using the set map type link. Once the wizard appears, how to choose which type of map(s) are available is obvious.

**Note:** To turn off site maps completely, uncheck _all the map types_.

By default, the site map is automatically sized so that it doesn't end up being too big or too small. If you want to override this default size you can use the map depth and format link. When the wizard appears, adjust the "map depth" value to vary the size of the map. For automatic sizing, set the value to 0. After your site is re-indexed the site map will reflect the new size setting.

What is site map "depth"? Basically it's a measure of how many clicks it takes to get to a page. If you have to click on two links to get to one of your pages from your home page, it has a depth of two. Setting the site map depth to 2 will make the site map include all pages up to and including pages at that depth.

Note that there are absolute limits on site map size. If your site is large, it is likely that you will be unable to have a site map which contains all of the pages of your site.

Restructuring your Site Map

Since the structure of your site map is determined by how your site is linked together, you can change the structure of your site map by changing when and where the spider finds links.

For news-oriented sites a common example is having a link to a recent story on the home page, but wanting that story placed in a sub-category (like "world news") in the site map, and not off the home page. To do this you would surround the link to that story on the home page with the special tags:

		<!-- FreeFind nofollow -->
	

and:

		<!-- FreeFind end nofollow -->
	

to prevent the spider from "seeing" it. Then next time the site was indexed, the only link to the news story would be the link in the "world news" section, so that is where it would appear in the site map.

Using this technique you can control how the spider thinks your site is linked together, and thus the structure of your site map.

For additional ways of preventing the spider from following links, see [_How to Exclude Pages_](https://freefind.com/library/howto/exclude/#nofollow).
