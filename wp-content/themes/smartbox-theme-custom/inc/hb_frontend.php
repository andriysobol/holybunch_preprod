<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
add_filter('get_avatar','change_avatar_css');
//this filter replace default avatar class by template class in order to show avatar as circle
function change_avatar_css($class) {
    $class = str_replace('avatar avatar-300 wp-user-avatar wp-user-avatar-300 alignnone photo', 'img-circle  avatar-300 photo', $class) ;
    return $class;
}
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


