-- David Hanley
-- 200259844
-- CS 215 - Assignment 4
-- 2021-03-16

/*======================Table Creation======================= */

CREATE TABLE Users (
    user_id INT NOT NULL AUTO_INCREMENT,
    username VARCHAR(25),
    email VARCHAR(50),
    password VARCHAR(25),
    dob DATE,
    avatar_URL VARCHAR(255),   
    PRIMARY KEY (user_id) 
);

CREATE TABLE Posts (
    post_id INT NOT NULL AUTO_INCREMENT,
    user_id INT,
    repost_id INT,
    post TEXT,
    post_date DATETIME,    
    PRIMARY KEY (post_id),
    FOREIGN KEY (user_id) REFERENCES Users (user_id),
    FOREIGN KEY (repost_id) REFERENCES Posts (post_id)
);

CREATE TABLE Likes (
    like_id INT NOT NULL AUTO_INCREMENT,    
    post_id INT,    
    user_id INT,
    PRIMARY KEY (like_id),
    FOREIGN KEY (post_id) REFERENCES Posts (post_id),
    FOREIGN KEY (user_id) REFERENCES Users (user_id)
);

CREATE TABLE Dislikes (
    dislike_id INT NOT NULL AUTO_INCREMENT,    
    post_id INT,    
    user_id INT,
    PRIMARY KEY (dislike_id),
    FOREIGN KEY (post_id) REFERENCES Posts (post_id),  
    FOREIGN KEY (user_id) REFERENCES Users (user_id)  
);

/*======================DATA STORAGE QUERIES======================= */
/* From signup page. store into users table */
INSERT INTO Users (username, email, password, dob, avatar_URL)
    VALUES  ("Guy_Incognito", "asdf@asdf.ca", "asdf1234", '2000-12-12', "avatar.jpeg" );

INSERT INTO Users (username, email, password, dob, avatar_URL)
    VALUES  ("Person", "person@asdf.ca", "abc1234", '1983-10-08', "avatar2.jpeg" );


/* From post page. store into Posts table. */
INSERT INTO Posts (user_id, post, post_date)
    VALUES (1, "This is just a test.", '2021-03-14 15:36:32');

INSERT INTO Posts (user_id, post, post_date)
    VALUES (2, "Testing.", '2021-03-14 16:30:00');

INSERT INTO Posts (user_id, post, post_date)
    VALUES (1, "Lorem ipsum.", '2021-03-14 16:31:32');

INSERT INTO Posts (user_id, post, post_date, repost_id)
    VALUES (2, "Cool.", '2021-03-14 16:32:32', 1);


-- From main page and user detail page. post into Likes table. Bool values. 
INSERT INTO Likes (post_id, user_id)
VALUES      (2, 15);

INSERT INTO Likes (post_id)
VALUES      (1);

/* If the user has already liked, update to delete like data */
DELETE FROM Likes 
    WHERE like_id = 1;

/* From main page and user detail page. post into Dislikes table. Bool values. */
INSERT INTO Dislikes (post_id, user_id) VALUES (14, 21);

/* If the user has already liked, update to delete like data */
DELETE FROM Dislikes 
    WHERE dislike_id = 2;


-- ======================DATA RETRIEVAL QUERIES======================= 
--  1. Login Form 
--  If username and password are correct 
SELECT user_id, username, avatar_URL
    FROM Users
    WHERE email = "asdf@asdf.ca" AND password = "asdf1234"; --  If username and password pair do not match records, empty set is returned 

 
/*
2. Post list page 
Retrieve up to 20 posts, ordered and most recent first.
Includes content of post, date/time posted, username, avatar, number of likes/dislikes 
*/
SELECT Posts.post_id, Users.username, Users.avatar_URL, Posts.post, Posts.post_date, COUNT(Likes.post_id) as Likes, COUNT(Dislikes.post_id) as Dislikes
FROM Users       
LEFT JOIN Posts ON Posts.user_id = Users.user_id
LEFT JOIN Likes ON Posts.post_id = Likes.post_id
LEFT JOIN Dislikes ON Posts.post_id = Dislikes.post_id
GROUP BY Posts.post_id
ORDER BY post_date DESC
LIMIT 20;       

/* If repost, include content of original post, username of original author, date/time of original post */
-- I couldn't quite figure out how to get the repost part. 
-- My intent was to reference a repost to an original post (using self join) and get the necessary data from there but couldn't get it to work correctly.

