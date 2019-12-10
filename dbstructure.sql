-- Posts

CREATE TABLE `yasfu_posts` (
  `ID` int(11) NOT NULL AUTOINCREMENT PRIMARY KEY,
  `posterID` int(11) NOT NULL,
  `content` blob NOT NULL,
  `title` varchar(255) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Users
-- A couple of fields aren't used yet.

CREATE TABLE `yasfu_users` (
  `ID` int(11) NOT NULL AUTOINCREMENT PRIMARY KEY,
  `username` varchar(30) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rank` int(11) NOT NULL DEFAULT '0',
  `lastpost` timestamp NULL DEFAULT NULL,
  `joined` datetime DEFAULT CURRENT_TIMESTAMP,
  `profileimg` varchar(255) DEFAULT NULL,
  `bio` varchar(1000) DEFAULT NULL,
  `gender` tinyint(4) DEFAULT NULL,
  `birthday` datetime DEFAULT NULL,
  `location` varchar(200) DEFAULT NULL,
  `realname` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Unverified Users

CREATE TABLE `yasfu_unverified_users` (
  `ID` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `username` varchar(30) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` varchar(255) NOT NULL,
  `joined` datetime DEFAULT CURRENT_TIMESTAMP,
  `vkey` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
