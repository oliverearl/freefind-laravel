# Searching Forums -- FreeFind.com

Searching Forums

Forums are a popular part of many websites and, with a little care, can be included in your site's index without problem.

Overview

Like many types of dynamically-generated site content, forums include many types of pages that you do not want to include in your index. Examples might include topic reply, registration, edit post, new topic, delete post, and send thread pages. Often these "junk" pages outnumber the good pages 10-to-1! If you do include these pages you may end up indexing thousands of extra pages and your search results may contain irrelevant results.

In order to precisely index your forum you will need to:

-   Determine the "good" parts of your forum (i.e. the parts you want indexed),
-   Determine the "bad" parts of your forum (i.e. the parts you do not want indexed), and
-   Exclude the search engine spider (indexer) from the "bad" parts of your forum.

The following sections cover these steps.

Determining the "Good" and the "Bad"

The way we usually do this is by simply going to the forum and looking! Using a text editor, I create a file with "good" and "bad" section headings in it. Then I go to the forum and gather an example address for each type of page the forum has, placing each address in the proper section (good or bad). Typically you end up with a couple good addresses and lots of bad ones.

Another good way of finding some (but not all) bad pages is to search your site for `"password"`. Many administration type pages require the user to enter a password, so this search can show these types of pages.

When you have a your list together, then you can move to the next section: _Excluding Pages_.

Excluding Pages

