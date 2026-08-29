# How to Use Sections -- FreeFind.com

How to Use Sections

You can allow your visitors to search only in a specific part of your site by dividing your search index into sections.

This tutorial is not a web/html primer and assumes that you already know how the process of "web surfing" is accomplished (i.e. a browser requests a page from a server which then returns the page to be viewed), what HTML tags are and how to use them. If you are not familiar with these concepts please read a basic web/html primer.

Overview

There are two general steps involved in allowing your visitors to search by section:

-   Divide your index into multiple sections, and
-   Use a search panel to access those sections

Each of these steps is discussed in more detail below.

Dividing your Index into Sections

To do this, you simply [log in to your account](https://freefind.com/control.html), go to the ![build index page](../assets/tabbuildindex.gif) page and use the define subsections link. When the wizard appears add your list of "section specifications", one per line (browser wrapping may be ignored), and press the Finish button to save your changes.

Each section specification consists of a "URL mask" and a list of single-word section names each with an optional modifier. Here are a few quick examples:

		http://example.com/store/\* products
		http://example.com/store/test/\* products=exclude
		http://example.com/store/art/\* products artsupplies
		http://example.com/articles/\* howto
	

The URL mask is simply a standard web address, but may contain the common wildcards `"*"` and `"?"` to make it match more than one web address. The `"*"` will match any number of any character and the `"?"` will match any single character. Non-wildcard characters are matched without regard to case (case-insensitive). URL masks which do not begin with `"http://"` are treated as if they begin with `"*"`. Because of this it is recommended that you include the `"http://"` in your URL masks.

The URL mask is followed by one or more single-word section names. Your visitors will never see these names, they are just used by the search engine to identify each section. Each section name may by followed by an equals sign `("=")` and then one of the modifiers:

		include
		exclude
	

to control whether web addresses which match the URL mask are included or excluded from that section. The default is `"include"`.

Note: The section name `"web"` is reserved. You cannot use it as your own section name. It is used by search panels to indicate a web search (not a site search) should be performed.

When determining which section specification to apply, entire list is considered and _the last matching section specification_ is used. This allows convenient expression of "include everything but..." logic. For example, to include everything in your `"http://example.com/store/"` directory in a section _except_ pages in the `"/store/test/"` subdirectory you can use the following:

		http://example.com/store/\* products
		http://example.com/store/test/\* products=exclude
	

After you have specified all of your sections your site will be reindexed before the new sections are active.

Use a Search Box with Sections

Now that you have an index with various sections you need to use an appropriate search panel to allow your visitors to use those sections. To do this just go to the ![html](../assets/tabhtml.gif) page and choose the panel with sections. Add it to your web site in the usual manner. To review instructions for this see the chapter [_Adding your Box to your Site_](https://freefind.com/library/tut/pagesearch/#adding) in the tutorial _Page Search Setup_.

You may want to change the labels of the sections as they appear in the drop down list. This is fine, just be sure to change the option text only, not the option value itself. To see an example of this, and more information on customizing any search panel to support sections, see [_Customizing a Search Box with Sections_](https://freefind.com/library/howto/sections/#custpanel), below.

Customizing a Search Box with Sections

There are a couple common ways to allow a visitor to select which section(s) to search. You can add a drop-down menu to your search panel, or you can also provide checkboxes (or radio buttons) for the user to check. Details on how to do both of these are provided below.

Using a drop-down menu to choose which section to search

To add a drop-down menu to your search panel, add HTML code similar to this:

			   <select name="s">
			   <option value="" selected>Entire site</option>
			   <option value="products">Products for sale</option>
			   <option value="howto">How-to articles</option>
			   <option value="web">The entire web</option>
			   </select>
			

The select name must be `"s"`. For the "search entire site" option, the value should be an empty string `("")`. For a web search option the value should be `"web"`. This is a reserved section name which may only be used for this purpose. Other selections should have option values which correspond to the single-word section names you used when specifying the sections.

Note: The section name `"web"` is reserved. It is used by search panels to indicate a web search (not a site search) should be performed.

Using checkboxes to choose which section(s) to search

To add a drop-down menu to your search panel, use HTML code similar to this:

			   <input type="checkbox" name="s" value="products">Things for Sale
			   <input type="checkbox" name="s" value="howto">How-to articles
			   <input type="checkbox" name="s" value="web">The entire web
			

Each checkbox must have the name `"s"`. The value should be the single-word section name you used when specifying the section. For web search the value should be `"web"`. This is a reserved section name which may only be used for this purpose. If it is checked a web search (only) will be performed even if other sections are checked as well. If nothing is checked by the user, the entire index will be used.

Additional points

1.  The HTML must be placed _after_ the search panel's initial
    
    				<form ...
    			
    
    tag and _before_ it's ending
    
    				</form>
    			
    
    tag, otherwise it will not work.
    
2.  We recommend using a text editor to modify your search panel as many HTML editors modify added html code without notifying you, giving unpredictable results.
