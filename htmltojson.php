<?php
	session_start();

	//grab content from the URL
	//read css scripts and add them by class/tag/id into attributes
	if( isset( $_REQUEST['url'] ) ) {
		if( !isset( $_SESSION['html'] ) ) {
			$html = file_get_contents( $_REQUEST['url'] );
			$_SESSION['html'] = $html;
		} else {
			$html = $_SESSION['html'];
		}

		$depth = 0;
		$elements = explode( '<', $html );

		for( $i = 0; $i < count( $elements ); $i++ ) {
			if( trim( $elements[$i] ) != '' ) {
				$tag_elements = explode( '>', $elements[$i] );
				$tag_attributes = explode( ' ', trim( $tag_elements[0] ) );
				if( ( strpos( $tag_attributes[0], '/' ) === false ) && ( strpos( $tag_attributes[0], '!' ) === false ) ) {
					$thetag = $tag_attributes[0];

					//check the tag has any ending tag anywhere
					if( isset( $noendtags ) && in_array( $thetag, $noendtags ) ) $endtag_exists = 0;
					elseif( ( strpos( $html, '</' . $thetag . '>' ) !== false ) || ( strpos( $html, '</' . $thetag . ' >' ) !== false ) ) $endtag_exists = 1;
					else {
						$noendtags[] = $thetag;
						$endtag_exists = 0;
					}

					$the_content = trim( $tag_elements[1] );

					$attributes_elements = explode( '"', str_replace( $thetag, '', $tag_elements[0] ) );
					$j = 0;
					while( $j < count( $attributes_elements ) ) {
						$attribute_segments = explode( '=', trim( $attributes_elements[ $j ] ) );
						$attribute_name = trim( $attribute_segments[0] );
						$attribute_value = trim( $attributes_elements[ $j + 1 ] );
						if( $attribute_name != '' ) $attributes[ $attribute_name ] = $attribute_value;
						$j = $j + 2;
					}

					$trail[$depth] = ( isset( $trail[$depth] ) ? ( $trail[$depth] + 1 ) : 0 );
					$the_trail = '';
					$the_parent_trail = '';
					for( $k = 0; $k <= $depth; $k++ ) {
						if( $k < $depth ) $the_parent_trail .= ( $the_parent_trail != '' ? '_' : '' ) . $trail[$k];
						$the_trail .= ( $the_trail != '' ? '_' : '' ) . $trail[$k];
					}

					if( $trail[$depth] > $trail_maximums[ strval( $the_parent_trail ) ] ) $trail_maximums[ $the_parent_trail ] = $trail[$depth];
					if( $depth > $max_depth ) $max_depth = $depth;

					if( $endtag_exists == 1 ) $depth = ( $depth + 1 );

					$elements_unsorted[] = array(
						'type'			=>	'start_tag',
						'trail'			=>	$the_trail,
						'parent_trail'	=>	$the_parent_trail,
						'attributes'	=>	$attributes,
						'tag'			=>	$thetag,
						'endtag'		=>	$endtag_exists,
						'content'		=>	$the_content
						);

				} elseif( strpos( $tag_attributes[0], '/' ) !== false ) {
					$the_end_tag = str_replace( '/', '', $tag_attributes[0] );
					$elements_unsorted[] = array(
						'type'		=>	'end_tag',
						'tag'		=>	$the_end_tag
						);

					$trail[$depth] = 0;
					$depth = ( $depth - 1 );
				}

				unset( $thetag );
				unset( $the_content );
				unset( $attributes );
				unset( $endtag_exists );
			}
		}

		$nodes = get_nodes( $elements_unsorted, '' );
		echo '<pre>';
      var_dump( $nodes );
    echo '</pre>';
	}

	function get_nodes( $elements, $parent_trail ) {
		foreach( $elements as $element ) {
			if( ( $element['parent_trail'] == $parent_trail ) && ( $element['type'] == 'start_tag' ) ) {
				$the_children = array(
					'tag'		=>	$element['tag'],
					'endtag'	=>	$element['endtag'],
					'attributes'=>	$element['attributes']
					);
				if( $element['content'] != '' ) $the_children['content'] = $element['content'];

				$nodes = get_nodes( $elements, $element['trail'] );
				if( count( $nodes) > 0 ) $the_children['nodes'] = $nodes;
				$children[] = $the_children;
			}
		}
		return $children;
	}
?>
