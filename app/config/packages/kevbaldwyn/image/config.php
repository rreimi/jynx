<?php

return array(

	/**
	 * the image worker (GD / Immagick)
	 */
	'worker' => '',

	'route' => '/_img',

	/**
	 * note this is the server path to the file 
	 * from base_path()
	 */
	'js_path' => '/public/js/imagecow.js',

	/**
	 * various $_GET variables
	 */
	'vars' => array(
		'image'           => 'img',
		'responsive_flag' => 'responsive',
		'transform'       => 'transform'
	),

	'cache' => array(
		'lifetime' => 1,
		'path'     => 'images' // /app/storage/cache/{images}
	)
 
);