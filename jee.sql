
 CREATE TABLE jee (
id INT AUTO_INCREMENT PRIMARY KEY,

reference_no VARCHAR(30) UNIQUE,

-- PERSONAL DETAILS
name VARCHAR(100),
gender ENUM('F','M','O'),
dob DATE,
father VARCHAR(100),
mother VARCHAR(100),
community ENUM('SC','ST','OC','OBC','BC-A','BC-B','BC-C','BC-D','BC-E','EWS','GENERAL'),

-- ADDRESS
address1 TEXT,
address2 TEXT,
city VARCHAR(50),
state VARCHAR(50),
zip VARCHAR(10),
country VARCHAR(50),

-- CONTACT
email VARCHAR(100),
confirm_email TINYINT(1) DEFAULT 0,
aadhar VARCHAR(20),
mobile VARCHAR(15),

-- ACADEMIC
board VARCHAR(50),
passing_year VARCHAR(20),
tenth VARCHAR(255),
intermediate_hall_ticket VARCHAR(30),
inter_percentage DECIMAL(5,2),
inter_group_percentage DECIMAL(5,2),
total_marks INT,
group_marks INT,

-- ENTRANCE
jee_hall_ticket VARCHAR(30),
jee_rank INT,
jee_percentile DECIMAL(5,2),
eamcet_hall_ticket VARCHAR(30),
eamcet_rank INT,

-- PREFERENCES
pref1 VARCHAR(20),
pref2 VARCHAR(20),
pref3 VARCHAR(20),
pref4 VARCHAR(20),
pref5 VARCHAR(20),

-- FILES
photo VARCHAR(255),
inter VARCHAR(255),
jee_card VARCHAR(255),
eamcet_card VARCHAR(255),
stud_sign VARCHAR(255),
parent_sign VARCHAR(255),
-- PAYU PAYMENT DETAILS
txnid VARCHAR(100),        -- Transaction ID
payu_status VARCHAR(20),         -- success / failure
payment_status VARCHAR(20) DEFAULT 'Pending',
payment_amount DECIMAL(10,2),
payment_date DATETIME,
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
status ENUM('Pending','Approved','Rejected') DEFAULT 'Pending'
);