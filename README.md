# Enterprise-Software-Engineering
Developed mock enterprise with uploading and search functionality. The professor provided an API being able to create session, query files, request file, and close session. The files that are received from the API are in the format of:

    loanID-DocumentType-DateCreated_Hour_Minute_Second. 

Utilized AWS to host the website and PHPMyAdmin for the database.

A cronjob to query and request files onto the server ran every hour, having the API calls stored into the database (api_create_query_receive.php).
![image](https://user-images.githubusercontent.com/87762720/208568842-d38705ff-eaed-44d2-845a-127bb1726dd2.png)

Another cronjob runs after 30 minutes from the previous cron job to upload the files from the server to the database as a content blob (upload_receive_database.php).
![image](https://user-images.githubusercontent.com/87762720/208569091-f54f1bee-f648-4e9b-82e4-b616b0d0e01e.png)

