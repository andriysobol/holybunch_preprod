<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
function oxy_create_logo_custom() {
    if( function_exists( 'icl_get_home_url' ) ) {
        $home_link = icl_get_home_url();
    }else {
        $home_link = site_url();
    }?>
    <!-- added class brand to float it left and add some left margins -->
    <a class="brand" href="<?php echo $home_link; ?>"> <img src="" class="logoButtonLink"></a>
<?php
}


