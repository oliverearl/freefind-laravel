# Setup for Non-English Languages -- FreeFind.com

Setup for Non-English Languages

It is possible to change the language of the prompts shown in the search results. This document describes the process and which languages are currently available.

Overview

To change the language of the search result prompts it is necessary to add an additional html tag to your search panel. This html tag specifies the language for the prompts.

As an example, if you wanted to change your search result prompts to Spanish, you would add the tag:

		<input type="hidden" name="lang" value="es">
	

to your search panel. This tag must be placed _after_ the search panel's initial

		<form ...
	

tag and _before_ it's ending

		</form>
	

tag, otherwise it will not work.

We recommend using a text editor to modify your search panel as many HTML editors modify added html code without notifying you, resulting in unpredictable results.

If the language code ("es", "fr", "it", etc) is not recognized or supported, English prompts will be used.

Note that currently the web search results always show English prompts.

Supported Languages

The following table lists the currently supported languages.

Language

HTML Tag

Bulgarian

<input type=hidden name=lang value=bg>

Catalan

<input type=hidden name=lang value=ca>

Chinese, Simplified

<input type=hidden name=lang value=zh>

Chinese, Traditional

<input type=hidden name=lang value=zh2>

Chinese, Traditional

<input type=hidden name=lang value=zh3>

Czech

<input type=hidden name=lang value=cs>

Danish

<input type=hidden name=lang value=da>

Dutch

<input type=hidden name=lang value=nl>

French

<input type=hidden name=lang value=fr>

German

<input type=hidden name=lang value=de>

Greek

<input type=hidden name=lang value=el>

Hungarian

<input type=hidden name=lang value=hu>

Italian

<input type=hidden name=lang value=it>

Norwegian

<input type=hidden name=lang value=no>

Romanian

<input type=hidden name=lang value=ro>

Romanian

<input type=hidden name=lang value=ro2>

Russian

<input type=hidden name=lang value=ru>

Spanish

<input type=hidden name=lang value=es>

Swedish

<input type=hidden name=lang value=sv>

Polish

<input type=hidden name=lang value=pl>

Portuguese

<input type=hidden name=lang value=pt>

Turkish

<input type=hidden name=lang value=tr>

Ukranian

<input type=hidden name=lang value=uk>

If the language you are looking for is not here and you are a native speaker willing to do the translation, please [contact us](https://freefind.com/contact.html).

Translation Credits

The following people translated or contributed to the translation of the search prompts:

Adam Roberto Albanesi Sebastian Brinkmann Radu Capan Ferran Clavell Sorin Cosma Alex Dantart Hakan Dogan Stan Drozdowski

Juergen Hinrichs-Roehring Angus Ho Xu Huimin Alexander Kachanov Olga Kapnitsi Kim Malchau M. R. Mallee Yaroslav Neveliuk

Lars Christian Ragus Eddy Ribeyrol Thomas Riesler Antonin Slejska Slavko Todorov Levente Vero Yan Andre Zielasko

We thank them for their assistance!
