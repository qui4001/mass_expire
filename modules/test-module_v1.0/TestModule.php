<?php

// error handling try/catch during query execution
// error handling when printing out the resut of fetch
// add a clause when no username is passed during fetch/open
// remove deprecated when detecing dom chnage

// Set the namespace defined in your config file
namespace WCM\TestModule;

// Declare your module class, which must extend AbstractExternalModule 
class TestModule extends \ExternalModules\AbstractExternalModule
{
    function redcap_control_center()
    {

        $this->initializeJavascriptModuleObject();
        $em_url = $this->escape($this->getUrl("test.php"));
        $this->tt_addToJavascriptModuleObject("greeting",$em_url);

        ?>
        <script>
            var module = <?=$this->getJavascriptModuleObjectName()?>;
            var base_url = module.tt('greeting');
            base_url = new DOMParser().parseFromString(base_url, "text/html");
            base_url = base_url.documentElement.textContent;

            document.addEventListener('DOMNodeInserted', () => {
                if(document.getElementById('view_user_div') 
                        && document.getElementById('view_user_div').childElementCount > 1 
                        && !document.getElementById('mass_expire')){

                    me_button = document.createElement('button');
                    me_button.id = 'mass_expire';
                    me_button.textContent = 'Mass Expire';
                    document.getElementById('indv_user_info').rows[15].cells[1].append(me_button);

                    me_button.addEventListener('click', function(){
                        user_details = document.getElementById('indv_user_info');
                        username = user_details.rows[2].cells[1];
                        username = trim(username.innerHTML);
                        query_url = base_url + '&username=' + username;
                        
                        // open(query_url, target="_blank", "popup=yes,left=100,top=100,width=480,height=320");

                        fetch(query_url)
                            .then(response => response.text())
                            .then(result => { 
                                const data = JSON.parse(result); 
                                alert('Newly expired project ' + data.unexpired + ' and already expired ' + data.already);
                            });
                    });
                }
            });
        </script>
        <?php

    }
}
