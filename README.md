
![Globalvision](http://vr360.globalvision.ch/assets/images/gv_logo.png)
----------
VR360
===================

Changelogs

**2.0.0**
 - **Refactored whole system** with MVC supported
 - Upgraded to krpano version 1.19
 - Use Javascript object
 - Implemented SEO for tour view
 - Move data.json into database
 - Database optimized
 - UI improved
 - New session system
 - New configuration system
 - Email sending
 - Searching

**2.1.0**

- Remove JSON. Everything stored directly into database
- Bugs fixing
- Performance improved
- Scenes ordering with drag & drop
- Automate testing
- Prevent close modal without pressing Close
- JS validate onfly
----------
How to install
- Setup database
- Create tables

~~~~
`
CREATE TABLE `hotspots` (
  `id` int(11) NOT NULL,
  `sceneId` int(11) NOT NULL DEFAULT '0',
  `code` varchar(50) NOT NULL DEFAULT '',
  `ath` varchar(50) NOT NULL DEFAULT '',
  `atv` varchar(50) NOT NULL DEFAULT '',
  `style` varchar(10) DEFAULT NULL,
  `type` varchar(10) DEFAULT NULL,
  `params` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `name` varchar(255) NOT NULL,
  `password` mediumtext NOT NULL,
  `email` varchar(125) NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `last_visit` datetime DEFAULT NULL,
  `params` mediumtext NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

CREATE TABLE `v2_scenes` (
  `id` int(11) NOT NULL,
  `tourId` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `file` varchar(255) NOT NULL,
  `ordering` int(11) DEFAULT NULL,
  `default` tinyint(4) NOT NULL,
  `status` int(11) NOT NULL,
  `params` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `v2_tours` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `keyword` varchar(255) NOT NULL,
  `ordering` int(11) DEFAULT NULL,
  `created` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `params` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `hotspots`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `v2_scenes`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `v2_tours`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `alias` (`alias`);
ALTER TABLE `hotspots`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
ALTER TABLE `v2_scenes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
ALTER TABLE `v2_tours`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
COMMIT;
`
~~~~
- Download Krpano and extract to ./krpano directory
- Setup configuration & krpano license
