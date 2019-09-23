-- 002: MySql - Having the follow table structures:

-- tbl_user: user_id, user_full_name 
-- tbl_role: role_id, role_name
-- tbl_user_role: user_id, role_id

-- 1: Create the mysql code to create these tables.
-- 2:  Write a query that will give you all users that belong to the role_name IT
-- 3: Write a query that will give you a list of users with no roles
-- 4: (Bonus) Write a query that will find the users with duplicate roles.


CREATE TABLE tbl_user(
	user_id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	user_full_name VARCHAR(255) NOT NULL
);

CREATE TABLE tbl_role(
	role_id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	role_name VARCHAR(100) NOT NULL
);

CREATE TABLE tbl_user_role(
	user_role_id INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	user_id INT(10) UNSIGNED NOT NULL,
	role_id INT(10) UNSIGNED NOT NULL,
	FOREIGN KEY (user_id) REFERENCES tbl_user(user_id),
	FOREIGN KEY (role_id) REFERENCES tbl_role(role_id)
);


-- ================== Queries ==================

-- 1: Write a query that will give you all users that belong to the role_name IT.

SELECT
	tur.user_role_id,
	tur.user_id,
	tur.role_id,
	tu.user_full_name,
	tr.role_name
FROM 
	tbl_user_role AS tur
JOIN 
	tbl_user AS tu ON tu.user_id = tur.user_id
JOIN 
	tbl_role AS tr ON tr.role_id = tur.role_id
WHERE
	tr.role_name = "IT"
ORDER BY
	tur.user_role_id

-- ======== OR ========

SELECT
	tur.user_role_id,
	tur.user_id,
	tur.role_id,
	tu.user_full_name,
	tr.role_name
FROM 
	tbl_user_role AS tur
JOIN 
	tbl_user AS tu ON tu.user_id = tur.user_id
JOIN 
	tbl_role AS tr ON tr.role_id = tur.role_id
WHERE
	tr.role_id = 3
ORDER BY
	tur.user_role_id

-- --------------------------------------------------------

-- 2: Write a query that will give you a list of users with no roles.

SELECT user_id, user_full_name FROM tbl_user WHERE user_id NOT IN 
(
    SELECT
        tur.user_id
    FROM 
        tbl_user_role AS tur
)

-- --------------------------------------------------------

-- 3: (Bonus) Write a query that will find the users with duplicate roles.

SELECT
	tu.user_full_name,
    COUNT(tu.user_full_name) AS User_Name_Count,
	tr.role_name, 
    COUNT(tr.role_name) AS User_Role_Count
FROM 
	tbl_user_role AS tur
JOIN 
	tbl_user AS tu ON tu.user_id = tur.user_id
JOIN 
	tbl_role AS tr ON tr.role_id = tur.role_id
GROUP BY
	tu.user_full_name,
	tr.role_name
HAVING
	COUNT(tu.user_full_name) > 1
    AND
    COUNT(tr.role_name) > 1

-- --------------------------------------------------------

-- tbl_user_role table All Recoeds.

SELECT
	tur.user_role_id,
	tur.user_id,
	tur.role_id,
	tu.user_full_name,
	tr.role_name
FROM 
	tbl_user_role AS tur
JOIN 
	tbl_user AS tu ON tu.user_id = tur.user_id
JOIN 
	tbl_role AS tr ON tr.role_id = tur.role_id
ORDER BY
	tur.user_role_id
