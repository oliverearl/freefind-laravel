# How to use Robots.txt -- FreeFind.com

How to use Robots.txt

The preferred way of preventing parts of your site from being indexed is to use the Control Center page exclusion mechanism. This is covered in [_How to Exclude Pages from Search_](https://freefind.com/library/howto/exclude/). You should read that "how to" first. The only reason you might need to use a `robots.txt` file is if you want to prevent someone else from using this search engine to index your site.

This tutorial is not a web/html primer and assumes that you already know how the process of "web surfing" is accomplished (i.e. a browser requests a page from a server which then returns the page to be viewed), what an HTML "form" is and how it works, and what a link "target" is. If you are not familiar with these concepts please read a basic web/html primer.

Overview

Using a `robots.txt` file is easy, but does require access to your server's root location. For instance, if your site is located at:

		http://example.com/mysite/index.html
	

you will need to be able to create a file located here:

		http://example.com/robots.txt
	

If you cannot access your server's root location you will not be able to use a `robots.txt` file to exclude pages from your index.

The `robots.txt` is a _TEXT_ file (not HTML!) which has a section for each robot to be controlled. Each section has a `user-agent` line which names the robot to be controlled and has a list of "disallows" and "allows". Each disallow will prevent any address that starts with the disallowed string from being accessed. Similarly, each allow will permit any address that starts with the allowed string from being accessed. The (dis)allows are scanned in order, with the last match encountered determining whether an address is allowed to be used or not. If there are no matches at all then the address will be used.

Here's an example:

	   user-agent: FreeFind
	   disallow: /mysite/test/
	   disallow: /mysite/cgi-bin/post.cgi?action=reply
	   disallow: /a
	

In this example the following addresses would be ignored by the spider:

	   http://example.com/mysite/test/index.html
	   http://example.com/mysite/cgi-bin/post.cgi?action=reply&id=1
	   http://example.com/mysite/cgi-bin/post.cgi?action=replytome
	   http://example.com/abc.html
	

and the following ones would be allowed:

	   http://example.com/mysite/test.html
	   http://example.com/mysite/cgi-bin/post.cgi?action=edit
	   http://example.com/mysite/cgi-bin/post.cgi
	   http://example.com/bbc.html
	

It is also possible to use an "allow" in addition to disallows. For example:

	   user-agent: FreeFind
	   disallow: /cgi-bin/
	   allow: /cgi-bin/Ultimate.cgi
	   allow: /cgi-bin/forumdisplay.cgi
	

This `robots.txt` file prevents the spider from accessing every `cgi-bin` address from being accessed _except_ `Ultimate.cgi` and `forumdisplay.cgi`.

Using allows can often simplify your `robots.txt` file.

Here's another example which shows a `robots.txt` with two sections in it. One for "all" robots, and one for the FreeFind spider:

	   user-agent: \*
	   disallow: /cgi-bin/

	   user-agent: FreeFind
	   disallow:
	

In this example all robots except the FreeFind spider will be prevented from accessing files in the `cgi-bin` directory. FreeFind will be able to access all files (a disallow with nothing after it means "allow everything").

Examples

This section has a few handy examples.

_To prevent FreeFind from indexing your site at all:_

	user-agent: FreeFind
	disallow: /
	

_To prevent FreeFind from indexing common Front Page image map junk:_

	user-agent: FreeFind
	disallow: /\_vti\_bin/shtml.exe/
	

_To prevent FreeFind from indexing a test directory and a private file:_

	user-agent: FreeFind
	disallow: /test/
	disallow: private.html
	

_To allow let FreeFind index everything but prevent other robots from accessing certain files:_

	user-agent: \*
	disallow: /cgi-bin/
	disallow: this.html
	disallow: and.html
	disallow: that.html

	user-agent: FreeFind
	disallow:
	

Reference

For full information on the `robots.txt` standard, go to: [http://www.robotstxt.org/](http://www.robotstxt.org/)
