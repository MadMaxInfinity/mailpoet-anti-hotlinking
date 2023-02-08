add_filter('mailpoet_sending_newsletter_render_after_pre_process', 'mr_process_mail', 10, 2);
function mr_process_mail ($aRenderedNewsletter, $oNewsletter) {
	$aRenderedNewsletter['html'] = mr_process_images( $aRenderedNewsletter['html'], $oNewsletter->getId() );
	return $aRenderedNewsletter;
}

function mr_process_images( $sHtml, $iId ) {
	// CREATE DIRECTORY
	$sDestDir = '/wp-content/uploads/mailpoet/newsletter_images/' . $iId;
	if ( !file_exists( ABSPATH . $sDestDir ) ) mkdir ( ABSPATH . $sDestDir, 0755, true );
	$sDetectionRegExp = '#(<img[^/>]+)(?<=\s)(src=)(\"|\')([^\"\']*)(\'|\")([^/>]*/>)#miUs';
	$sResult = preg_replace_callback( $sDetectionRegExp, function( $matches ) use( $sDestDir ) {
		// COMPUTE URI
		$src = $matches[4];
		$new_src = preg_replace( '#(.*)/wp-content/(.*)/([\w-]+)(\.(jpe?g|png|gif|webp|avif|svg))$#iU', '$1'.$sDestDir.'/$3$4', $src );
		$new_src = preg_replace( '#(.*)/wp-content/(.*)/([\w-]+)(\.(jpe?g|png|gif|webp|avif|svg))\?(.*)$#iU', '$1'.$sDestDir.'/$3$4?$6', $new_src );
		// COMPUTE PATHS
		$site_url = get_site_url(null, '/');
		$src_file = preg_replace('' . $site_url . '', ABSPATH, $src);
		$src_file = preg_replace('\?(.*)$', '', $src_file); // Remove query string
		if ( file_exists( $src_file ) ) {
			$dst_file = preg_replace('' . $site_url . '', ABSPATH, $new_src);
			$dst_file = preg_replace('\?(.*)$', '', $dst_file); // Remove query string
			// COPY FILES
			if ( file_exists( $dst_file ) ) unlink( $dst_file );
			if ( ( $in = fopen( $src_file, "rb" ) ) && ( $out = fopen( $dst_file, "wb" ) ) ) {
				while ( !feof( $in ) ) {
				  $buffer = fread( $in, 64 );
				  fwrite( $out, $buffer );
				}
				fclose( $out ); 
				fclose( $in );
				return $matches[1].$matches[2].$matches[3].$new_src.$matches[5].$matches[6];
			}
			return $matches[0]; // No change
		}
		return $matches[0]; // No change
	}, $sHtml );
	
	return $sResult;
}
