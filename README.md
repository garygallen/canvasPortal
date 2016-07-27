# canvasPortal
Single Page Application Webportal consuming RESTful API Services
The webportal was a front tier application interfacing with a LMS.  The provided the capability for the user 
to create a course, edit, and delete.

The user would enter a course name, a short name and a department from a dropdown list.  The app would wrap the short course
name with standard fields separated with a period.  The fields school identifier code, the user email name, and at the
end appended the date the course was created.  This provided a standarization of short course name.  The short course name was 
used by other applications within the LMS.  This allowed application an efficent way to access course data from the DB.

The API services provided a middle tier between the web portal and the db.
