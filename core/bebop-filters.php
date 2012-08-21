<?php
class bebop_filters {
	//Increment the day counter
	public function day_increase( $extension, $user_id, $username ) {
		$user_count = bebop_tables::get_user_meta_value( $user_id, 'bebop_' . $extension . '_' . $username . '_daycounter' );
		if ( bebop_tables::get_option_value( 'bebop_' . $extension .'_maximport' ) > $user_count ) {
			bebop_tables::log_error( 'bebop_' . $extension  . '_' . $username . '_daycounter', $user_count );
			$new_count = $user_count + 1;
			bebop_tables::update_user_meta( $user_id, $extension, 'bebop_' . $extension . '_' . $username . '_daycounter', $new_count );
			return true;
		}
		return false;
	}
	
	//Check import limits
	public function import_limit_reached( $extension, $user_id, $username ) {
		//different day ot no day set, set the day and the counter to 0;
		if ( bebop_tables::get_user_meta_value( $user_id, 'bebop_' . $extension . '_' . $username . '_counterdate' ) != date( 'dmy' ) ) {
			bebop_tables::update_user_meta( $user_id, $extension, 'bebop_' . $extension . '_' . $username . '_daycounter', '0' );
			bebop_tables::update_user_meta( $user_id, $extension, 'bebop_' . $extension . '_' . $username . '_counterdate', date( 'dmy' ) );
		}
		
		//max items per day * < should return false*
		if ( bebop_tables::check_option_exists( 'bebop_' . $extension . '_maximport' ) ) {
			if ( bebop_tables::get_user_meta_value( $user_id, 'bebop_' . $extension . '_' . $username . '_daycounter' ) < bebop_tables::get_option_value( 'bebop_' . $extension . '_maximport' ) ) {
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
				$filterValue = str_replace( '/', '', $filterValue );
				
				if ( preg_match( '/' . $filterValue . '/', $content ) > 0 ) {
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