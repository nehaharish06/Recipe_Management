--Step 1: Create database
CREATE DATABASE IF NOT EXISTS recipe_management;
USE recipe_management;

--Step 2: Create USER Table
CREATE TABLE USER (
    User_id VARCHAR(50) PRIMARY KEY,
    Fname VARCHAR(50),
    Minit VARCHAR(5),
    Lname VARCHAR(50),
    Password VARCHAR(255)
);

-- Step 3: Create RECIPE Table (without Main_ingredients column)
CREATE TABLE RECIPE (
    Recipe_id VARCHAR(50) PRIMARY KEY,
    Recipe_name VARCHAR(100),
    Recipe_type VARCHAR(50),
    Quantity INT,
    Description TEXT,
    User_id INT,
    FOREIGN KEY (User_id) REFERENCES USER(User_id)
);

-- Step 4: Create INGREDIENTS Table (without Recipe_name column)
CREATE TABLE INGREDIENTS (
    Ing_id VARCHAR(50) PRIMARY KEY,
    Ing_type VARCHAR(50),
    Ing_name VARCHAR(100),
    Ing_amt VARCHAR(50)
    recipe_name VARCHAR(50)
);

-- Step 5: Create RECIPE_INGREDIENT Table
CREATE TABLE RECIPE_INGREDIENT (
    Recipe_id VARCHAR(50),
    Ing_id VARCHAR(50),
    PRIMARY KEY (Recipe_id, Ing_id),
    FOREIGN KEY (Recipe_id) REFERENCES RECIPE(Recipe_id) ON DELETE CASCADE,
    FOREIGN KEY (Ing_id) REFERENCES INGREDIENTS(Ing_id) ON DELETE CASCADE
);

-- Step 6: Create CATEGORY Table
CREATE TABLE CATEGORY (
    Category_id VARCHAR(50) PRIMARY KEY,
    Type VARCHAR(50),
    Is_veg BOOLEAN,
    Regional VARCHAR(50)
);

-- Step 7: Create RATING Table
CREATE TABLE RATING (
    Rating_id INT AUTO_INCREMENT PRIMARY KEY,
    Rating_value INT CHECK (Rating_value BETWEEN 1 AND 5),
    Comment TEXT,
    User_id INT,
    Recipe_id INT,
    FOREIGN KEY (User_id) REFERENCES USER(User_id),
    FOREIGN KEY (Recipe_id) REFERENCES RECIPE(Recipe_id)
);



-- Step 9: Create HAS Table for Recipe Details
CREATE TABLE HAS (
    Nutri_info TEXT,
    Cook_time VARCHAR(50),
    User_id VARCHAR(50),
    Recipe_id VARCHAR(50),
    FOREIGN KEY (User_id) REFERENCES USER(User_id),
    FOREIGN KEY (Recipe_id) REFERENCES RECIPE(Recipe_id)
);