# Using document dates -- FreeFind.com

Using document dates

Document dates can be displayed along with your search results. Search results can also be sorted by date, showing the most recently modified documents first and older documents later in the search results.

Overview

Not all web servers support document dates, and even those that do may not have it enabled.

The FreeFind search engine obtains document dates in one of two ways:

-   your web server sends us a "last modified" date field with each page, or
-   you have a [document-date](https://freefind.com/library/ref/tagref/#date) meta tag on each page of your web site.

If your web server does not send last modified dates and you have no document-date meta tags in your web pages, FreeFind's date features will not work properly for your website.

Once you have verified that your website supports document dates you can show dates in your search results by clicking on the ![customize](../assets/tabcustomize.gif) tab in the FreeFind control center, then clicking on the "Result item format" link. In that dialog you will be able to turn on date display and choose a time zone and date format.

You can also enable date sorting. Click on the ![customize](../assets/tabcustomize.gif) tab in the FreeFind control center, then click on "Result ordering" link and check the "show sorting controls" checkbox. If you want to default to date sorting you can also select the "sort result by date" radio button.

Checking for date support

Some web servers send us a "last modified" date with each page they serve, others send no last modified date, still others send incorrect last modified dates (often today's date) with each page served.

To determine if your web server is sending us proper last modified dates you'll need to check your indexing log.

If your site has not been indexed already, you will need to [do that first](https://freefind.com/library/ref/faq/spidering.html#index01).

To check your indexing log:

-   click on ![reports](../assets/tabreports.gif) in the FreeFind control center
-   click on "View log list"
-   click on "view"
-   click on "builder log"

The first column of the builder log contains the last modified times that your server sent to us. If these times are correct then you can use all of FreeFind's date related features. These times are in GMT.

If the date values are not set or if the values are incorrect you will need to either adjust your server settings to make it work properly or add document date meta tags to each of your documents.

See the [tag reference](https://freefind.com/library/ref/tagref/#date) for information about the document date meta tag.
