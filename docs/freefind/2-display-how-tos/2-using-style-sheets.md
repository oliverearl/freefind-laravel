# Changing the Font Using Style Sheets -- FreeFind.com

Changing the Font Using Style Sheets

You can change the font used in the search results page by using style sheets. Special CSS classes have been set up to allow you to easily change the look of each element on the page.

Use this feature to change the search results font so that it exactly matches your site. You can also use these classes and your style sheets to do other creative customization.

This tutorial is not a web/html primer and assumes that you already are familiar with creating web pages and with cascading style sheets (CSS). If you are not familiar with these concepts please read a basic web/html primer.

Overview

In order to change the font using style sheets you will need to customize your results using a template file (see [Using Templates for the Best Look](https://freefind.com/library/howto/templates/)). Use of a template file allows you to set the styles used on the search result page. The base CSS classes below are always available in the search results. The extended CSS classes are optionally available.

This how-to assumes that you have already set up a basic template, and now want to change the font of the search results.

Base CSS Classes

To set the fonts, add a style sheet to your template page. Here is an example which sets the overall font used for result items to verdana and makes the line at the top of the search results red:

	<style>
	.search-results {font-size:10pt; color:Black; font-family:verdana,sans-serif;}
	.search-line {background:Red;}
	</style>
	

The table below lists all of the standard CSS classes available. Additional optional classes are detailed in the section after this one.

.search-results

All search results text

.search-headline

"Search results from this site" headline (and sponsored search, if present)

.search-nav

"Next" and "prev" buttons

.search-form

The search panel form

.search-count

The item count

.search-no-results

Message: "No pages were found that match your query."

.search-page-links

Other links (site map, home, etc...)

.search-line

One pixel wide lines

.search-header-table

Table at the top containing search count message and the "search tips" (etc) links

.search-footer-table

Table at the bottom containing the "search tips" (etc) links

.search-nav-form-table

Table containing the nav controls and the searchbox

.search-nav-cell

Table cell containing the nav controls

.search-form-cell

Table cell containing the search panel form

Extended CSS Classes

The search engine can optionally create pages with extended CSS classes. This allows greater control over the formatting of each result item. To enable this feature you will need to add this HTML tag to your search panel:

	<input type=hidden name=css value="">
	

This tag instructs the search engine to include the extended CSS classes listed below in the search result pages:

.search-item-number

The item number portion of each search result item

.search-item-links

The link portion of each search result item

.search-item-description

The description portion of each search result item

.search-item-url

The URL portion of each search result item
