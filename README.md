# Welcome to FitShare - The Sports-Oriented Social Network

FitShare is a social networking platform designed for sports enthusiasts to connect, share their achievements, provide advice, and engage in discussions related to sports. This repository contains the source code and files necessary to set up and run the FitShare platform.

## Features

- User registration and authentication system.
- News feed displaying recent posts from followed users.
- User profiles with the ability to send messages and follow/unfollow users.
- Search functionality to find users and posts.
- User-specific posts and the ability to report inappropriate content.
- Private messaging system.
- Admin functionality for managing reported posts.
- Create both classic and ephemeral (story) posts.

## Set Up Your Database

1. **Import Database Schema:** Use the provided `sql.sql` file to import the database schema.

2. **Edit `db.php` File:** Open the `db.php` file and input your database connection details:

   ```php
   <?php
   // db.php
   
   $db_host = "your_database_host";
   $db_user = "your_database_username";
   $db_pass = "your_database_password";
   $db_name = "your_database_name";
   
   ?>

3. You can see here the database organization :

![SHEMA DATABASE](https://github.com/omvr-yr/FITSHARE_social_network/assets/109440038/7592b495-528f-4cdb-85bd-c6da12cb525a)


## Configure Cron Job for Ephemeral Posts

To automatically delete ephemeral posts, set up a cron job:

1. **Open Terminal:** Launch your terminal or command prompt.

2. **Edit Cron Jobs:** Edit your user's cron jobs by typing:

   ```bash
   crontab -e
   ```

3. Add Cron Job Line: Add the following line to the cron file to run the script every minute:

   ```bash
   * * * * * /usr/bin/php /path/to/fitshare/script.php

Replace /path/to/fitshare with the actual path to your script.php file.
Note: Ensure that the path to the PHP binary (/usr/bin/php) is accurate on your system.


## Usage
  
  Follow these steps to get started with FitShare:
  
  1. **Register and Log In:** Start by registering a new user through the registration page. Once registered, log in to your account.
  
  2. **Home Page:** After logging in, you'll be directed to the home page. Here, you'll find a feed displaying the 20 most recent posts from users you follow.
  
  3. **Exploring Profiles:** Explore user profiles by clicking on usernames. You can view their posts, send messages, and follow/unfollow them.
  
  4. **Interacting with Posts:** On the home page, you can like, unlike, and report posts. Engage with posts from fellow sports enthusiasts.
  
  5. **Private Messaging:** Utilize the private messaging system by navigating to the "Message" page. View conversations with other users and send messages privately.
  
  6. **Admin Functions:** If you have admin privileges, access the reporting system to manage posts that have been reported by other users.


## Visual Walkthrough

<img width="1624" alt="inscr" src="https://github.com/omvr-yr/FITSHARE_social_network/assets/109440038/4da1ca0a-7720-4560-b0f2-d6408e7c93b7">
<img width="1624" alt="profil" src="https://github.com/omvr-yr/FITSHARE_social_network/assets/109440038/cc8f4c3d-74fd-47c8-bfbe-591145a767b5">
<img width="1624" alt="msg" src="https://github.com/omvr-yr/FITSHARE_social_network/assets/109440038/d84341e5-8057-4be0-9463-17100a5b9e85">
<img width="1624" alt="search" src="https://github.com/omvr-yr/FITSHARE_social_network/assets/109440038/3b56ad90-63d8-4ced-9ff8-ebccd21df6ed">


