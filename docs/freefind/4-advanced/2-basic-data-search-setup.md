# Data Search Setup -- FreeFind.com

Data Search Setup

This tutorial describes data search setup and deployment in detail. The search engine's data search mode allows you to index "lists of things" instead of indexing web pages. For more information on what data search can do for you, see the [data search features page](https://freefind.com/datasearch.html).

In addition, be sure to check the [data search policies](https://freefind.com/datasearch.html#policies) before you begin.

This tutorial is not a web/html primer and assumes that you already know how the process of "web surfing" is accomplished (i.e. a browser requests a page from a server which then returns the page to be viewed), and what an HTML "form" is and how it works. Also, you will need to be comfortable inserting special HTML comments into the correct places in your pages and making links absolute.

Who Should Use It

To put it simply, experienced web developers who have "lists of stuff" on their web site.

If you want visitors to your site to easily locate items which are listed on your site by searching for words which appear in those items, then this is for you. The result of a data search is a custom list of those items which contain the desired words. _Not_ links to those items, but a _list of those actual items_. Contrast this to a traditional page search whose result is a list of links to pages.

It doesn't matter what that the items to be indexed are. Any portion of a web page (like a product description) can be an "item", and there can be (and usually are) more than one item per page.

And, repeating an important warning: data search is not for beginners. It is substantially more difficult to set-up than normal site search, and you need to be _very familiar_ with the HTML _language_.

Setup Overview

For most sites, setup is quick and easy. The following steps outline the procedure from sign up to deploy:

-   [Sign up](https://freefind.com/) for your search engine and receive your sign up email,
-   Tell the search engine to use data search mode,
-   Identify the "items" listed on your site,
-   Optionally categorize each page of items you have,
-   Tell us to index your site,
-   Add the search panel to your home page,
-   Make sure your search engine is working great,
-   Subscribe to get that professional look, if desired, and
-   Publish your new home page with search.

Each of these steps is discussed in more detail below.

Signing Up

To sign up, just go to our [home page](https://freefind.com/) and fill out the form there. To get started, we just need two pieces of information:

_Your email address._ This needs to be a valid email address. If there is even a small error in the email address, you will not receive your sign up email and will not be able to access our system.

_Your website address._ This should be the actual address of your website once a visitor has arrived. In most cases the easiest way to determine this is to simply go to your website and then copy the address from the "address" or "location" field at the top of your browser after your home page has been displayed. (_Note:_ Your site must be online to sign up. If it is not yet ready, you can either use the address you are using for online testing or you can use a different site altogether. In both cases, you would use our online account administration tools to simply update your site address when it is ready.)

After you have signed up your signup email is sent out _immediately_. This actually occurs before the next page is displayed so that we may report any errors in this process to you. Due to the nature of the internet and email, it may take a few minutes or more to receive the email even though it was already mailed to you.

_Note:_ One account cannot be used for both regular page search and data search. If you want both capabilities, you'll need two accounts. Furthermore, two accounts cannot have the same URL/email combination. For your page search account use your home page. For your data search account specify some other page in your site.

When you receive your email you will be ready to move on to the next step.

Specifying Data Search Mode

In order to let search engine know you want to use data search you'll need to add this tag:

		<!-- FreeFind Index Listings Only id="_YourSiteID_" -->
	

to the very start of the first page read by the spider (the page you specified when you signed up for your account). _You must replace_ `YourSiteID` with your actual site ID (provided in your signup email).

Identifying Your Items

An "item" can be any part of a web page, and there can be multiple items per page (like product listings, web site reviews, bios, etc).

Place the following tags before and after each item listed in your site:

	    <!-- FreeFind Listing -->
	    <!-- FreeFind End Listing -->
	

This lets the search engine know what constitutes an item in your list. An "item" may consist of any HTML at all. For example, the FreeFind library search has glossary entry items, FAQ Q&A items, and more all combined into a single list of "things a user might find helpful". A product listing item might include photos of the product, pricing, etc.

If any URLs your items are relative (do not start with `"http://..."`) you'll need to change them. This is because the search engine constructs a custom page using your items, and then serves it from our server, not yours. If the links in your items are relative, they will be referring to (non-existent) locations on our web site instead of the correct locations on your web site. _This includes URLs of images, too!_

Here is an example of two items in a list:

	    <!-- FreeFind Listing -->
	    <a href="http://www.freefind.com">FreeFind.com</a>
	    The coolest thing on the net. Quite possibly the
	    pinnacle of human achievement. Blah blah blah.
	    <!-- FreeFind End Listing  -->

	    <!-- FreeFind Listing -->
	    <a href="http://www.fidofind.com">FidoFind.com</a>
	    A real dog of a search engine. Blah blah blah.
	    <!-- FreeFind End Listing -->
	

Specifying Item Categories (opt.)

This optional tag is typically used to specify a category for all the listings on a given page. It is placed at the top of the page, and applies to all of the items on that page. The value part of the tag may be any valid HTML.

		<!-- FreeFind Category  value='your HTML here' -->
	

_Note:_ Single quotes (') enclose the value field instead of double quotes ("), and any links must be absolute, not relative.

When the search engine constructs a result page, each item begins with the HTML specified in this tag. It is usually used to describe and link to a page of similar items. For example, if the item is a particular screen saver, the category HTML might link back to the page of all screen savers.

Here's an example of a "sites that begin with F" category:

	  <!-- FreeFind Category value='<small>Category: <a
	  href="https://freefind.com/dsdemo/index.html">
	  F sites</a></small>' -->
	

Indexing your Site

Before your search engine can be used it needs to determine what items your site has and what words are in those items. It does this by browsing your site and following all the links it can find that don't lead off to some other site. It's like a very enthusiastic visitor who wants to see all that your site has to offer! This process is called "indexing" (or "spidering") your site.

To index your site you first need to [go to the Control Center](https://freefind.com/control.html) and log in to your account. The password you need was included in your signup email. After you have logged in, go to the ![build index page](../assets/tabbuildindex.gif) page and use the Index now link. When the next page appears, press the Finish button to start indexing your site.

Our spider is careful not to overload your server. It does this by pausing a moment between each page it reads from your site. Because of this, the time it takes to index a site depends mostly on the number of pages the site has, but a slow server can have substantial impact on spidering time as well. When the spider has indexed your site, it will send you an email to let you know the job is complete and how many pages were indexed.

In this case we don't have to wait for the email because we have more to do!

But before we move onto the next step, a couple timely notes:  

-   Every time you make a significant change to your site you will want to go to the Control Center and re-index your site. If you make regular changes to your site you can use the schedule re-indexing link to have your site automatically re-indexed.
-   You can use the change password link in the ![admin tab](../assets/tabadmin.gif) page to change your password to something more memorable.

Now, on to the next step: Adding your Panel to your Site.

Adding your Panel to your Site

The search panel is a simple HTML form, and is easy to add to your site and to customize. Follow these steps to add it to a page of your site:

1.  Create a copy of your home page to work with.
    
2.  Using your usual HTML editor open, the copy of your home page and add the text "search panel here" where you'd like the search panel to be. This will make that location easy to find when you are looking at the HTML code in a later step. Now save and close the page.
    
3.  Open the page using a _text editor_ like Windows Notepad (not Wordpad or Word). We recommend using a text editor because many other programs will modify your HTML code in unexpected ways - usually without telling you!
    
4.  Locate the "search panel here" text you added to the page in step 2.
    
5.  Now determine which panel to add to your site. Using your browser, log in to the [Control Center](https://freefind.com/control.html) then go the to the ![html](../assets/tabhtml.gif) page. This page displays a variety of standard search panels. Choose the panel you like best. Note that the site map is not available in data search mode, so pick a search panel which doesn't have that option.
    
6.  Select and copy the HTML code that is just below the search panel you want. Be sure to get all of the HTML code, not just part of it!
    
7.  Switch back to your text editor and paste the HTML code into your page where the "search panel here" text is. Save your page and quit the text editor.
    

Now you have a copy of your home page with the search panel added to it.

Before customizing the page further - _or even opening the new page with your HTML editor_ - we suggest going to the next step: Checking Search Engine Operation. This is because most "problems" users encounter with FreeFind are in fact difficulties customizing the page with the panel on it. For example, if you use Word as an HTML editor you may have already noticed that it tends to "break" HTML forms (like the search panel) so that they do not work with Netscape. By verifying the basic search panel operation before customizing your page further, you will have a better idea at which step any problem was introduced.

Checking Search Engine Operation

Before the operation of your new search engine can be checked, FreeFind needs to finish indexing your site. You instructed FreeFind to start this process in one of the first steps. Now check your mail and see if you have recently received an email from our system. The subject will start: "Your site, ...., has been spidered" (or perhaps "sampled" instead of "spidered").

When you receive this email, open it to see how many items in your site were indexed (they will be reported as "pages"). If the number seems low, check the FAQ topic [too few pages found](https://freefind.com/library/ref/faq/toofew.html). If the spider cannot find the page(s) your items are on, it cannot find the items. Also check the data search HTML that you added to your site. Does it look correct?

After you have the right number of items, you are ready to test the search panel itself. Open your new home page - still on your local hard disk - using your browser. There is no need to copy this page to your server. You can test locally as long as you currently have access to the internet.

Now use the panel to search for a word that appears in some (or all) of your items. If you do not get a list of items then your search panel was probably modified when you added it to your page, or your page already had a form in it that is getting in the way. For more info look [here](https://freefind.com/library/ref/faq/doesnotwork.html).

Now that we know the search engine is basically working, let's move on to: Customizing your Search Results.

Customizing your Search Results

Now we are going to do some easy search result customization.

In data search mode, the search engine serves up to four types of pages, depending on what features you are using:

1.  _Basic search_ - a single search box. ([example](https://search.freefind.com/find.html?si=8948249&sbv=j2))
2.  _Search results_ - the results of a search. ([example](https://search.freefind.com/find.html?si=8948249&pageid=r&query=faq&sbv=j2))

Some of the customization settings apply to all four pages (for example, the background), and some settings apply to specific pages (like page titles).

Although we are not going to go through all the possible customization settings, we will give a few a quick try so you can see how things work.

First, we are going to change the background color for all the pages. To do this log in to the [Control Center](https://freefind.com/control.html) then go the to the ![customize](../assets/tabcustomize.gif) page. In the middle of that page, click on the change background link. A "wizard" will appear that allows you to choose a few different background styles. Click on the "use solid background color below" radio button, then choose "antiquewhite" from the menu.

Now, click on the Preview button to quickly get a rough idea of how your search results will look without committing to making the change. To get back to the wizard you can either use your browser's "back" button, or you can use the "back to setup" button in the previewed results.

After you decide on a background color, press the Finish button to commit to your selection, or if you don't want to change the background setting, press the Cancel button.

Now we are going to change the title of the search results page.

In the middle of the ![customize](../assets/tabcustomize.gif) page, click on the edit site search text link. When the wizard appears find the second "Title:" field (in the "search results page" section), and update the text to whatever you want the title of your search results page to be.

Again, after changing the setting you can use the Preview to see how things will look.

The other settings are handled similarly.

Professionals can achieve a 100% custom look by using the upload custom template link _instead of_ the settings under the "easy customization" category. For more information on how to use templates, read [_Using Templates for the Best Look_](https://freefind.com/library/howto/templates/).

Removing Advertising

Although our service is truly free, most professionals will want to remove the advertising from their search results by paying a modest subscription fee. This allows you to remove all mentions of FreeFind and ensures that your search will have the same professional-grade look as the rest of your site.

To do this, log in to [the Control Center](https://freefind.com/control.html) then go to the ![subscribe](../assets/tabsubscribe.gif) page and sign up for one of our low-cost plans.

Publishing your New Home Page

Frankly, we can't really help you with this - there are just too many ways of copying your page to your server nowadays! But since you already have a site we feel confident that you have already mastered this step.

HTML Example

We've put together a tiny one page "site" [here](https://freefind.com/dsdemo/index.html).

It is a fully functional site that reviews other web sites, including a working search box for you to try out.

To view the sample source, go to that page and use your browser's "view source" option. The first half of the source is the search panel that gets displayed on the left of the page. The last half is the list of web reviews.

Naturally, with just one page and three "items" there isn't any point in using data search, but when you start having more than a dozen or so it's vital.

Hints

Here are a few hints and things to remember:

-   If you are using the free service, your listings may _not_ include banner advertising.
-   For a quick summary of the HTML tags that control data search, look at the [_HTML Tag Reference_](https://freefind.com/library/ref/tagref/#datasearch).
-   All relative URLs _must_ be converted to absolute URLs that start with `"http://...".` _This includes URLs of images, too!_
-   After you have done your first few items, have your site spidered then check your search to be sure everything is going as expected.
-   You should browse through all listings to check for syntax errors. The spider does not report syntax errors in your data search HTML.

Where to go from Here

For many sites nothing more needs to be done - your site now has its own search engine! For more complex sites and situations, refer [back to the library](https://freefind.com/library/) for lots more information.
