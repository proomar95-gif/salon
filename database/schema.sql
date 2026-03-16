CREATE TABLE customers (
  id INT AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(120) NOT NULL,
  phone VARCHAR(20) NOT NULL,
  birth_date DATE NULL,
  notes TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE employees (
  id INT AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(120) NOT NULL,
  role VARCHAR(60) NOT NULL,
  commission_percent DECIMAL(5,2) DEFAULT 0
);

CREATE TABLE services (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  duration_minutes INT NOT NULL,
  price DECIMAL(10,2) NOT NULL
);

CREATE TABLE employee_services (
  employee_id INT NOT NULL,
  service_id INT NOT NULL,
  PRIMARY KEY (employee_id, service_id),
  FOREIGN KEY (employee_id) REFERENCES employees(id),
  FOREIGN KEY (service_id) REFERENCES services(id)
);

CREATE TABLE system_users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(80) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('admin','reception','booking') NOT NULL
);

CREATE TABLE appointments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  customer_id INT NOT NULL,
  created_by_user_id INT NOT NULL,
  service_id INT NOT NULL,
  employee_id INT NOT NULL,
  appointment_at DATETIME NOT NULL,
  status ENUM('booked','visited','cancelled') DEFAULT 'booked',
  FOREIGN KEY (customer_id) REFERENCES customers(id),
  FOREIGN KEY (created_by_user_id) REFERENCES system_users(id),
  FOREIGN KEY (service_id) REFERENCES services(id),
  FOREIGN KEY (employee_id) REFERENCES employees(id)
);

CREATE TABLE visits (
  id INT AUTO_INCREMENT PRIMARY KEY,
  appointment_id INT NOT NULL,
  total_amount DECIMAL(10,2) NOT NULL,
  payment_status ENUM('paid','partial','pending') DEFAULT 'paid',
  visited_at DATETIME NOT NULL,
  FOREIGN KEY (appointment_id) REFERENCES appointments(id)
);

CREATE TABLE inventory_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  item_name VARCHAR(120) NOT NULL,
  qty INT NOT NULL,
  min_qty INT NOT NULL,
  unit VARCHAR(20) NOT NULL
);

CREATE TABLE customer_events (
  id INT AUTO_INCREMENT PRIMARY KEY,
  customer_id INT NOT NULL,
  event_name VARCHAR(120) NOT NULL,
  event_date DATE NOT NULL,
  reminder_days_before INT DEFAULT 3,
  FOREIGN KEY (customer_id) REFERENCES customers(id)
);

-- تقارير أداء الموظفات
CREATE VIEW employee_revenue_report AS
SELECT
  e.id,
  e.full_name,
  COUNT(v.id) AS visits_count,
  IFNULL(SUM(v.total_amount), 0) AS total_revenue
FROM employees e
LEFT JOIN appointments a ON a.employee_id = e.id AND a.status = 'visited'
LEFT JOIN visits v ON v.appointment_id = a.id
GROUP BY e.id, e.full_name;

-- تقارير أداء مستخدمي النظام
CREATE VIEW user_booking_conversion_report AS
SELECT
  u.id,
  u.username,
  COUNT(a.id) AS total_bookings,
  SUM(CASE WHEN a.status = 'visited' THEN 1 ELSE 0 END) AS converted_to_visits
FROM system_users u
LEFT JOIN appointments a ON a.created_by_user_id = u.id
GROUP BY u.id, u.username;
