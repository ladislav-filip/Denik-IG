create table lf_stocks (
  id int not null auto_increment,
  code varchar(15) not null,
  name varchar(150) not null,
  price decimal(12,4) not null,
  refresh_type tinyint not null,
  description varchar(1000),
  created timestamp not null default current_timestamp,
  updated timestamp not null default current_timestamp,
  primary key (id),
  constraint lf_stocks_uk unique (code)
);