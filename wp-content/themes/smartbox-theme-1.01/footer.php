
            </div>
        </div>
        <footer id="footer" role="contentinfo">
            <div class="wrapper wrapper-transparent">
                <div class="container-fluid">
                    <div class="row-fluid">
                        <div class="span6 text-left">
                            <?php  if ( !function_exists('dynamic_sidebar') || dynamic_sidebar( 'footer-left' ) ): ?>
                            <?php  endif; ?>
                        </div>
                        <div class="span6 text-right">
                            <?php  if ( !function_exists('dynamic_sidebar') || dynamic_sidebar( 'footer-right' ) ): ?>
                            <?php  endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </footer>


        <script type="text/javascript">
            //<![CDATA[
            var _gaq = _gaq || [];
            _gaq.push(['_setAccount', '<?php echo oxy_get_option( 'google_anal' ) ?>']);
            _gaq.push(['_trackPageview']);

            (function() {
                var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
                ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
                var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
            })();
            //]]>
        </script>
        <?php wp_footer(); ?>
    </body>
</html>