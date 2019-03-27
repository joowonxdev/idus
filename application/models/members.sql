CREATE TABLE `members` (
  `mem_idx` int(11) UNSIGNED AUTO_INCREMENT ,
  `email` varchar(255)  NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `mobile` varchar(13) NOT NULL,
  `mobile_check` enum('Y', 'N') NOT NULL DEFAULT 'N' ,
  `member_code` varchar(25)  NOT NULL,
  `recommend_code` varchar(25),
  `service_agree` enum('Y', 'N')  NOT NULL,
  `privacy_agree` enum('Y', 'N')  NOT NULL,
  `commercial_agree` enum('Y', 'N')  NOT NULL,
  `createdAt` datetime NOT NULL DEFAULT NOW(),
  `updatedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`mem_idx`)
);