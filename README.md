Begin by unpacking the docker-compose.yml and Dockerfile.  Initially, disregard the PHP pages, after docker has done its magic, it will create an html/ directory. Unpack all the .php files into the html/ directory and everything should work. 

After everything is unpacked start the mysql console like docker exec -it work3-mysql-1 mysql -uroot -p

work3 is the directory you installed docker on, your directory might be different and go ahead and run this mysql command:
docker exec -it work3-mysql-1 mysql -uroot -p

the password is password by the way.

Run this command to get to the database we want to use:
use mydatabase;

Now if you find tables in the database, go ahead and drop them, after you have no tables; run these commands in your mysql console:
CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                first_name VARCHAR(255) NOT NULL,
                last_name VARCHAR(255) NOT NULL
            );

And run:

CREATE TABLE IF NOT EXISTS contacts (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT,
                email VARCHAR(255) DEFAULT NULL,
                address VARCHAR(255) DEFAULT NULL,
                phone VARCHAR(20) DEFAULT NULL,
                FOREIGN KEY (user_id) REFERENCES users(id)
            );

These two tables are needed to run the application, that's it, the app should now run

