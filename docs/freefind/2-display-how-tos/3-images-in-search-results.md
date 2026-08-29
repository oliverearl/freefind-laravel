# How to Use Images -- FreeFind.com

How to Use Images

You can enable the search engine to display an image along with each search result item shown on the search results page.

You might also use it, for example, to place an icon that identifies the section of your site like "news" or "archive", or an image of the author of an article, or an icon that identifies the type of document, such as pdf.

This how-to shows you how to accomplish this.

This tutorial is not a web/html primer and assumes that you already know how the process of "web surfing" is accomplished (i.e. a browser requests a page from a server which then returns the page to be viewed), what HTML tags are and how to use them. If you are not familiar with these concepts please read a basic web/html primer.

Overview

There are three basic ways to specify which image you would like displayed for a given page.

-   On-page tags, such as the thumbnail meta tag, or the FreeFind image tag
-   Specify images by the <img> src= attribute
-   Specify images by the page they appear on

Each of these methods is discussed in more detail below.

Using on-page tags to define an image.

FreeFind supports three types of on-page tags that can be used to associated an image with the page the tag appears on. They are:

-   FreeFind image tag
-   The meta thumbnail tag
-   PicoSearch picolinkpic tag

The FreeFind image tag

comes in the form of a specially formatted HTML comment. It can be placed anywhere in your html <head> or <body> sections. It looks like this.

				<!-- FreeFind image src="http://example.com/someimage.jpg" -->
			

The tag can optionally include image attributes, e.g. height, width, style, class, etc.

				<!-- FreeFind image src="http://example.com/someimage.jpg" alt="my image" height=50 width=50 class=simage -->
			

By default the image is linked to the page the image tag appears on. So when the user clicks on it they go to that page. You can optionally specify a different page for the image to be linked to by using an href attribute.

				<!-- FreeFind image src="http://example.com/someimage.jpg" href="http://example.com/buynow.html" -->
			

The href attribute, if any, should be placed after all the image attributes. It can be followed by its own attributes.

				<!-- FreeFind image src="http://example.com/someimage.jpg" alt="my image" height=50 width=50 class="simage" href="http://example.com/buynow.html" target="\_top" -->
			

The thumbnail meta tag

comes in the form of a standard meta tag, with the name of "thumbnail" and a content of the image source url. For example:

				<meta name="thumbnail" content="http://example.com/someimage.jpg" >
			

The thumbnail meta tag should be placed in the <head> section of your HTML page.

The PicoSearch picolinkpic tag

is included for compatibility with sites that used to use the PicoSearch search engine but have transitioned to FreeFind as PicoSearch is shutting down. If you do not already have these tags on your site we recommend using the FreeFind image tag instead.

Using <img> src attributes to specify images

Some sites may be able to specify the image to use for a page by matching an image tag src attribute with a specified list. As the search engine is indexing your page it checks this list, the first matching image it finds is assigned as the image for the page. The list contains one image URL pattern per line. The URL pattern is simply a standard image url, but may contain the common wildcards `"*"` and `"?"` to make it match more than one web Image.

This works best with sites that have their product images organized into their own directory or named in an identifiable pattern. For example your list of image src patterns might contain the following:

		http://example.com/products/\*
		http://example.com/prod\_image\*.jpg
	

Using that example list, if you have two image tags on a page:

		<img src="http://example.com/img/mylogo.gif" width=30 height=30>
		<img src="http://example.com/products/toaster.jpg" width=40 height=30>
	

The search engine would select toaster.jpg because its URL matches the one of the patterns in the list above ( http://www.example.com/products/\* ).

You can also include image attributes in your list of image URL patterns. For example your image URL pattern list contains:

		http://example.com/products/\*
		http://example.com/prod\_image\*.jpg
	

and, once again, your page contains the images:

		<img src="http://example.com/img/mylogo.gif" width=30 height=30 >
		<img src="http://example.com/products/toaster.jpg" width=40 height=30 >
	

The search engine would select the toaster.jpg image, and would add the to the image tag used in the search results, yielding the following image tag

		<img src="http://example.com/products/toaster.jpg" width=40 height=30>
	

In the event that the same image attribute is used in the image URL pattern list and in the image tag itself, the attribute in the URL pattern list would be used. For example if your Image URL pattern list looked like this:

		http://example.com/products/\* width=30 height=""
		http://example.com/prod\_image\*.jpg width=30 height=""
	

and, once again, your page contains the images:

		<img src="http://example.com/img/mylogo.gif" width=30 height=30 >
		<img src="http://example.com/products/toaster.jpg" width=40 height=30 >
	

The search engine would select the toaster.jpg image, and would add the to the image tag used in the search results and replace the height and width tag yielding the following image tag in the search results:

		<img src="http://example.com/products/toaster.jpg" width=30 height="">
	

Specify images by the page they appear on.

You can create a list of page URL patterns and associate an image with each pattern. The list contains one page URL pattern and associated attributes per line. The URL pattern is simply a standard web address, but may contain the common wildcards `"*"` and `"?"` to make it match more than one web page.

For example if you wanted to add an icon to indicate what type of document the result is, you might make a page URL pattern list something like this:

		\*.pdf src="http://example.com/pdficon.gif"
		\*.doc src="http://example.com/docicon.gif"
		\*.html src="http://example.com/htmlicon.gif"
	

As you can with the image source list, you can include image attributes in your list:

		\*.pdf src="http://example.com/pdficon.gif" width=30 height=12
		\*.doc src="http://example.com/docicon.gif" width=20 height=12
		\*.html src="http://example.com/htmlicon.gif" width=25 height=12
