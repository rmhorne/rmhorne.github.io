---
title: "Gephi, Conspiracies, and SNA in the Classroom: Midterm Thoughts"
date: "2016-09-27"
categories: 
  - "social-networks"
tags: 
  - "social-networks"
coverImage: "layout2.png"
---

###### Image from https://gephi.org/images/screenshots/layout2.png

This semester I designed a class, _Introduction to Social Networks and Conspiracy Theories_, that makes extensive use of [Gephi](https://gephi.org/) along with the [downloadable version](https://www.cs.cornell.edu/home/kleinber/networks-book/) of _Networks, Crowds, and Markets: Reasoning About a Highly Connected World_ by David Easley and Jon Kleinberg. Readings on real conspiracies and conspiracy theories were compiled by myself into a course packet, and cover Ancient Athens, the assassination of Philip II, Knights Templar, the Gunpowder plot, the French Revolution, and conspiracy theories in the United States from the revolution to the present. In short, this class uses social network analysis to study paranoia from Plato to NATO.

Key to this class is the understanding and use of SNA software. I chose Gephi as it has a forgiving learning curve for creating networks and conducting basic analysis, and its cross platform capabilities were required as I do not have access to a computer lab for the class. The other deciding factor was the ease of exporting Gephi files (through the use of the excellent [Sigmajs exporter](https://marketplace.gephi.org/plugin/sigmajs-exporter/)) to the web, as the students will produce a number of publicly available network visualizations, in addition to a written report, for their final project.

Gephi has shown its usefulness to the class. The ability to very quickly take .csv files and make meaningful network diagrams impressed the students, and showing network filtering in real-time is a powerful way to show conceptually how eliminating bridges and key nodes can throw a network into confusion. Some other positive points:

**Gephi's GUI vs. command line tools** For my students, using a GUI has been a far better choice than a command-line or text driven interface. While the Gephi GUI can sometimes do strange things (like eliminate buttons or workspaces), on the whole its basic functionality is relatively intuitive. After a few demonstrations in the basics, the students have grasped how to create a network from spreadsheet data.

**Real-time rendering** Keeping the various layouts running while filtering / changing elements of the network (especially the stand-by force atlas) powerfully illustrates many network concepts. It is also a very cheap (in time and effort!) method to create animated networks for the class.

**Ease of stats** While some of the statistics I would like to see are not in the core of Gephi, the ones that are present are excellent. The students, after learning about the math and logic behind various network statistics, were quite relieved to discover how quickly Gephi can compute centrality, density, and degree measurements.

**Styling** After spending some time going over the interface, the ease of selecting different attributes and measurements for node styling is something that really captured the student's attention. I anticipate a flood of very interesting network diagrams for their final projects based on different styling / visualization choices, which is an excellent way for students to support their arguments.

**Creating diagrams for the course** Using Gephi to create network diagrams for the conspiracy portion of the course is a very straightforward process, and the excellent export capabilities ensure that all of the networks I share look very professional.

While Gephi is an excellent piece of software, extensive use in the classroom has revealed some issues and missing features that do present a source of frustration for the class.

**Java can be difficult** Supporting multiple operating systems with different Java installs on student laptops is an exercise in frustration. A class that uses Gephi extensively MUST have a supported computer lab, at the very least so that Java problems can be addressed and fixed for everyone at the same time in the same way. I am running my course without this, and I can attest that much class time has been wasted trying to troubleshoot Java and install issues on different OS / JVM combinations.

**Gephi is not very fault tolerant ** Data, at least in the humanities, is often messy, malformed, and non standards-compliant. I was stymied in class due to one character causing an issue in a data set that we found online - while text programs and Excel / OpenOffice handled the file gracefully, it blew up on Gephi.

**Many of the concepts discussed in SNA texts can not be easily seen in Gephi** Concepts like triadic closure are somewhat difficult to capture, but there is nothing in Gephi to identify triads. It can compute the total number, but this is less useful for showing students where the triads are in a graph. I could also not find a way to view cliques, or to identify bridges programatically. Network balance is also something that is not readily apparent in Gephi.

**Filtering can be difficult** While there are some powerful filtering features in Gephi, the class has had a difficult time conceptualizing their use and using them to their full potential. A more intuitive interface may solve some of these problems.

**Some features are Broken** Embeddedness is not a core feature for Gephi, and the plugin that computes this is incompatible with the current version of the code. In addition, filtering on partition for edges does not seem to currently work - this makes identification of cliques and balanced graphs more difficult. Along with this, Gephi can be very unstable at times, and some workarounds (like exporting a newly created graph and re-importing it to ensure compatibility with multiple edges) can be a hassle.

**Summary** In short, I think Gephi is a good choice for the classroom, but one that will require some serious work from the instructor. I would HIGHLY recommend that you teach Gephi in a classroom setting, where JVM and OS choices are restricted and supported by IT staff. I would like to see more educators using Gephi so we can pressure the developers (or encourage interested students!) to add more functionality to the core of the software.
