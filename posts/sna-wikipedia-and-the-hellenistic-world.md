---
title: "SNA, Wikipedia, and the Hellenistic World"
date: "2017-04-26"
coverImage: "web.jpg"
---

Part of my work on the _Big Ancient Mediterranean_ project involves creating a general software framework that can display social networks produced with Gephi, either as "stand alone" displays or integrated with geographic and textual information.

I created [this particular module, _"Hellenistic" Royal Relationships_](https://rmhorne.github.io/bam/hellenisticwiki/index.html), to highlight the "stand alone" social network analysis (SNA) capabilities of BAM, and to serve as the start of a more generalized Hellenistic prosopography. Some other, more specialized work has been done in this direction; notably [Trismegistos Networks](http://www.trismegistos.org/network/index.php) and the efforts of [SNAP:DRGN](http://snapdrgn.net/) to create data standards for describing prosopographies and linking to other projects. Eventually this module will take advantage of these efforts, and provide stable URIs for its own data.

I envision this module serving several purposes. First, it provides an interesting visual representation of data contained within [Wikipedia](https://www.wikipedia.org/) articles, including textual data that is not "linked" to other entries  and therefore not discoverable by automated means. It serves as a quick reference for familial relationships, and provides an entry point for further exploration and study. This project has created a "core" of relationships that can be further expanded by different projects. It also can function as a check on [Wikipedia](https://www.wikipedia.org/) data; some of the relationships here are highly controversial, or could even be wrong.

For future development, the next steps are to add more data on the subjects, including birth / death / reigning dates and a time-line browser based on those dates. As mentioned above, more work needs to be done to take advantage of linked data projects, including linkages to [Pleiades](https://pleiades.stoa.org/) locations where appropriate, linkages to [Nomisma](http://nomisma.org/) IDs if the monarch minted coins, and the presentation of the underlying data in a format that is compatible with [SNAP:DRGN](http://snapdrgn.net/). Finally, I would like to develop a method for the automatic discovery and extraction of relationships described in [Wikipedia](https://www.wikipedia.org/) articles, which is an interesting, but difficult, problem.
