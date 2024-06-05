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

        $this->initializeJavascriptModuleObject();
        $em_url = $this->escape($this->getUrl("test.php"));
        $this->tt_addToJavascriptModuleObject("greeting",$em_url);

        ?>
        <script>
            $(function(){
                var module = <?=$this->getJavascriptModuleObjectName()?>;
                console.log(module.tt('greeting'));
                fetch('http://localhost/redcap_v14.0.14/ExternalModules/?prefix=test-module&page=test&username=test1')
                    .then(response => response.text())
                    .then(result => console.log(result));
            })
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
