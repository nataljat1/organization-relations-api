-- Create the organizations table
CREATE TABLE organizations (
id INT AUTO_INCREMENT PRIMARY KEY,
name VARCHAR(255) UNIQUE NOT NULL
);

--Create the relations table
CREATE TABLE relations (
parent_id INT NOT NULL,
child_id INT NOT NULL,
FOREIGN KEY (parent_id) REFERENCES organizations(id),
FOREIGN KEY (child_id) REFERENCES organizations(id),
UNIQUE (parent_id, child_id)
);