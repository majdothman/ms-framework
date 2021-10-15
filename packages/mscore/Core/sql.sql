--
-- Tabellenstruktur für Tabelle `be_user_groups`
--
CREATE TABLE `be_user_groups`
(
    `uid`   int(11) NOT NULL,
    `titel` varchar(30) COLLATE utf8_unicode_ci NOT NULL
);

--
-- Indizes für die Tabelle `be_user_groups`
--
ALTER TABLE `be_user_groups`
    ADD PRIMARY KEY (`uid`);

--
-- Daten für Tabelle `be_user_groups`
--

INSERT INTO `be_user_groups` (`uid`, `titel`)
VALUES (1, 'Super Administrator'),
       (2, 'Administrator'),
       (3, 'User'),
       (4, 'Editor');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `be_users`
--

CREATE TABLE `be_users`
(
    `uid`            int(11) unsigned NOT NULL,
    `firstname`      varchar(70) COLLATE utf8_unicode_ci  NOT NULL,
    `lastname`       varchar(70) COLLATE utf8_unicode_ci  NOT NULL,
    `email`          varchar(100) COLLATE utf8_unicode_ci NOT NULL,
    `username`       varchar(255) COLLATE utf8_unicode_ci NOT NULL,
    `password`       text,
    `address`        varchar(255) COLLATE utf8_unicode_ci NOT NULL default '',
    `phone`          varchar(255) COLLATE utf8_unicode_ci NOT NULL default '0',
    `privateAnswer`  varchar(255) COLLATE utf8_unicode_ci NOT NULL default '0',
    `info`           text,

    `registerDate`   int(11) default '0' NOT NULL,
    `lastvisitDate`  int(11) default '0' NOT NULL,
    `isActive`       tinyint(1) DEFAULT '0' NOT NULL,
    `idUserGroups`   int(11) DEFAULT '0' NOT NULL,
    `hash_link`      text,
    `crdate`         int(11) default '0' NOT NULL,
    `createdBy`      int(11) default '0' NOT NULL,
    `updated`        int(11) default '0' NOT NULL,
    `updatedBy`      int(11) default '0' NOT NULL,
    `isOnline`       tinyint(1) unsigned default '0' NOT NULL,
    `hidden`         tinyint(1) unsigned default '0' NOT NULL,
    `deleted`        tinyint(1) unsigned default '0' NOT NULL,
    `currentSession` text,
    `token`          text
);

--
-- Indizes für die Tabelle `be_users`
--
ALTER TABLE `be_users`
    ADD PRIMARY KEY (`uid`),
    ADD UNIQUE KEY `username` (`username`);
ALTER TABLE `be_users`
    CHANGE `uid` `uid` INT (11) NOT NULL AUTO_INCREMENT;

--
-- Daten für Tabelle `be_users`
--
INSERT INTO `be_users` (`uid`, `firstname`, `lastname`, `email`, `username`, `password`, `registerDate`,
                        `lastvisitDate`, `isActive`, `idUserGroups`, `hash_link`)
VALUES (1, 'Admin', 'Mscms', 'admin@mscms.example', 'admin', '$2y$10$0dXqKi3VyJkmV/R88Ct03exKZ4hByYkfzEJvRI5QHBjgLr5edK0y6', '2019', '2019', 1, 1, '');



CREATE TABLE `ms_configuration`
(
    `uid`       int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `debug`     int(11) default '0' NOT NULL,

    `crdate`    int(11) default '0' NOT NULL,
    `createdBy` int(11) default '0' NOT NULL,
    `updated`   int(11) default '0' NOT NULL,
    `updatedBy` int(11) default '0' NOT NULL,
    `hidden`    tinyint(1) unsigned default '0' NOT NULL,
    `deleted`   tinyint(1) unsigned default '0' NOT NULL
);
INSERT INTO `ms_configuration`(`uid`)
VALUES (1);



--
-- Tabellenstruktur für Tabelle `ms_languages`
--
CREATE TABLE `ms_languages`
(
    `uid`       int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `shortcut`  varchar(10)  NOT NULL,
    `language`  varchar(255) NOT NULL,
    `crdate`    int(11) default '0' NOT NULL,
    `createdBy` int(11) default '0' NOT NULL,
    `updated`   int(11) default '0' NOT NULL,
    `updatedBy` int(11) default '0' NOT NULL,
    `hidden`    tinyint(1) unsigned default '0' NOT NULL,
    `deleted`   tinyint(1) unsigned default '0' NOT NULL
);

--
-- Daten für Tabelle `ms_languages`
--

INSERT INTO `ms_languages` (`shortcut`, `language`)
VALUES ('EN', 'English');
