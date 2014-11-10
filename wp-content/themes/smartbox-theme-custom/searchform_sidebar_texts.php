<form role="search" method="get" id="searchform" action="<?php echo home_url('/'); ?>">
                    <input type="text" name="s" id="s" <?php if (is_search()) { ?>value="<?php the_search_query(); ?>" <?php } else { ?>value="<?php echo __('Search', THEME_FRONT_TD); ?> &hellip;" onfocus="if (this.value == this.defaultValue)
                this.value = '';" onblur="if(this.value == '')this.value = this.defaultValue;"<?php } ?> />
                    <input type="hidden" name="post_type" value="oxy_content" checked="checked"/>                       
</form>