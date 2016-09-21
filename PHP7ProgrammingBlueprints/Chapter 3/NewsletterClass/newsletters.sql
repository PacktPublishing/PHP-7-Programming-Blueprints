CREATE TABLE newsletters (
  id INT(11) PRIMARY KEY AUTO_INCREMENT,
newsletter_name (TEXT),
newsletter_count INT(11) NOT NULL DEFAULT ‘0’,
is_active TINYINT(1),
Created_at DATETIME,
);

CREATE TABLE publications (
  newsleterId INT(11) PRIMARY KEY AUTO_INCREMENT,
  status VARCHAR(25),
  content TEXT,
  template TEXT,
  sent_at DATETIME,
  created_at DATETIME,
);
