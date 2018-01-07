create table lf_users (
  id int not null auto_increment,
  username varchar(30) not null,
  password varchar(150) not null,
  email varchar(250) not null,
  role varchar(15) not null,
  created timestamp not null default current_timestamp,
  updated timestamp not null default current_timestamp,
  primary key (id),
  constraint lf_users_uk unique (username)
);