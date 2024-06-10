<?php

// Next step: show WCM BA for modifications; QA, Code review; OPT Release REDCap EM as v.1 and continue working on v.2
// need to check if it is conflicting with native functions(check if suspension, deletion, reaction of user work when EM is enabled)
// error handling try/catch during query execution
// error handling when printing out the resut of fetch
// add a clause when no username is passed during fetch/open
// remove deprecated when detecing dom chnage
// Build EM's internal log for troubleshooting
// username wasn't declared as var/let when assigning variable the first time -- investigate
// run built in script checker
// REDCap dialog box instead of HTML dialog
// Present it to EM Meeting
// Would it be an issue that I am working locally?
// find how we deploy, folder name represents version number
// investigate ORM https://stackoverflow.com/questions/129677/how-can-i-sanitize-user-input-with-php

// Set the namespace defined in your config file
namespace WeillCornellMedicine\MassExpire;

// Declare your module class, which must extend AbstractExternalModule 
class MassExpire extends \ExternalModules\AbstractExternalModule
{
    function redcap_control_center()
    {

        $this->initializeJavascriptModuleObject();
        $em_url = $this->escape($this->getUrl("expire_user.php"));
        $this->tt_addToJavascriptModuleObject("fullurl",$em_url);

        ?>
        <script>
            var module = <?=$this->getJavascriptModuleObjectName()?>;
            var base_url = module.tt('fullurl');
            base_url = new DOMParser().parseFromString(base_url, "text/html");
            base_url = base_url.documentElement.textContent;

            document.addEventListener('DOMNodeInserted', () => {
                if(document.getElementById('user-search') 
                        && document.getElementById('view_user_div').childElementCount > 1 
                        && !document.getElementById('mass_expire')){

                    me_button = document.createElement('button');
                    me_button.id = 'mass_expire';
                    me_button.textContent = 'Mass Expire';

                    var user_details = document.getElementById('indv_user_info');
                    user_details.rows[15].cells[1].append(me_button);

                    me_button.addEventListener('click', function(){
                        username = user_details.rows[2].cells[1];
                        username = trim(username.innerHTML);
                        query_url = base_url + '&username=' + username;
                        
                        // Will be used in phase 2
                        // open(query_url, target="_blank", "popup=yes,left=100,top=100,width=480,height=320");

                        fetch(query_url)
                            .then(response => response.text())
                            .then(result => { 
                                // console.log(result);
                                const data = JSON.parse(result); 
                                // alert('Newly expired project ' + data.unexpired + ' and already expired ' + data.already);
                                alert('Projects expired with today\'s date: ' + data.unexpired + 
                                        '\nProjects already expired (no action taken): ' + data.already );
                            });
                    });
                }
            });
        </script>
        <?php

    }
}
