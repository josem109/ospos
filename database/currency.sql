CREATE TABLE ospos_currencytable (
    id INT AUTO_INCREMENT,
    currency_rate VARCHAR(255),
    currency_symbol VARCHAR(255),
    currency_date DATE,
    PRIMARY KEY (id, currency_symbol, currency_date)
);
