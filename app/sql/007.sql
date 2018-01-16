create view lf_records_view as
  SELECT a.*, a.amount * a.price as price_all, a.amount * b.price as price_all_now, b.price as price_now, b.code, b.name,
              (a.amount * a.price - a.amount * b.price) as profit,
              (b.price - a.price) / b.price * 100 as profit_proc
  FROM lf_records a
    INNER JOIN lf_stocks b ON a.stock_id = b.id
  ;