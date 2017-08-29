<?php

class Router {

	private $uriPatterns = array();
	private $uriCallbacks = array();

	/**
	* @var string $_trim Class-wide items to clean
	*/
	private $_trim = '/\^$';

	/**
	* add - Adds a URI and Function to the two lists
	*
	* @param string $uri A path such as about/system
	* @param object $callback An anonymous function
	*/
	public function add($uri, $callback)
	{
		$uri = trim($uri, $this->_trim);
		$this->uriPatterns[] = $uri;
		$this->uriCallbacks[] = $callback;
	}

	/**
	* perform - Looks for a match for the URI and runs the related function
	*/
	public function perform()
	{
		$uri = isset($_REQUEST['uri']) ? $_REQUEST['uri'] : '/';
		$uri = trim($uri, $this->_trim);

		$replacementValues = array();

		/**
		* List through the stored URI's
		*/
		foreach ($this->uriPatterns as $listKey => $listUri)
		{
			/**
			* See if there is a match
			*/
      // var_dump($listKey);
      // var_dump($listUri);
			if (preg_match("#^$listUri$#", $uri))
			{
				/**
				* Replace the values
				*/
				$realUri = explode('/', $uri);
				$fakeUri = explode('/', $listUri);
        // var_dump($realUri);
        // var_dump($fakeUri);
				/**
				* Gather the .+ values with the real values in the URI
				*/
				foreach ($fakeUri as $key => $value)
				{
					if ($value == '.+') {
						$replacementValues[] = $realUri[$key];
					}
				}

        // print_r($replacementValues);
				/**
				* Pass an array for arguments
				*/
        $className = $this->uriCallbacks[$listKey][0];
        $instance = new $className;
        print_r($instance);
        $method = $this->uriCallbacks[$listKey][1];
				call_user_func_array(array($instance, $method), $replacementValues );
			}
		}

	}

}
