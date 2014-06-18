/**
 * Oxygenna.com
 *
 * $Template:: *(TEMPLATE_NAME)*
 * $Copyright:: *(COPYRIGHT)*
 * $Licence:: *(LICENCE)*
 */

(function( $ ){
    $(document).ready(function($){
        // get the select box we need to toggle options with
        var $select = $( '#' + theme + '_link_type' );
        var $selectContainer = $select.parents( '.rwmb-field' );
        var $toggleOptions = $selectContainer.siblings( '.rwmb-field' );
        console.log($toggleOptions);
        // hide all controls after the select
        //$selectContainer.nextAll().hide();

        $select.change(function(){
            // hide all controls after the select
            $toggleOptions.hide();
            // show selected options
            console.log($(this).val());
            switch( $(this).val() ) {
                case 'page':
                    $( $toggleOptions[0] ).show();
                break;
                case 'post':
                    $( $toggleOptions[1] ).show();
                break;
                case 'category':
                    $( $toggleOptions[2] ).show();
                break;
                case 'url':
                    $( $toggleOptions[3] ).show();
                break;
            }
        }).trigger('change');
    });
})( jQuery );