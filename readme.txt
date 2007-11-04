=== Censortive ===
Contributors: DaoByDesign
Donate link: http://www.daobydesign.com/blog/censortive/
URL: http://www.daobydesign.com/
Tags: censorship, text to image
Requires at least: 2.0.2
Tested up to: 2.3
Stable tag: 1.0.0

If your site is being blocked for "sensitive" words, Censortive is for you. This plugin can replace any word or phrase with its graphic equivalent.

== Description ==

Censortive uses text-to-image technology to convert user-defined 'sensitive' words into an image file that blends right into the flow of text. By changing the text into an image file, you effectively negate the censorship robots from being able to 'see' the offending words. Your readers, however, will be able to follow what you're saying without a hitch.

In some countries Internet censorship is at an unjustified level, with a host of Big Brother technologies working to block one of our basic human rights: freedom of speech. The most common way for them to do this is by using bots to scan the text from a site attempting to be viewed. If words deemed inappropriate are found, the viewer is left with an error screen.

However, by converting the words into small, near-identical to the original text, graphic files, the robots have no idea what is being said and happily let it all pass through.

To further increase security, rather than have the sensitive words saved in the post (increasing the chances for a block), users simply use a codeword, which when scanned by the plugin, is replaced by the text2graphic image of the intended word.

== Installation ==

1. Unzip this compressed file to a temporary directory.
2. Edit '/censortive/codewords.dat' in a plain text editor (not Word). Look at the example words on how to add your own. Be sure to maintain the same format (codeword=realword,). Note the comma at the end of each line, including the last line. Save the file.
5. Upload the 'censortive' folder to your '/wp-content/plugins/' directory – making sure to copy the entire folder, and not just the files, maintaining the folder’s directory structure.
6. Activate the plugin through the 'Plugins' menu in WordPress.
7. Navigate to your WordPress Options menu and select the new 'Censortive' option. Adjust the settings to suit your blog's style.

== Frequently Asked Questions ==

= How do I mark words in my posts to be converted? =
Simply enclose the codeword (as defined in 'codeword.dat') in [* and \*]. So if my codeword was 'monkey', I would input [\*monkey\*] in my post. Upon viewing, [\*monkey\*] would be replaced with a text-image of whatever it is equal (=) to in the 'codeword.dat' file.

**Note:** If the example above contains any backslashes (\\), please remove them. They are being used as escape characters in this readme file, and are not to be used when marking codewords in your posts.

= Can I use special characters, such as for other languages? =
For characters with accents or tonal marks, it will largely depend on the font file you’re using and if it supports the characters. For completely different characters, such as Chinese characters or Arabic writing, the answers are more varied. We’ll be working on further functionality in this area in future releases, but currently it’s not supported.

= So, where do I get fonts then? =
We’ve included one font, a Sans-Serif font in the Libertine family - which is an open source font initiative. We don’t distribute a large selection of fonts with the plugin, as they can be quite bulky, and vary greatly from blog to blog.

To make sure your text-to-images are most closely matched to your blog’s content, just find out what TrueType font your posts are set to display at, and then search your computer for those .TTF files. Once found, simply upload the file to the wp-content/plugins/censortive/fonts/ directory and change the Font File setting in the Censortive options.

== Support ==
Please visit the [Censortive page](http://www.daobydesign.com/blog/censortive/) and leave questions in the comments, or e-mail us at the contact address on the site. This plugin is in 'beta' testing, so we do appreciate any feedback or comments you may have.

== Terms &amp; Disclaimer ==
This plugin is released under the [GNU GPL](http://www.gnu.org/licenses/gpl.html/) and is 100% free. However, you are welcome to show your appreciation for the work we've put into this plugin by donating a couple bucks.

Censortive is only a tool for the furtherance of free speech, and it is not foolproof. We cannot be held liable if something you say with the use of this plugin gets you in trouble with "Big Brother". Please use it at your own risk.

Additionally, though we can not begin to imagine how, if the plugin pooches your server/blog/computer/toilet, we're not going to take responsibility for that either.

== Thanks ==
Thanks is owed to Stewart Ulm, of [Moderate Design](http://www.stewartspeak.com/), for creating the original text2image script used in this plugin.