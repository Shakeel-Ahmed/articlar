SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";
CREATE TABLE `author` (
`author-id` varchar(12) NOT NULL,
`name` tinytext NOT NULL,
`email` tinytext NOT NULL,
`about` text NOT NULL,
`views` int(11) NOT NULL,
`picture` tinytext,
`d_n_t` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
`t_n_d` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
CREATE TABLE `log` (
`log-id` int(24) NOT NULL,
`page-id` varchar(12) NOT NULL,
`author-id` varchar(12) NOT NULL,
`country` varchar(4) DEFAULT NULL,
`d_n_t` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
CREATE TABLE `login-editor` (
`editor-id` varchar(12) NOT NULL,
`login` tinytext NOT NULL,
`hash` tinytext NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `login-recover` (
`token` varchar(50) NOT NULL,
`email` tinytext NOT NULL,
`expiry` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
CREATE TABLE `login-writer` (
`author-id` varchar(12) NOT NULL,
`login` tinytext NOT NULL,
`hash` tinytext NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
CREATE TABLE `page` (
`page-id` varchar(12) NOT NULL,
`author-id` varchar(12) NOT NULL,
`status` varchar(24) NOT NULL DEFAULT '1',
`title` tinytext NOT NULL,
`keywords` tinytext NOT NULL,
`description` tinytext NOT NULL,
`header` text,
`body` mediumtext NOT NULL,
`footer` text,
`image` varchar(100) DEFAULT NULL,
`views` int(11) NOT NULL,
`d_n_t` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
`t_n_d` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='status 1=unpublished, 2=under review, 3=published, 4= homepage';
CREATE TABLE `settings` (
`settings-id` int(11) NOT NULL,
`name` tinytext NOT NULL,
`owner` tinytext NOT NULL,
`owner-email` tinytext NOT NULL,
`email` tinytext NOT NULL,
`address` mediumtext NOT NULL,
`baseurl` tinytext NOT NULL,
`site-logo` tinytext,
`default-image` tinytext NOT NULL,
`product` tinytext NOT NULL,
`version` float NOT NULL,
`lic-key` tinytext NOT NULL,
`d_n_t` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
CREATE TABLE `theme` (
`theme-id` varchar(255) NOT NULL,
`author` tinytext NOT NULL,
`title` tinytext,
`about` text NOT NULL,
`styles` tinytext NOT NULL,
`template` mediumtext,
`template-blog` mediumtext,
`back` text,
`backward` text,
`first` text,
`next` text,
`forward` text,
`last` text,
`seprator` text,
`status` varchar(16) NOT NULL DEFAULT 'inactive',
`image` tinytext,
`d_n_t` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
`t_n_d` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
ALTER TABLE `author` ADD UNIQUE KEY `author-id` (`author-id`);
ALTER TABLE `author` ADD FULLTEXT KEY `name` (`name`);
ALTER TABLE `author` ADD FULLTEXT KEY `email` (`email`);
ALTER TABLE `log` ADD PRIMARY KEY (`log-id`);
ALTER TABLE `login-editor` ADD UNIQUE KEY `admin_id` (`editor-id`);
ALTER TABLE `login-recover` ADD UNIQUE KEY `token` (`token`);
ALTER TABLE `login-writer` ADD UNIQUE KEY `admin_id` (`author-id`);
ALTER TABLE `page` ADD UNIQUE KEY `page-id` (`page-id`);
ALTER TABLE `page` ADD FULLTEXT KEY `title keywords` (`title`,`keywords`);
ALTER TABLE `settings` ADD PRIMARY KEY (`settings-id`);
ALTER TABLE `theme` ADD UNIQUE KEY `theme-id` (`theme-id`);
ALTER TABLE `theme` ADD FULLTEXT KEY `title` (`title`);
ALTER TABLE `log` MODIFY `log-id` int(24) NOT NULL AUTO_INCREMENT;
ALTER TABLE `settings` MODIFY `settings-id` int(11) NOT NULL AUTO_INCREMENT;