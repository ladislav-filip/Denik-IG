create table lf_settings (
  id int not null auto_increment,
  name varchar(150) not null,
  value text not null,
  created timestamp not null default current_timestamp,
  updated timestamp not null default current_timestamp,
  primary key (id),
  constraint lf_settings_uk unique (name)
);