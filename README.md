## Mass Expire

Mass Expire is a REDCap external module, that simplifies the process of expiring an user from multiple projects. Without this module one needs to go though each project manually. This process can be error prone if the user is associated with many projects.

### Usage
Once activated, this modules adds a new button labeled `Mass Expire` in the control panel.
To find this button, access the user details found at Control Panel > Browser Users > (Enter an user name, and click Search). On the user details table, under 'Statistics & Other Information' section, the Mass Expire button will show up next to 'Projects user can access' field. Click the button to expire the selected user from all associated projects. This module will set an expiration date set to yesterday.

During expiration, the module updates the databse, adds expiration action log to each project and also updates the external module log for each expiration per user. Once expired successfully, the module will report two numbers. The number of project it has expired the user from, and the number of projects the users was already expired in - the module ignore the second category of projects.

While expiring, the module will shows a progress spinner. Please be patient, if the user is associated with a large number of projcets. The module will report two above mentioned numbers, in a dialog box, only if it had successfully expire the user from **all** projects. If the modules fails to do so, there will be a dialog box with a brief explanation of the error.
