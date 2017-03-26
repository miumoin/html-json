# html-json
What if we could write website in JSON format instead of xml/html.

<?php
	//a single node example
	$a_single_tag = array(
		'tag'		=>	'div',
		'endtag'	=>	'1',	//does the ending tag required, for <meta> endtag is false ("0")
		'attributes'=>	array(	//attribute must be unique, get a list of possible attributes of available tags
				'styles'	=>	'font-color:red; font-size:40px;',
				'onclick'	=>	'javascripts:do_the_event(); return false;',
				'class'		=>	'row editor',
				'id'		=>	'the_div_id'
			),
		'content'	=>	'this is a text content', //only for <p>, <span>, <blockquote>, etc
		//content may contain html tags and anything
		'nodes'		=>	array( $another_singl_tag, $yet_another_single_tag )	//if content exists, it is the leaf node, it will not have any children nodes
		//tags with no end tag will not have children nodes, not even a content
		);

	//following is an example of the created json configuration for the funnel page
 	$template =	array(
 		'tag'		=>	'html',
 		'endtag'	=>	1,
 		'attributes'=>	array(
 			'lang'	=>	'en',
 			'class'	=>	'fixed-width-page'
 			),
 		'nodes'		=>	array(
 			array(
 				'tag'		=>	'head',
 				'endtag'	=>	1,
 				'attributes'=>	array(),
 				'nodes'		=> 	array(
 					array(
 						'tag'		=>	'meta',
 						'endtag'	=>	0,
 						'attributes'=>	array(
 							'name'		=>	'viewport',
 							'content'	=>	'width=device-width,intitial-scale=1.0'
 							)
 						),
 					array(
 						'tag'		=>	'link',
 						'endtag'	=>	0,
 						'attributes'=>	array(
 							'rel'	=>	'stylesheet',
 							'type'	=>	'text/css',
 							'href'	=>	'//fonts.googleapis.com/css?family=Lato'
 							)
 						)
 					)
 				),
 			array(
 				'tag'		=>	'body',
 				'endtag'	=>	1,
 				'attributes'=>	array(),
 				'nodes'		=>	array(
 					array(),
 					array(),
 					array()
 					)
 				)
 			)

 		);
?>
