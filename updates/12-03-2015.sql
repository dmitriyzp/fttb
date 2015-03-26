
ALTER TABLE `users` 
  ADD `name` varchar(25) NOT NULL AFTER `fio`,
  ADD `otchestvo` varchar(25) NOT NULL AFTER `name`,
  ADD `passport` varchar(250) NOT NULL AFTER `otchestvo`,
  ADD `email` varchar(25) NOT NULL AFTER `passport`,
  ADD `datebirth` date NOT NULL AFTER `email`,
  ADD `phone` varchar(40) NOT NULL AFTER `datebirth`;

ALTER TABLE  `users` CHANGE  `fio`  `familiya` VARCHAR( 250 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT  '0';

