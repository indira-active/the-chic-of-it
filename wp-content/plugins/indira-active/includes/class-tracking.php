<?php

// exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Tracking scripts
 *
 * @since 1.0.0
 * @package indira
 */
class IA_Tracking {

	/**
	 * Get things going
	 *
	 * @since 1.0.0
	 */
	function __construct() {
        add_action( 'wp_head'        , array( __CLASS__ , 'add_head' ) );
        add_action( 'genesis_before' , array( __CLASS__ , 'add_body' ) );
        add_action( 'wp_footer'      , array( __CLASS__ , 'add_foot' ) );
    }


	/**
	 * Add <head> scripts
	 *
	 * @since 1.0.0
	 */
	public static function add_head() { ?>

        <!-- Global site tag (gtag.js) - Google Analytics -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=UA-84828000-1"></script>
		<script>
		  window.dataLayer = window.dataLayer || [];
		  function gtag(){dataLayer.push(arguments);}
		  gtag('js', new Date());
		  gtag('config', 'UA-84828000-1', {
		  'linker': {
		    'domains': ['indiraactive.com', 'www.indiraactive.com', 'blog.indiraactive.com', 'returns.indiraactive.com', 'track.indiraactive.com']
		  },
		  'link_attribution': true,
		  'allow_display_features': true,
		  'send_page_view': true
		});
		</script>

		<!-- Bing Ads Universal tag -->
		<script>(function(w,d,t,r,u){var f,n,i;w[u]=w[u]||[],f=function(){var o={ti:"5708096"};o.q=w[u],w[u]=new UET(o),w[u].push("pageLoad")},n=d.createElement(t),n.src=r,n.async=1,n.onload=n.onreadystatechange=function(){var s=this.readyState;s&&s!=="loaded"&&s!=="complete"||(f(),n.onload=n.onreadystatechange=null)},i=d.getElementsByTagName(t)[0],i.parentNode.insertBefore(n,i)})(window,document,"script","//bat.bing.com/bat.js","uetq");</script><noscript><img src="//bat.bing.com/action/0?ti=5708096&Ver=2" height="0" width="0" style="display:none; visibility: hidden;" /></noscript>
		<!-- End Bing Ads Universal tag -->

		<!-- Twitter universal website tag code -->
		<script>
		!function(e,t,n,s,u,a){e.twq||(s=e.twq=function(){s.exe?s.exe.apply(s,arguments):s.queue.push(arguments);
		},s.version='1.1',s.queue=[],u=t.createElement(n),u.async=!0,u.src='//static.ads-twitter.com/uwt.js',
		a=t.getElementsByTagName(n)[0],a.parentNode.insertBefore(u,a))}(window,document,'script');
		// Insert Twitter Pixel ID and Standard Event data below
		twq('init','nxxxp');
		twq('track','PageView');
		</script>
		<!-- End Twitter universal website tag code -->

		<!-- Smooch.io Chat head -->
		<script>
		    !function(e,n,t,r){
		        function o(){try{var e;if((e="string"==typeof this.response?JSON.parse(this.response):this.response).url){var t=n.getElementsByTagName("script")[0],r=n.createElement("script");r.async=!0,r.src=e.url,t.parentNode.insertBefore(r,t)}}catch(e){}}var s,p,a,i=[],c=[];e[t]={init:function(){s=arguments;var e={then:function(n){return c.push({type:"t",next:n}),e},catch:function(n){return c.push({type:"c",next:n}),e}};return e},on:function(){i.push(arguments)},render:function(){p=arguments},destroy:function(){a=arguments}},e.__onWebMessengerHostReady__=function(n){if(delete e.__onWebMessengerHostReady__,e[t]=n,s)for(var r=n.init.apply(n,s),o=0;o<c.length;o++){var u=c[o];r="t"===u.type?r.then(u.next):r.catch(u.next)}p&&n.render.apply(n,p),a&&n.destroy.apply(n,a);for(o=0;o<i.length;o++)n.on.apply(n,i[o])};var u=new XMLHttpRequest;u.addEventListener("load",o),u.open("GET","https://"+r+".webloader.smooch.io/",!0),u.responseType="json",u.send()
		    }(window,document,"Smooch","5892a9a16c77636e014b54f3");
		</script>
		<!-- END Smooch.io Chat head -->

    <?php }


	/**
	 * Add just-after <body> scripts
	 *
	 * @since 1.0.0
	 */
	public static function add_body() { ?>

		<!-- Hi -->

    <?php }


	/**
	 * Add pre-</body> scripts
	 *
	 * @since 1.0.0
	 */
	public static function add_foot() { ?>
		<!-- Smooch.io Chat body -->
		<script>
		  var skPromise = Smooch.init({
		      appId: "5892a9a16c77636e014b54f3",
		      customText: {
		        introductionText: 'We try to be quick, so ask us anything!',
		        introAppText: 'Chat with us below or from your favorite app, email, or SMS.',
		        inputPlaceholder: 'Type a message....'                       
		      }
		  });
		</script>
		<!-- END Smooch.io Chat body -->

        <!-- Klaviyo Tracking code for email markerting -->
		<script type="text/javascript">
			var _learnq = _learnq || [];
			_learnq.push(['account', 'Fr2zbh']);
			(function () {
			var b = document.createElement('script'); b.type = 'text/javascript'; b.async = true;
			b.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'a.klaviyo.com/media/js/analytics/analytics.js';
			var a = document.getElementsByTagName('script')[0]; a.parentNode.insertBefore(b, a);
			})();
		</script>
		<!-- End Klaviyo Tracking code for email markerting -->

		<!-- Sniply Tracking -->
		<img src="https://snip.ly/services/pixel/5a9f6b4e76ae563b192d3e9b/" height=1 width=1 border=0 />
		<!-- End Sniply tracking -->

	<?php }
}

new IA_Tracking;
