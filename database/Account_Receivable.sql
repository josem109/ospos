CREATE TABLE ospos_debts (
    debt_id INT AUTO_INCREMENT,
    customer_id INT,
    sale_id INT,
    invoice_amount DECIMAL(10, 2),
    amount_due DECIMAL(10, 2),
    due_date DATE,
    PRIMARY KEY (debt_id)
);

CREATE TABLE ospos_payments (
    payment_id INT AUTO_INCREMENT,
    customer_id INT,
    sale_id INT,
    payment_date DATE,
    payment_amount DECIMAL(10, 2),
    payment_type VARCHAR(50),
    PRIMARY KEY (payment_id)
);


-- Populate Table ospos_debts
INSERT INTO ospos_debts (customer_id, sale_id, invoice_amount, amount_due, due_date)
SELECT 
    os.customer_id, 
    os.sale_id, 
    SUM(osi.quantity_purchased * osi.item_unit_price) AS invoice_amount,
    SUM(osi.quantity_purchased * osi.item_unit_price) AS amount_due,
    DATE_ADD(os.sale_time, INTERVAL 15 DAY) AS due_date
FROM 
    ospos_sales AS os
LEFT JOIN 
    ospos_sales_items AS osi ON os.sale_id = osi.sale_id
WHERE 
    os.customer_id IS NOT NULL
GROUP BY 
    os.sale_id, os.customer_id, os.sale_time

--Update ospos_debts amount_due

UPDATE ospos_debts od
INNER JOIN 
(
    SELECT 
        sale_id,
        SUM(payment_amount) AS total_payment_amount
    FROM 
        (SELECT * FROM ospos_sales_payments WHERE payment_type != 'Adeudado') osp
    GROUP BY 
        sale_id
    HAVING 
        total_payment_amount IS NOT NULL
) subquery ON od.sale_id = subquery.sale_id
SET od.amount_due = od.amount_due - subquery.total_payment_amount

--Insert Payments Created

INSERT INTO ospos_payments (customer_id, sale_id, payment_date, payment_amount, payment_type)
SELECT 
    os.customer_id,
    osp.sale_id,
    osp.payment_time,
    osp.payment_amount,
    osp.payment_type
FROM 
    ospos_sales_payments osp
LEFT JOIN 
    ospos_sales os ON osp.sale_id = os.sale_id
WHERE 
    osp.payment_type <> 'Adeudado'
    AND os.customer_id IS NOT NULL