<?php
//Filters modify data and return the values to the original function.
class bebop_filters {
	//This grabs the day counter for that specific extension and increases it by one.	
	public function day_increase( $extension, $userId ) {
		$user_count = bebop_tables::get_user_meta_value( $userId, 'bebop_' . $extension . '_daycounter' );
		if( bebop_tables::get_option_value( 'bebop_' . $extension . '_maximport' ) > $user_count ) {
			$new_count = $user_count + 1;
			bebop_tables::update_user_meta( $userId, $extension, 'bebop_' . $extension . '_daycounter', $new_count );
			return true;
		}
		return false;
	}
	
	//This checks to see if the limit for the amount of imports has been maxed for the day.
	public function import_limit_reached( $extension, $userId ) {
		//different day ot no day set, set the day and the counter to 0;
		if ( bebop_tables::get_user_meta_value( $userId, 'bebop_' . $extension . '_counterdate' ) != date( 'dmy' ) ) {
			bebop_tables::update_user_meta( $userId, $extension, 'bebop_' . $extension . '_daycounter', '0' );
			bebop_tables::update_user_meta( $userId, $extension, 'bebop_' . $extension . '_counterdate', date( 'dmy' ) );
		}
		
		//max items per day * < should return false*
		if ( bebop_tables::get_option_value( 'bebop_' . $extension . '_maximport' ) ) {			
			if ( bebop_tables::get_user_meta_value( $userId, 'bebop_' . $extension . '_daycounter' ) < bebop_tables::get_option_value( 'bebop_' . $extension . '_maximport' ) ) {
				return false;
			}
		}
		else {			
			return false;
		}
		return true;
	}
	
	function search_filter( $content, $filters = null, $returnOnFirst = false, $findAll = false, $returnDefault = false ){
		if ( ! $filters ) {
			return $returnDefault;
		}
		$content = strip_tags( $content );

		foreach ( explode( ',', $filters ) as $filterValue ) {
			 if ( $filterValue ) {
			 	$filterValue = trim( $filterValue );
			 	$filterValue = str_replace( '/','',$filterValue );
			 	
			 	if ( preg_match( '/'.$filterValue.'/', $content ) > 0 ) {
			 		if ( $returnOnFirst ) {
			 			return true;
					}
					
					if ( $findAll ) {
						$returnValue = true;
					}
				}
				else {
					if ( $findAll ) {
						$returnValue = false;
					}
				}
			 }
		}
		return $returnValue;
	}
}