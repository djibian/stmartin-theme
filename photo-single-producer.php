<?php
	/* ajout une photo du producteur et le code postal + ville de son entreprise dans la sidebar */
	$identity_photo_id = get_post_meta($post->ID, 'photo', true);

	if ( $identity_photo_id && get_post( $identity_photo_id ) )
	{
		$identity_photo_src = wp_get_attachment_image_url( $identity_photo_id, 'full' );
	}
	else
	{
		$identity_photo_src = plugins_url() . '/bocalenbalade/assets/images/placehodlerimage-photoprovider.png';
	}
	?>
	<img class="producer-identity-photo" src="<?php echo esc_url($identity_photo_src); ?>" alt=""/>	
