-- Create Products Table
CREATE TABLE Products (
    product_id INT AUTO_INCREMENT PRIMARY KEY,
    product_name VARCHAR(255) NOT NULL,
    price DECIMAL(10, 2) NOT NULL
);

-- Create Customers Table
CREATE TABLE Customers (
    customer_id INT AUTO_INCREMENT PRIMARY KEY,
    customer_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    city VARCHAR(100) NOT NULL
);

-- Create Orders Table
CREATE TABLE Orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    product_id INT NOT NULL,
    order_date DATE,
    quantity DECIMAL(10) NOT NULL,
    total_price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (customer_id) REFERENCES Customers(customer_id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES Products(product_id) ON DELETE CASCADE
);

-- Insert Products
INSERT INTO Products VALUES(101, 'Laptop', 25);
INSERT INTO Products VALUES(102, 'Phone', 25);

-- Insert Customers
INSERT INTO Customers VALUES(NULL, 'Alice', 'alice@gmail.com', 'New York');
INSERT INTO Customers VALUES(NULL, 'Bob', 'bob@gmail.com', 'Chicago');

-- Insert Orders
INSERT INTO Orders VALUES(NULL, 1, 101, '2023-01-10', 2, 50);
INSERT INTO Orders VALUES(NULL, 2, 102, '2023-02-15', 1, 25);
INSERT INTO Orders VALUES(NULL, 1, 101, '2023-03-22', 5, 125);

-- Identifying Potential Performance Bottlenecks
1. Joins: The QUERY involves joining three TABLES, which can be costly IN terms OF performance, especially IF the TABLES are large AND NOT indexed properly.
2. Filtering: The WHERE clause filters customers based ON the city. IF the city COLUMN IS NOT indexed, this can result IN a FULL TABLE scan.
3. GROUPING: The GROUP BY clause can also be a performance bottleneck IF the result SET IS large because it requires sorting the DATA BEFORE aggregating.

-- Proposed Indexing Strategies
1. INDEX ON Customers.city: CREATE an INDEX ON the city COLUMN OF the Customers TABLE TO speed up the filtering.
run query = CREATE INDEX idx_city ON Customers(city);
2. INDEX ON Orders.customer_id AND Orders.product_id: Ensure that there are INDEXES ON customer_id AND product_id IN the Orders TABLE TO improve the JOIN performance.
run query = CREATE INDEX idx_orders_customer ON Orders(customer_id);
run query = CREATE INDEX idx_orders_product ON Orders(product_id);
3. INDEX ON Products.product_id: Ensure there IS an INDEX ON product_id IN the Products TABLE FOR FAST lookups.
run query = CREATE INDEX idx_products_product_id ON Products(product_id);

-- Rewriting the Query with Optimizations
SELECT c.customer_name, p.product_name, o.total AS total_spent
FROM Customers c
JOIN (SELECT customer_id, product_id, SUM(total_price) AS total FROM Orders GROUP BY customer_id, product_id) o ON c.customer_id = o.customer_id
JOIN Products p ON o.product_id = p.product_id
WHERE c.city = 'New York'
GROUP BY c.customer_name, p.product_name;

-- Changes AND Explanation
INDEXES: The addition OF INDEXES ON the city, customer_id, AND product_id COLUMNS will significantly reduce the TIME spent ON lookups AND filtering. INDEXES HELP the DATABASE ENGINE quickly LOCATE the ROWS that MATCH the criteria rather THAN scanning the entire table.

-- Using EXPLAIN Plan to Analyze Query Performance
EXPLAIN SELECT c.customer_name, p.product_name, o.total AS total_spent
FROM Customers c
JOIN (SELECT customer_id, product_id, SUM(total_price) AS total FROM Orders GROUP BY customer_id, product_id) o ON c.customer_id = o.customer_id
JOIN Products p ON o.product_id = p.product_id
WHERE c.city = 'New York'
GROUP BY c.customer_name, p.product_name;

-- What TO Look FOR IN the EXPLAIN Output:
1. TYPE: CHECK IF the TYPE IS ALL (FULL TABLE scan) OR INDEX (USING an INDEX). You want TO see ref OR eq_ref, which indicates that INDEXES are being used efficiently.
2. ROWS: Look AT the estimated NUMBER OF ROWS that need TO be examined. A high NUMBER indicates potential performance issues.
3. KEY: CHECK which INDEXES are being used. Ideally, the output should SHOW the relevant INDEXES you created.
