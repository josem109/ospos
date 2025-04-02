ALTER TABLE ospos_payments 
ADD COLUMN employee_id INT(10) NULL,
ADD CONSTRAINT fk_payments_employees 
FOREIGN KEY (employee_id) 
REFERENCES ospos_employees(person_id) 
ON DELETE SET NULL 
ON UPDATE CASCADE;