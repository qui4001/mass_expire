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
        // make a JS Get call to the php file so we don't open a new browser tab
        // const xhr = new XMLHttpRequest()
        // xhr.open("GET", "http://localhost/plugins/mass_expire.php?username=test1", true)
        // xhf.send()
        print  "<div id='MassExpire'>
                    <a href='../../plugins/mass_expire.php?username=test1' target='_blank'>Mass Expire this user.</a>
                </div>";

        // Use JavaScript/jQuery to append our link to the bottom of the left-hand menu
        print  "<script type='text/javascript'>
                document.addEventListener('DOMNodeInserted', () => {
                    if(document.getElementById('view_user_div').childElementCount > 1)
                        $( 'div#MassExpire' ).appendTo( 'div#view_user_div' );
                    });
                </script>";
    }
}
