# Page Search Setup -- FreeFind.com

Page Search Setup

This tutorial is not a web/html primer and assumes that you already know how the process of "web surfing" is accomplished (i.e. a browser requests a page from a server which then returns the page to be viewed), and what an HTML "form" is and how it works. If you are not familiar with these concepts please read a basic web/html primer.

Who Should Use It

If you want visitors to your site to easily locate pages on your site by searching for words that appear on those pages, then this is for you. The result of a page search is a list of links to pages on your site which contain the desired words.

Setup Overview

For most sites, setup is quick and easy. The following steps outline the procedure from having just signed up to deploy:

-   Tell us to index your site,
-   Add the search panel to a test version of your home page,
-   Make sure your search engine is working great,
-   Subscribe to get that professional look, if desired, and
-   Publish your new home page with search.

Each of these steps is discussed in more detail below.

Indexing your Site

Before your search engine can be used it needs to determine what pages your site has and what words are on those pages. It does this by browsing your site and following all the links it can find that don't lead off to some other site. It's like a very enthusiastic visitor who wants to see all that your site has to offer! This process is called "indexing" (or "spidering") your site.

To index your site you first need to [go to the Control Center](https://freefind.com/control.html) and log in to your account. The password you need was included in your signup email. After you have logged in, go to the ![build index page](../assets/tabbuildindex.gif) page and use the Index now link. When the next page appears, press the Finish button to start indexing your site.

Our spider is careful not to overload your server. It does this by pausing a moment between each page it reads from your site. Because of this, the time it takes to index a site depends mostly on the number of pages the site has, but a slow server can have substantial impact on spidering time as well. When the spider has indexed your site, it will send you an email to let you know the job is complete and how many pages were indexed.

In this case we don't have to wait for the email because we have more to do!

But before we move onto the next step, a couple timely notes:  

-   Every time you make a significant change to your site you will want to go to the Control Center and re-index your site. If you make regular changes to your site you can use the schedule re-indexing link to have your site automatically re-indexed.
-   You can use the change password link in the ![admin tab](../assets/tabadmin.gif) page to change your password to something more memorable.

Now, on to the next step: Adding your Panel to your Site.

Adding your Panel to your Site

The search panel is a simple HTML form, and is easy to add to your site and to customize. Follow these steps to add it to a page of your site:

**1.** Create a copy of your home page to work with.

**2.** Using your usual HTML editor open, the copy of your home page and add the text "search panel here" where you'd like the search panel to be. This will make that location easy to find when you are looking at the HTML code in a later step. Now save and close the page.

**3.** Open the page using a _text editor_ like Windows Notepad (not Wordpad or Word). We recommend using a text editor because many other programs will modify your HTML code in unexpected ways - usually without telling you!

**4.** Locate the "search panel here" text you added to the page in step 2.

**5.** Now determine which panel to add to your site. Using your browser, log in to the [Control Center](https://freefind.com/control.html) then go the to the ![html](../assets/tabhtml.gif) page. This page displays a variety of standard search panels. Choose the panel you like best.

**6.** Select and copy the HTML code that is just below the search panel you want. Be sure to get all of the HTML code, not just part of it!

**7.** Switch back to your text editor and paste the HTML code into your page where the "search panel here" text is. Save your page and quit the text editor.

Now you have a copy of your home page with the search panel added to it.

Before customizing the page further - _or even opening the new page with your HTML editor_ - we suggest going to the next step: Checking Search Engine Operation. This is because most "problems" users encounter with FreeFind are in fact difficulties customizing the page with the panel on it. For example, if you use Word as an HTML editor you may have already noticed that it tends to "break" HTML forms (like the search panel) so that they do not work with Netscape. By verifying the basic search panel operation before customizing your page further, you will have a better idea at which step any problem was introduced.

Checking Search Engine Operation

Before the operation of your new search engine can be checked, FreeFind needs to finish indexing your site. You instructed FreeFind to start this process in one of the first steps. Now check your mail and see if you have recently received an email from our system. The subject will start: "Your site, ...., has been spidered" (or perhaps "sampled" instead of "spidered").

When you receive this email, open it to see how many pages of your site were indexed. For most professional sites this will correspond to the number of pages you have on your site. If the number seems to be incorrect, check our FAQ topics for [too many pages found](https://freefind.com/library/ref/faq/toomany.html) and for [too few pages found](https://freefind.com/library/ref/faq/toofew.html).

After you have the right number of pages, you are ready to test the search panel itself. Open your new home page - still on your local hard disk - using your browser. There is no need to copy this page to your server. You can test locally as long as you currently have access to the internet.

Now use the panel to search for a common word like: `the`

Because the word "the" is so common, this usually just returns a list of most all of the pages that were indexed. If you do not get a list of pages look at the FAQ topics [here](https://freefind.com/library/ref/faq/doesnotwork.html).

If you chose a search panel with the "site map" button, try it now to see how your site map looks. The structure of the site map is determined by how the pages of your site are linked together. A well structured site typically results in a well structured site map, but it is not unusual that a few adjustments are required to make it look optimal. For basic tips on adjusting your site map, see the section below: Customizing your Site Map.

Note that the site map is intended as an overview of your site, and does not necessarily include all the pages in your site.

Now that we know the search engine is basically working, let's move on to: Customizing your Search Results.

Customizing your Search Results

Now we are going to do some easy search result customization.

The search engine serves up to various types of pages, depending on what features you are using:

1.  _Basic search_ - a single search box. ([example](https://search.freefind.com/find.html?si=3225682&sbv=j2))
2.  _Advanced search_ - an advanced search panel. ([example](https://search.freefind.com/find.html?si=3225682&ics=1&pid=a&sbv=j2))
3.  _Search results_ - the results of a search. ([example](https://search.freefind.com/find.html?si=3225682&pageid=r&query=faq&sbv=j2))
4.  _Site map_ - an overview of your site. ([example](https://search.freefind.com/find.html?si=3225682&m=0&p=0&sbv=j2))
5.  _What's new_ - a list of the recently changed pages on your site. ([example](https://search.freefind.com/find.html?si=3225682&w=0&p=0&sbv=j2))
6.  _Index_ - a list of all words in site. ([example](https://search.freefind.com/siteindex.html?id=3225682&ics=1&sbv=j2))

Some of the customization settings apply to all six pages (for example, the background), and some settings apply to specific pages (like page titles).

Although we are not going to go through all the possible customization settings, we will give a few a quick try so you can see how things work.

First, we are going to change the background color for all the pages. To do this log in to the [Control Center](https://freefind.com/control.html) then go the to the ![customize](../assets/tabcustomize.gif) page. In the middle of that page, click on the change background link. A "wizard" will appear that allows you to choose a few different background styles. Click on the "use solid background color below" radio button, then choose "antiquewhite" from the menu.

Now, click on the Preview button to quickly get a rough idea of how your search results will look without committing to making the change. To get back to the wizard you can either use your browser's "back" button, or you can use the "back to setup" button in the previewed results.

After you decide on a background color, press the Finish button to commit to your selection, or if you don't want to change the background setting, press the Cancel button.

Now we are going to change the title of the search results page.

In the middle of the ![customize](../assets/tabcustomize.gif) page, click on the edit site search text link. When the wizard appears find the second "Title:" field (in the "search results page" section), and update the text to whatever you want the title of your search results page to be.

Again, after changing the setting you can use the Preview to see how things will look.

The other settings are handled similarly.

Professionals can achieve a 100% custom look by using the upload custom template link _instead of_ the settings under the "easy customization" category. For more information on how to use templates, read [_Using Templates for the Best Look_](https://freefind.com/library/howto/templates/).

The site map deserves a section of its own, and so...

Customizing your Site Map

There are a few important points to remember about the site map.

_It's an overview._ The site map is not intended to show all the pages of your site. For large sites that would just be confusing. Instead it is intended as an overview -- just showing the major areas of your site -- so your visitor can quickly get going the right direction. Typically a one page site map is plenty.

_It shows how your site is linked together._ The structure of the site map is determined by how the pages of your site are linked together. It does not reflect the "directory structure" of your site on its server. This means to change the structure of your site map you need to change how it's linked or _change when and where the spider finds the existing links_.

_"Up" links (like links back to your home page) are not shown._ This is to simplify the site map for your visitors and make it more useful.

_A lot of the customization is done by adding HTML tags to your site._ There are some basic options available through the online interface, but since most customization issues deal with the particulars of how your site is structured, a lot of the customization is done using custom HTML tags in your site.

We only show you a few quick-and-easy site map setup options here, and don't discuss how to modify the structure of the site map. For a complete discussion of site map setup, see [_How to Restructure your Site Map_](https://freefind.com/library/howto/sitemap/).

There are three different types of site maps available: outline, table, and list. The outline type is usually the best and it is chosen to be the default site map shown. You can select which types of site maps are available to your visitors - _or turn off the site maps altogether_ - by using the set map type link. Once the wizard appears, how to choose which type of map(s) are available is obvious. What is less clear is that you can _uncheck all the map types to turn off the site maps completely_. Of course if you do this you will need to remove any "site map" button that may be in your search panel.

By default, the site map is automatically sized so that it doesn't end up being too big or too small. If you want to override this default size you can use the map depth and format link. When the wizard appears, adjust the "map depth" value to vary the size of the map. For automatic sizing, set the value to 0. After your site is re-indexed the site map will reflect the new size setting.

What is site map "depth"? Basically it's a measure of how many clicks it takes to get to a page. If you have to click on two links to get to one of your pages from your home page, it has a depth of two. Setting the site map depth to 2 will make the site map include all pages up to and including pages at that depth.

Note that there are absolute limits on site map size. If your site is large, it is likely that you will be unable to have a site map which contains all of the pages of your site.

OK. Now things are looking good, so it's time to consider...

Removing Advertising

Although our service is truly free, most professionals will want to remove the advertising from their search results by paying a modest subscription fee. This allows you to remove all mentions of FreeFind and ensures that your search will have the same professional-grade look as the rest of your site.

To do this, log in to [the Control Center](https://freefind.com/control.html) then go to the ![subscribe](../assets/tabsubscribe.gif) page and sign up for one of our low-cost plans.

Publishing your New Home Page

Frankly, we can't really help you with this - there are just too many ways of copying your page to your server nowadays! But since you already have a site we feel confident that you have already mastered this step.

Where to go from Here

For many sites nothing more needs to be done - your site now has its own search engine. For more complex sites and situations, refer [back to the library](https://freefind.com/library/) for lots more information.
