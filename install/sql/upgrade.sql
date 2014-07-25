
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

INSERT IGNORE INTO `settings` (`OptionName`, `OptionValue`, `Default`) VALUES
('FacebookLogin','{FB_SOCIAL_CONNECT}','');

-- split --

CREATE TABLE IF NOT EXISTS `categories` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `category_name` text COLLATE utf8_unicode_ci NOT NULL,
  `category_slug` text CHARACTER SET latin1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

-- split --

INSERT IGNORE INTO `settings` (`OptionName`, `OptionValue`, `Default`) VALUES
('AnonymousPosting','FALSE','FALSE');

-- split --

INSERT IGNORE INTO `emailtemplates` (`Template_Name`, `Subject`, `Content`, `Help`) VALUES
('New Suggestion', 'A New suggestion has been posted','Hello admin,\r\n\n {authorname} has posted a new suggestion {link}', ''),
('New Registration', 'New User Registration','Hello Admin\r\n\nNew user has been registered\r\n\nUsername: {username}\r\n\nemail: {email}','');
