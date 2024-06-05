<?php

// error handling try/catch during query execution
// error handling when printing out the resut of fetch

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
                            url = 'http://localhost/redcap_v14.0.14/ExternalModules/?prefix=test-module&page=test&username='+username;
                            
                            // open(url, target="_blank", "popup=yes,left=100,top=100,width=480,height=320");
                            fetch(url) .then(response => response.text()) .then(result => { const data = JSON.parse(result); alert('Newly expired project ' + data.unexpired + ' and already expired ' + data.already);});
                        });
                    }
                });
        </script>
        <?php

        /*
        $em_url = $em_url . "&username=test1";
        echo $em_url;

        $ch = curl_init();
        // curl_setopt($ch, CURLOPT_URL, $em_url);
        curl_setopt($ch, CURLOPT_URL, 'http://localhost/redcap_v14.0.14/ExternalModules/?prefix=test-module&page=test&username=test1');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

        $response = curl_exec($ch);
        echo $response;

        curl_close($ch);

        redirectAfterHook($em_url, true);
        */

    }
}
