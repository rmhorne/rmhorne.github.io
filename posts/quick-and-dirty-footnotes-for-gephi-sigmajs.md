---
title: "Quick and Dirty Footnotes For Gephi / SigmaJS"
date: "2015-10-31"
categories: 
  - "code"
  - "social-networks"
tags: 
  - "gephi"
  - "social-networks"
---

Before I begin, I once again want to recognize the excellent [SigmaJS Exporter](https://marketplace.gephi.org/plugin/sigmajs-exporter/) plugin for [Gephi](https://gephi.github.io/). This really does mitigate a lot of the grunt work involved in quickly making a usable, interactive social network graph. However, sometimes you just want another feature or some further refinement - in my case adding workable footnotes to information on each node.

For those of us in the humanities, citations are _sine qua non_ for scholarship. However, there are few good was to maintain linkable citations on the web that are not hardcoded beforehand, or reliant on javascript trickery. What I wanted to do was find a tool or a method to easily move text and citations contained in my dissertation to a description field in a [Gephi](https://gephi.github.io/)\-based application without manually entering footnotes, footnote numbers, or linking them myself, as I have over 2,000 footnotes to deal with.

What I found is a bit of a hack, and certainly can be improved, but it works. First, you are going to want to have your document in a format that is readable by OpenOffice / LibreOffice / etc. What you need to do is select the bit of text you are interested in, dump it into a new file (making sure to include your footnotes!) and then export that file as XHTML.

[![export-text](https://ryanmatthewhorne.files.wordpress.com/2015/09/screen-shot-2015-09-26-at-9-51-26-pm.png?w=300)](https://ryanmatthewhorne.files.wordpress.com/2015/09/screen-shot-2015-09-26-at-9-51-26-pm.png)Once this is complete, you will have a lovely, fully encapsulated xml file of your text - including all formatting, footnotes, etc. However, we want to eliminate some of the elements produced by this process. Open this file in your favorite text editor. You will notice that you have code similar to the following at the top of the document:

\[code language="html"\]

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1 plus MathML 2.0//EN" "http://www.w3.org/Math/DTD/mathml2/xhtml-math11-f.dtd"> <html xmlns="http://www.w3.org/1999/xhtml"> <!--This file was converted to xhtml by LibreOffice - see http://cgit.freedesktop.org/libreoffice/core/tree/filter/source/xslt for the code.--> <head profile="http://dublincore.org/documents/dcmi-terms/"> <meta http-equiv="Content-Type" content="application/xhtml+xml; charset=utf-8"/> <title xml:lang="en-US">- no title specified</title> <meta name="DCTERMS.title" content="" xml:lang="en-US"/> <meta name="DCTERMS.language" content="en-US" scheme="DCTERMS.RFC4646"/> <meta name="DCTERMS.source" content="http://xml.openoffice.org/odf2xhtml"/> <meta name="DCTERMS.issued" content="2015-09-26T21:09:50.283345000" scheme="DCTERMS.W3CDTF"/> <meta name="DCTERMS.modified" content="2015-09-26T21:23:01.904161000" scheme="DCTERMS.W3CDTF"/> <meta name="DCTERMS.provenance" content="" xml:lang="en-US"/> <meta name="DCTERMS.subject" content="," xml:lang="en-US"/> <link rel="schema.DC" href="http://purl.org/dc/elements/1.1/" hreflang="en"/> <link rel="schema.DCTERMS" href="http://purl.org/dc/terms/" hreflang="en"/> <link rel="schema.DCTYPE" href="http://purl.org/dc/dcmitype/" hreflang="en"/> <link rel="schema.DCAM" href="http://purl.org/dc/dcam/" hreflang="en"/> \[/code\]

This can all be eliminated. Make sure you retain the

\[code language="html"\]

<style type="text/css"> \[/code\]

tag at the end of the line.

Likewise, delete this from the start of the last line:

\[code language="html"\] </head><body dir="ltr" style="max-width:8.5in;margin-top:0.7874in; margin-bottom:0.7874in; margin-left:0.7874in; margin-right:0.7874in; writing-mode:lr-tb; "> \[/code\]

and this from the end of the last line:

\[code language="html"\] </body></html> \[/code\]

Now, simply paste what is left into a field in your Gephi data.

[![Gephi with footnotes](https://ryanmatthewhorne.files.wordpress.com/2015/10/screen-shot-2015-09-26-at-9-50-21-pm.png?w=300)](https://ryanmatthewhorne.files.wordpress.com/2015/10/screen-shot-2015-09-26-at-9-50-21-pm.png)

Export as usual, and viola! you have your clickable, interactive footnotes.

[![footnote-gephi](https://ryanmatthewhorne.files.wordpress.com/2015/10/footnote-gephi.png?w=300)](https://ryanmatthewhorne.files.wordpress.com/2015/10/footnote-gephi.png)

Now, this is good for a quick and dirty solution, but it would require a modification of the json datafile if you ever make a change or wish to add more information (or, even worse, a re-export of your entire network). As such, this solution will not be used for BAM, as we are seeking a more flexible and modifiable code base.
