# canvasPortal
A Single Page Application Webportal consuming RESTful API Services.

The webportal is a front tier application interfacing with the Canvas LMS.  The app provided the capability for the user 
to create a course, edit, and delete.

The user would enter a course name, a short course name and a department from a dropdown list.  The app would wrap the short course name with standard fields separated with a period.  The fields provided were the school identifier code, the user email name, and at the end appended the date the course was created.  This mechanism allow for standarization for the short course name. The short course name was used by other applications within the LMS providing them an efficent way to access course data from the DB.

The API services provided a middle tier between the web portal, the LMS, and DB.

Technology Stack: PHP, Javascript, jQuery, RESTful Svcs, Single Sign On
