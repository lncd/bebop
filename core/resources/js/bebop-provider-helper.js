$be = jQuery.noConflict();
$be(document).ready( function() {
	alert('hello!');
	$be(".bebop_provider_helper").on( 'click', function() {
		$be(this).stop().slideToggle( 500 );
	});
});