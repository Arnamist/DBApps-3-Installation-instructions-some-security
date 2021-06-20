# DBApps-3-Installation-instructions-some-security
Assignment for Database Application, using mySQL server and PHP. 

About Application:
The function of the following php short code is to
access database, read values and search for a specific value from the database.
Trying to add value in database is incomplete due to syntax error (Commented code).
This application fulfils the purpose of understanding database management, creating sql queries, using https methods 
and overall provides the user with an basic example covering said topics.
[Work currently in progress].

Installation:
After extracting the code from the git directory, change the following information with your databases information.
$dbhost = "localhost";
$dbuser = "root";
$dbpass = "mysql";
$dbname = "foods"; 

Followed by changing the SQL statements found inside "" to suit the variable in your database. eg- $sql = "select * from employee where e_name=\"".$name."\"";
change the 'e_name' to the variable column in your database.
