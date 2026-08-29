# Hiding the Search Panel -- FreeFind.com

Hiding the Search Panel

When the user performs a search a list pages which satisfy the user's query is displayed. By default there is a search panel at the top of this list so that the user can easily submit an additional query.

This tutorial is not a web/html primer and assumes that you already know how the process of "web surfing" is accomplished (i.e. a browser requests a page from a server which then returns the page to be viewed), what HTML tags are and how to use them. If you are not familiar with these concepts please read a basic web/html primer.

Overview

In order to tell the search engine that no search panel is desired on the results page it will be necessary to add an HTML tag to the search panel on your site. This HTML tag will tell the search engine to hide the standard search panel. The detailed steps are provided below.

Removing the Search Panel

Add the following tag to the search panel on your site:

	<input type=hidden name=nsb>
	

When you use a search panel that has been properly set up in this fashion, the search engine will not generate the default search panel at the top of the search results.

Remember that the input tag above must occur after the search panel's opening `<form>` tag and before its closing `</form>` tag.
