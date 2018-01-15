create table lf_records (
  id int not null auto_increment,
  created timestamp not null default current_timestamp,
  updated timestamp not null default current_timestamp,
  date_event timestamp,
  stock_id int not null,
  user_id int not null,
  amount int not null,
  price decimal(12,4) not null,
  rec_type varchar(5) not null,
  primary key (id)
);