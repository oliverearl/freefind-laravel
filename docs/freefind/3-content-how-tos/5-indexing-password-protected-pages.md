# How to Index Password Protected Pages -- FreeFind.com

How to Index Password Protected Pages

Your search engine can easily be configured to index parts of your site which are protected using the HTTP basic authorization scheme.

This tutorial is not a web/html primer and assumes that you already know how the process of "web surfing" is accomplished (i.e. a browser requests a page from a server which then returns the page to be viewed), what HTML tags are and how to use them. If you are not familiar with these concepts please read a basic web/html primer.

Overview

Using this feature is easy. All you need to do is specify a valid username and password for a given URL prefix (web site address prefix). Detailed instructions on how you do this are in the next section below.

Setup

To setup password protected indexing for part of your site simply [log in to your account](https://freefind.com/control.html), go to the ![build index page](../assets/tabbuildindex.gif) page and use the password protected areas link. When the wizard appears list the "authorization specification" for each password protected area of your site, one per line (browser wrapping may be ignored), and then press the Finish button to save your changes.

Each authorization specification consists of a "URL mask" and a valid username and password to access pages which match the mask. Here are a couple quick examples:

		http://example.com/members/\* user=alan password=iwonttell
		http://example.com/members/gold/\* user=jim password=mypassword
	

Also, it is valid to have no user name and password information:

		http://example.com/members/freepreview/\*
	

The URL mask is simply a standard web address, but may contain the common wildcards `"*"` and `"?"` to make it match more than one web address. The `"*"` will match any number of any character and the `"?"` will match any single character. Non-wildcard characters are matched without regard to case (case-insensitive). URL masks which do not begin with `"http://"` are treated as if they begin with `"*"`. Because of this it is recommended that you include the `"http://"` in your URL masks.

The URL mask is typically followed by a valid user name and password, as shown in the example above. Any time our spider (indexer) requests a page which has a matching URL mask, the user name and password will be provided to that server. If a matching URL mask has no associated user name and password, then the page is assume to be public and not require any authorization. Your visitors will never see this information, but _be sure to specify your server_ in the URL mask otherwise your user name and password will be sent to someone else's server!

When determining which authorization specification to apply, the entire list is considered and _the last matching authorization specification_ is used. This allows convenient expression of "include everything but..." logic. For example, if everything in your `"http://example.com/members/"` directory is password protected _except_ pages in the `"/members/freepreview/"` you can use the following:

		http://example.com/members/\* user=alan password=iwonttell
		http://example.com/members/freepreview/\*
	

After you have specified all of your authorization information, click on the Finish button to save your changes.

Security Warnings

The indexing of password protected sites is recommended for low security sites only.

YOU MUST USE JUDGEMENT IN DETERMINING WHICH PASSWORD PROTECTED SITES YOU INDEX.

For example, while you must make the final judgment, in our judgment we would NOT index password protected sites that contain high security content like:

-   Credit card numbers
-   Financial information
-   Medical records
-   Trade secrets
-   Confidential data
-   Other high security content

We would consider indexing password protected sites that include low security content like:

-   Members-only content and articles
-   Registered user support information
-   Subscription services
-   Other low security content

**Before entering passwords**

Some things you should keep in mind before entering user names and passwords into the FreeFind system.

1.  Security for user names and passwords should be considered "low".
2.  The search itself is not password protected.
    1.  anyone with your FreeFind site ID can run a search
    2.  anyone with your search box HTML can run a search
3.  FreeFind Site IDs are not secret.
    1.  they are not changeable so...
        1.  anyone who has run a search has the ID forever
        2.  they are included in the URL
            1.  URLs are logged in various places (servers, proxies)
            2.  URLs are used as referrers in any link on the page
        3.  they will leak out eventually
    2.  valid IDs can be found by guessing (though to guess a specific ID is harder)
    3.  they were never designed to be secret so there may be other leaks
4.  The search results contain extracts from your password protected documents, areas around the searched for keyword are displayed.
5.  Your documents still ARE password protected, when a user clicks on a search result they must authenticate.
6.  The username(s) and password(s) that you enter into the search engine control center are saved on our servers.
    1.  they are transmitted to and from our servers in plain text
    2.  they are stored on our servers
    3.  they may be used by our support and engineering staffs in diagnosing problems and insuring proper system operation
    4.  they may be accessed by anyone who is able to log in to your FreeFind account
    5.  they may be accessed by anyone who is able to gain unauthorized access to our servers
7.  Basic HTTP authorization (by definition) sends your username and password to your servers in what amounts to clear text.
