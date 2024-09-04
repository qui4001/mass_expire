<?php

// EM framework version

// $module->query vs db_query
// https://github.com/vanderbilt-redcap/external-module-framework-docs/blob/main/methods/querying.md

// $module->getUser() returns empty - Why?
// could we use $module->isAuthenticated() instead?

namespace WeillCornellMedicine\MassExpire;

class MassExpire extends \ExternalModules\AbstractExternalModule
{
    function redcap_control_center()
    {
        $this->initializeJavascriptModuleObject();
        $em_url = $this->escape($this->getUrl("expire_user.php"));
        $this->tt_addToJavascriptModuleObject("fullurl",$em_url);

        ?>
        <script>
            let module = <?=$this->getJavascriptModuleObjectName()?>;
            let base_url = module.tt('fullurl');
            base_url = new DOMParser().parseFromString(base_url, "text/html");
            base_url = base_url.documentElement.textContent;

            let observer = new MutationObserver(callback);

            function callback (mutations) {
                for(const m of mutations){
                    if(m.type === 'childList' && 
                        document.getElementById('user-search') && document.getElementById('view_user_div').childElementCount > 1 && 
                        !document.getElementById('mass_expire'))
                    {
                        me_button = document.createElement('button');
                        me_button.id = 'mass_expire';
                        me_button.textContent = 'Mass Expire';

                        let user_details = document.getElementById('indv_user_info');
                        user_details.rows[16].cells[1].append(me_button);

                        me_button.addEventListener('click', function(){
                            username = user_details.rows[2].cells[1];
                            username = trim(username.innerHTML);
                            query_url = base_url + '&username=' + username;
                            
                            showProgress(true);
                            async function handleMassExpire(){ 
                                try{
                                    const mass_expire_http = await fetch(query_url);
                                    const mass_expire_json = await mass_expire_http.text();
                                    const data = JSON.parse(mass_expire_json);
                                    
                                    let dialog_message = '';
                                    if(data.status === 'failure')
                                        dialog_message = data.description;
                                    else
                                        dialog_message = 'Projects expired with yesterday\'s date: <b>' + data.unexpired + '</b><br> Projects already expired (no action taken): <b>' + data.already +'</b>';

                                    showProgress(false);
                                    simpleDialog(
                                        dialog_message,
                                        "Mass Expire", null, null, 
                                        null, "Close",
                                        null, null
                                    );
                                } catch(e){
                                    console.log(e);

                                    showProgress(false);
                                    simpleDialog(
                                        'Internal Error - <b> ' + e + '</b>', 
                                        "Mass Expire", null, null, 
                                        null, "Close",
                                        null, null
                                    );
                                }
                            }

                            handleMassExpire();
                            // fetch(query_url)
                            //     .then(response => response.text())
                            //     .then(result => {
                            //         showProgress(false);
                            //         const data = JSON.parse(result); 
                            //         simpleDialog(
                            //             'Projects expired with yesterday\'s date: <b>' + data.unexpired + '</b><br> Projects already expired (no action taken): <b>' + data.already +'</b>', 
                            //             "Mass Expire Result", null, null, 
                            //             null, "Close",
                            //             null, null
                            //         );
                            // });
                        });
                    }
                }
            }

            observerOptions = {
                childList: true,
                subtree: true
            }

            observer.observe(document.body, observerOptions);
        </script>
        <?php

    }
}
