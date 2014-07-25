
CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(45) NOT NULL DEFAULT '0',
  `user_agent` varchar(120) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text NOT NULL,
  PRIMARY KEY (`session_id`),
  KEY `last_activity_idx` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- split --

CREATE TABLE IF NOT EXISTS `comments` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `UserID` bigint(20) NOT NULL,
  `ParentID` bigint(20) DEFAULT NULL,
  `SuggestionID` bigint(20) NOT NULL,
  `Comment` text COLLATE utf8_unicode_ci NOT NULL,
  `AddedOn` datetime NOT NULL,
  `Status` text NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

-- split --

CREATE TABLE IF NOT EXISTS `emailtemplates` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `Template_Name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `Subject` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `Content` text COLLATE utf8_unicode_ci NOT NULL,
  `Help` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=9;

-- split --

INSERT INTO `emailtemplates` (`ID`, `Template_Name`, `Subject`, `Content`, `Help`) VALUES
(1, 'Registration Email', 'Account Activation', 'Hello {username}\r\nPlease verify your email with this link {link}\r\nThank You\r\n\r\n\r\n', 'This message is used to send Registration Verification Email, when Configuration "Registration Verification" is set to TRUE'),
(2, 'Welcome Email', 'Welcome ', 'Welcome {username} !\n', 'Welcome Message'),
(3, 'Notify Admin', 'New User Registration', 'Hello Admin\r\nNew user has been registered\r\n\r\nUsername: {username}\r\nemail: {email}\r\n',''),
(5, 'Status Changed', 'Status Update', 'Hello {username} \n{suggestioname}\n{laststatus} To {status} \n\n{link}\n',''),
(6, 'New Comment', 'New Comment', 'Hello {username}\n\n{comment_author} has commented on {suggestionTitle}\n{link}\n\n','');
(7, 'New Suggestion', 'A New suggestion has been posted','Hello admin,\r\n\n {authorname} has posted a new suggestion {link}',''),
(8, 'New Registration', 'New User Registration','Hello Admin\r\n\nNew user has been registered\r\n\nUsername: {username}\r\n\nemail: {email}','');

-- split --

CREATE TABLE IF NOT EXISTS `settings` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `OptionName` text NOT NULL,
  `OptionValue` text NOT NULL,
  `Default` text NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `ID` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=10;

-- split --

INSERT INTO `settings` (`ID`, `OptionName`, `OptionValue`, `Default`) VALUES
(1, 'Email_Val', 'FALSE', 'FALSE'),
(2, 'Allow_New_Reg', 'TRUE', 'TRUE'),
(3, 'NotifyAdmin', 'TRUE', 'TRUE'),
(4, 'Admin_Approval', 'FALSE', 'FALSE'),
(5, 'Max_votes', '15', '15'),
(6, 'Website_Address', '{WEBSITE_ADDR}' ,''),
(7, 'Admin_Email','{ADMIN_EMAIL}',''),
(8, 'SERVER_EMAIL','{SERVER_EMAIL}',''),
(9, 'FacebookLogin','{FB_SOCIAL_CONNECT}',''),
(10, 'AnonymousPosting','FALSE','FALSE');

-- split --

CREATE TABLE IF NOT EXISTS `suggestions` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `UserID` bigint(20) DEFAULT NULL,
  `Title` text COLLATE utf8_unicode_ci NOT NULL,
  `Slug` text COLLATE utf8_unicode_ci NOT NULL,
  `Description` text COLLATE utf8_unicode_ci NOT NULL,
  `category_id` BIGINT NULL DEFAULT NULL,
  `Total_votes` bigint(20) DEFAULT NULL,
  `CreatedOn` datetime NOT NULL,
  `UpdatedOn` datetime DEFAULT NULL,
  `Last_status` int(11) DEFAULT NULL,
  `status_updated_on` datetime DEFAULT NULL,
  `Status` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

-- split --

CREATE TABLE IF NOT EXISTS `users` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `Username` varchar(50) NOT NULL,
  `Password` varchar(50) NOT NULL,
  `SALT` text NOT NULL,
  `email` varchar(32) NOT NULL,
  `user_role` int(11) NOT NULL,
  `Token` varchar(64) NOT NULL,
  `CreatedOn` datetime NOT NULL,
  `LastLogin` datetime NOT NULL,
  `Lastlogout` datetime NULL DEFAULT NULL,
  `Status` int(11) NOT NULL,
  `Activation_Code` text NOT NULL,
  `LastIP` varchar(16) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

-- split --

INSERT INTO `users` (`ID`, `Username`, `Password`, `SALT`, `email`, `user_role`, `Token`, `CreatedOn`, `LastLogin`, `Status`, `Activation_Code`, `LastIP`) VALUES
    (1,'{USERNAME}', '{PASSWORD}', '{SALT}', '{EMAIL}', 0, '', '{NOW}', '{NOW}', 0,'', '{IP}');

-- split --

CREATE TABLE IF NOT EXISTS `users_info` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `UserId` int(11) NOT NULL,
  `votesleft` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

-- split --

CREATE TABLE IF NOT EXISTS `user_settings` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `UserID` bigint(20) NOT NULL,
  `live_status` text,
  `new_sug` text,
  `new_reg` text,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

-- split --

CREATE TABLE IF NOT EXISTS `votes_log` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `UserID` bigint(20) NOT NULL,
  `SuggestionID` bigint(20) NOT NULL,
  `Votes` int(11) NOT NULL,
  `at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `Updated` datetime DEFAULT NULL,
  `IP` text NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

-- split --

CREATE TABLE IF NOT EXISTS `profiles_users` (
  `ID` bigint(20) NOT NULL AUTO_INCREMENT,
  `fb_user_id` bigint(20) DEFAULT NULL,
  `twitter_userid` bigint(20) DEFAULT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `name` text NOT NULL,
  `email` text NOT NULL,
  `last_login` datetime NOT NULL,
  `ip_address` text NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

-- split --

CREATE TABLE IF NOT EXISTS `categories` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `category_name` text COLLATE utf8_unicode_ci NOT NULL,
  `category_slug` text CHARACTER SET latin1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;