# html-json
What if we could write website in JSON format instead of xml/html.

	array(
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