SELECT Posts.post_id, Users.username, Users.avatar_URL, Posts.post, Posts.post_date, COUNT(Likes.post_id) as Likes, COUNT(Dislikes.post_id) as Dislikes,
Posts.repost_id, (SELECT re.post FROM Posts        
JOIN Posts re ON Posts.repost_id = re.post_id
GROUP BY Posts.repost_id) as OriginalPost
FROM Posts       
LEFT JOIN Users ON Posts.user_id = Users.user_id
LEFT JOIN Likes ON Posts.post_id = Likes.post_id
LEFT JOIN Dislikes ON Posts.post_id = Dislikes.post_id
GROUP BY Posts.post_id
ORDER BY post_date DESC
LIMIT 20; 


/* 3. User detail page */
/* given a specific user ID, retrieve all of the user’s posts following the 
data retrieval requirements as on the Post List Page query above */
SELECT Posts.post_id, Users.username, Users.avatar_URL, Posts.post, Posts.post_date, COUNT(Likes.post_id) as Likes, COUNT(Dislikes.post_id) as Dislikes
FROM Users       
LEFT JOIN Posts ON Posts.user_id = Users.user_id
LEFT JOIN Likes ON Posts.post_id = Likes.post_id
LEFT JOIN Dislikes ON Posts.post_id = Dislikes.post_id
WHERE Users.user_id = 23
GROUP BY Posts.post_id
ORDER BY post_date DESC
LIMIT 20; 



SELECT Posts.post_id, Users.username, Users.avatar_URL, Posts.post, Posts.post_date, COUNT(Likes.post_id) as Likes, COUNT(Dislikes.post_id) as Dislikes
        FROM Users       
        LEFT JOIN Posts ON Posts.user_id = Users.user_id
        LEFT JOIN Likes ON Posts.post_id = Likes.post_id
        LEFT JOIN Dislikes ON Posts.post_id = Dislikes.post_id
        WHERE Users.username = "$username"
        GROUP BY Posts.post_id
        ORDER BY post_date DESC
        LIMIT 20;




        SELECT Posts.post_id, Posts.repost_id, Users.user_id, Users.username, Users.avatar_URL, Posts.post, Posts.post_date, COUNT(Likes.post_id) as Likes, COUNT(Dislikes.post_id) as Dislikes
        FROM Users       
        LEFT JOIN Posts ON Posts.user_id = Users.user_id
        LEFT JOIN Likes ON Posts.post_id = Likes.post_id
        LEFT JOIN Dislikes ON Posts.post_id = Dislikes.post_id
        WHERE Posts.post_id = 15 AND 
        GROUP BY Posts.post_id
        ORDER BY Posts.post_date DESC
        LIMIT 20;


        SELECT COUNT(Likes.like_id) as Likes, COUNT(Dislikes.dislike_id) as Dislikes FROM Posts         
        JOIN Likes ON Posts.post_id = Likes.post_id
        JOIN Dislikes ON Posts.post_id = Dislikes.post_id        
        WHERE Posts.user_id = 23 AND Posts.post_id = 15
        ORDER BY Posts.post_id;


        SELECT COUNT(Dislikes.dislike_id) as Dislikes FROM Posts         
        JOIN Dislikes ON Posts.post_id = Dislikes.post_id        
        WHERE Posts.user_id = 23 AND Posts.post_id = 15;

        select count(Likes.like_id) as Likes FROM Likes
        WHERE user_id = 23 AND post_id = 15;

        select count(Dislikes.dislike_id) as Dislikes FROM Dislikes
        WHERE user_id = 23 AND post_id = 15;








SELECT Posts.post_id, Users.username, Users.avatar_URL, Posts.post, Posts.post_date, (SELECT COUNT(like_id) FROM Likes LEFT JOIN Posts ON Posts.post_id = Likes.post_id WHERE Likes.post_id = 15) as Likes, (SELECT COUNT(dislike_id) FROM Dislikes) as Dislikes,
Posts.repost_id, (SELECT Posts.post FROM Posts WHERE post_id = 9) as OriginalPost
FROM Posts       
LEFT JOIN Users ON Posts.user_id = Users.user_id
LEFT JOIN Likes ON Posts.post_id = Likes.post_id
LEFT JOIN Dislikes ON Posts.post_id = Dislikes.post_id
GROUP BY Posts.post_id
ORDER BY post_date DESC
LIMIT 20;