<?php

// popup
// show un-expired user count
// alternate to force refresh
// url argument needs to be dynamic
// error handling try/catch during query execution

// Set the namespace defined in your config file
namespace WCM\TestModule;

// Declare your module class, which must extend AbstractExternalModule 
class TestModule extends \ExternalModules\AbstractExternalModule
{
    function redcap_control_center()
    {
        ?>

            <script type='text/javascript'>
                document.addEventListener('DOMNodeInserted', () => {
                    if(document.getElementById('view_user_div') && document.getElementById('view_user_div').childElementCount > 1 && !document.getElementById('mass_expire')){
                        me_button = document.createElement('button');
                        me_button.id = 'mass_expire';
                        me_button.textContent = 'Mass Expire';
                        document.getElementById('indv_user_info').rows[15].cells[1].append(me_button);

                        me_button.addEventListener('click', function(){
                            user_details = document.getElementById('indv_user_info');
                            username = user_details.rows[2].cells[1];
                            username = trim(username.innerHTML);
                            open('../../plugins/mass_expire.php?username='+username, target="_blank", "popup=yes,left=100,top=100,width=480,height=320");
                            // fetch('../../plugins/mass_expire.php?username='+username) .then(response => response.text()) .then(result => alert('Expired user from all projects.'));
                        });
                    }
                });
            </script>

        <?php

    }
}