For detailed help on how to exclude pages from your index see [_Excluding Pages using the Control Center_](https://freefind.com/library/howto/exclude/#cc).

Here is a quick example for review. If you had these exclusions:

		/test/\*
		/cgi-bin/postings.cgi?action=reply\*
		/a\*
	

then the following addresses would be ignored by the spider:

		http://example.com/test/index.html
		http://example.com/cgi-bin/postings.cgi?action=reply&id=123
		http://example.com/cgi-bin/postings.cgi?action=replytothis
		http://example.com/abc.html
	

and the following ones would be allowed:

		http://example.com/test.html
		http://example.com/cgi-bin/postings.cgi?action=edit
		http://example.com/cgi-bin/postings.cgi
		http://example.com/bbc.html
	

It is also possible to first disallow a whole section of your site (the forum) then selectively "allow" certain parts to be included in the index. For example:

		/cgi-bin/\* index=no follow=yes
		/cgi-bin/Ultimate.cgi\* index=yes follow=yes
		/cgi-bin/forumdisplay.cgi\* index=yes follow=yes
	

These exclusions prevent the spider from accessing every `cgi-bin` address from being accessed _except_ `Ultimate.cgi` and `forumdisplay.cgi`.

Using exclusions structured in this manner can often simplify things.

Now that you know how to prevent pages from being indexed, and you have a list of "good" and "bad" page types, you will need to create your list of exclusions. You do not use the entire sample addresses, but just enough to uniquely exclude the _type_ of page.

The following sections have exclusion lists for some popular forums.

Ultimate Bulletin Board

If you are using version 6 or above, use the following exclusions as a starting point for indexing this forum:

		/cgi-bin/ultimatebb.cgi\*
		/cgi-bin/ultimatebb.cgi index=yes follow=yes
		/cgi-bin/ultimatebb.cgi?ubb=forum\* index=yes follow=yes
		/cgi-bin/ultimatebb.cgi?ubb=get\_topic\* index=yes follow=yes
	

If you are using version 5 or below, use the following exclusions as a starting point for indexing this forum:

		/cgi-bin/ubbmisc.cgi\*
		/cgi-bin/Ultimate.cgi?\*
		/cgi-bin/postings.cgi\*
		/cgi-bin/archive.cgi\*
		/cgi-bin/search.cgi\*
	

You may have to change these examples to match your exact configuration.

UltraBoard

Use the following exclusions as a starting point for indexing this forum:

		/board/UltraBoard.cgi?action=Post\*
		/board/UltraBoard.cgi?action=ModifyPost\*
		/board/UltraBoard.cgi?action=Login\*
		/board/UltraBoard.cgi?action=Register\*
		/board/UltraBoard.cgi?action=Search\*
		/board/UltraBoard.cgi?action=Help\*
		/board/UltraBoard.cgi?action=Online\*
		/board/UltraBoard.cgi?action=Profile\*
		/board/UltraBoard.cgi?action=Email\*
		/board/UltraBoard.cgi?action=ICQ\*
		/board/UltraBoard.cgi?action=Reply\*
		/board/UltraBoard.cgi?action=Print\*
		/board/UltraBoard.cgi?action=Forward\*
		/board/UltraBoard.cgi?action=TopicCommands\*
		/board/UltraBoard.cgi?action=Reply\*
		/board/UltraBoard.cgi?action=Result&tmp=\*
	

You may have to change this example to match your exact configuration.

vBulletin

Use the following exclusions as a starting point for indexing this forum:

		/forum/showthread.php?goto=\*
		/forum/postings.php\*
		/forum/newreply.php\*
		/forum/newthread.php\*
		/forum/poll.php\*
		/forum/private.php\*
		/forum/member.php?action=signup\*
		/forum/member.php?action=editprofile\*
		/forum/member.php?action=mailform\*
		/forum/member.php?action=clearcookies\*
		/forum/search.php\*
		/forum/sendtofriend.php\*
		/forum/printthread.php\*
		/forum/memberlist.php?action=search\*
		/forum/memberlist.php?what=topposters&perpage=25\*
		/forum/memberlist.php?what=datejoined\*
	

You may have to change this example to match your exact configuration.

Ikonboard

Use the following exclusions as a starting point for indexing this forum:

		/cgi-bin/forum/register.cgi\*
		/cgi-bin/forum/profile.cgi\*
		/cgi-bin/forum/loginout.cgi\*
		/cgi-bin/forum/whosonline.cgi\*
		/cgi-bin/forum/search.cgi\*
		/cgi-bin/forum/post.cgi\*
		/cgi-bin/forum/postings.cgi\*
		/cgi-bin/forum/messanger.cgi\*
		/cgi-bin/forum/ikonfriend.cgi\*
		/cgi-bin/forum/printpage.cgi\*
		/cgi-bin/forum/announcements.cgi?action=\*
		/cgi-bin/forum/forums.cgi?forum=?&action=resetposts\*
		/cgi-bin/forum/forums.cgi?forum=??&action=resetposts\*
	

You may have to change this example to match your exact configuration.

Discus

Use the following exclusions as a starting point for indexing this forum:

		/discus/board-menu.html\*
		/discus/board-profile.html\*
		/discus/board-profile1.html\*
		/cgi-bin/discus/board-newmessages.cgi\*
		/cgi-bin/discus/board-viewtree.cgi\*
		/cgi-bin/discus/board-profile.cgi\*
		/cgi-bin/discus/board-admin-menuonly.cgi\*
		/cgi-bin/discus/board-admin-3.cgi\*
	

You may have to change this example to match your exact configuration.

Board Power

Use the following exclusions as a starting point for indexing this forum:

		/cgi-bin/forums/post.cgi?action=reply\*
		/cgi-bin/forums/post.cgi?action=new\*
		/cgi-bin/forums/misc2.cgi?action=startbccform\*
		/cgi-bin/forums/register.cgi\*
		/cgi-bin/forums/login.cgi\*
		/cgi-bin/forums/profile.cgi?action=lostpassword\*
		/cgi-bin/forums/search.cgi\*
		/cgi-bin/forums/controls.cgi\*
		/cgi-bin/forums/boardpower.cgi?cookie=logout\*
		/cgi-bin/forums/online.cgi\*
		/cgi-bin/forums/boardpower.cgi?cookie=reset\*
		/cgi-bin/forums/poll.cgi?action=newpoll\*
		/cgi-bin/forums/poll.cgi?action=viewpoll\*
		/cgi-bin/forums/misc.cgi?action=editpostform\*
		/cgi-bin/forums/misc.cgi?action=replyquote\*
		/cgi-bin/forums/misc.cgi?action=amoptions\*
	

You may have to change this example to match your exact configuration.

MSN Messageboard

Note: these instructions are for a FreeFind account that indexes the messageboard ONLY. Other parts of your MSN site will not be indexed.

1.  Set your exclusions to the following:
    
    		\*
    		\*all\_topics=1\* index=yes follow=yes
    		\*mview=1\*
    		\*ddir=\*
    		\*cdir=\*
    		\*&cdir ="-1"\*all\_topics=1\* index=yes follow=yes
    		\*&dir=1\*
    		\*ctype=\*
    		\*action=get\_threads&all\_topics=1
    		\*&openpopup=\*
    	
    
2.  Now change your account's address. If your MSN site was named "Me", the address you should us would be:
    
    		http://communities.msn.com/Me/messageboard.msnw?all\_topics=1
    	
    
    NOTE: Your need to change the "Me" to the appropriate name for your site!

That's it. At this point FreeFind will be indexing your messsageboard and will email you with the results when it has finished.

Note that due to the unusually large average page size of MSN community pages, you will not be able to index very many pages of your messageboard with a free account. Also, since the original message of each topic is repeated for each page of that topic (i.e. when you keep clicking "prev" the first message remains the same), if the search finds the words it's looking for in that message you will get all of the topic's pages in the search results.
