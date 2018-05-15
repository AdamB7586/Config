CREATE TABLE IF NOT EXISTS `config` (
  `setting` varchar(100) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`setting`),
  UNIQUE KEY `setting` (`setting`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `config` (`setting`, `value`) VALUES
('settings', 'Hello'),
('my_table', 'hello'),
('second_table', 'second');