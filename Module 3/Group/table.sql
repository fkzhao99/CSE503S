CREATE TABLE `user` (
 `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
 `username` varchar(50) NOT NULL,
 `password` varchar(200) NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8


CREATE TABLE `story` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `userId` int(11) unsigned NOT NULL,
 `title` varchar(250) NOT NULL,
 `content` longtext NOT NULL,
 `link` longtext NOT NULL,
 `time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
 PRIMARY KEY (`id`),
 KEY `userId` (`userId`),
 CONSTRAINT `story_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8


CREATE TABLE `comment` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `userId` int(11) unsigned DEFAULT NULL,
 `storyId` int(11) NOT NULL,
 `content` longtext NOT NULL,
 `time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
 PRIMARY KEY (`id`),
 KEY `userId` (`userId`),
 KEY `storyId` (`storyId`),
 CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `user` (`id`),
 CONSTRAINT `comment_ibfk_2` FOREIGN KEY (`storyId`) REFERENCES `story` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8