<?php
/**
 * 
 */
class Plugin_Access extends Zend_Controller_Plugin_Abstract
{
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {				
		$controller = $request->getControllerName();
        
        // skip some controllers (ie. error)
		if ($controller == 'login' || $controller == 'error') {
			return; // do nothing
		}
        
        $module = $request->getModuleName();
        $response = $this->getResponse();

        $resourceId = $module . '/' . $controller;
		$privilege = $request->getActionName();

        // get the ACL resource
        $resource = App_AccessList::getResource($resourceId);
        if (!$resource){
            // fallback: see if just the module resource is there
            $resource = App_AccessList::getResource($module);
        }

        // only perform a check if we have a resource -> otherwise we assume the resource
        // is public; it keeps our Access control definition simple
        if ($resource){

            if ($module === 'api'){
                $this->authorizeApi($request, $response, $resource, $privilege);
            }
            else{
                $this->authorizeDefault($request, $response, $resource, $privilege);
            }
        }
		
    }

    /**
     * Authorize a webservice call.
     */
    private function authorizeApi($request, $response, $resource, $privilege){
        
        $api_key = null;
        $api_signature = null;
        
        $auth_header = $request->getHeader('Authorization');
        if (!empty($auth_header)){

            // pick apart the header which should be in the form:
            // API [api_id]:[signature]
            // where signature is simply a md5 hash of the id + secret key
            if (substr($auth_header, 0, 4) == 'API '){
                $auth_header = substr($auth_header, 4);

                $pos = strripos($auth_header, ':');
                if ($pos !== false){
                    $api_key = substr($auth_header, 0, $pos);
                    $api_signature = substr($auth_header, $pos+1);
                }
            }
            
        }else{

            // no header specified in request - try for query string params
            $api_key = $request->getParam('apikey');
            $api_signature = $request->getParam('signature');
        }

        // use NonPersistent storage since identity comes in on every request
        $storage = new Zend_Auth_Storage_NonPersistent();
        $adapter = new App_Auth_Adapter_Api($api_key, $api_signature);
        $auth = Zend_Auth::getInstance();
        $auth->setStorage($storage);
        
        $result = $auth->authenticate($adapter);

        try {

            if (!$result->isValid()){
                $errorMessages = $result->getMessages();
                throw new Exception('Service Authentication Failed: '.$errorMessages[0]);
            }

            $data = $storage->read();
            
            $user_role = $data['user_role'];
			$allowed = App_AccessList::isAllowed($user_role, $resource, $privilege);
            
			if (!$allowed){
				throw new Exception('Sorry, you do not have access.');
			}

            // set a param on the request so controllers can access the user-id
            $request->setParam('user_id', $data['user_id']);

		}
		catch (Exception $thrown)
		{
            // forward to error page
			$this->forwardException($request, $thrown, 'api');
		}

    }

    /**
     * Authorize a user to either the frontend or backend website.
     */
    private function authorizeDefault($request, $response, $resource, $privilege){

        $frontController = Zend_Controller_Front::getInstance();
        $bootstrap = $frontController->getParam('bootstrap');
        $session = $bootstrap->getResource('session');
        $storage = new App_Auth_Storage_Session($session);

		// default to 'guest' or public role
		$user_role = App_AccessList::ROLE_GUEST;

        // see if they are signed in and have a role
        if (!$storage->isEmpty()) {
            $user_role = $storage->getUserRole();
        }

        // set a parameter on the request with the complete full URL of the request
        // this can be used by the login controller to send user onto where they were
        // originally trying to get to once they login -- GAW
		$target = $this->getFullRequestUrl($request);
		$request->setParam("_request_url", $target);

		$allowed = false;

		try {

			$allowed = App_AccessList::isAllowed($user_role, $resource, $privilege);

			if (!$allowed){

				// throw an exception - will end up in our error handler
                // TODO: redirect to a 'pretty' Unauthorized page
				if (!$storage->isEmpty()) {

					throw new Exception('Sorry, you do not have access to the requested resource.');
				}
				else{

					// forward to login (note above _request_url param on the request) - this
                    // is there so that after login the user will be redirected to the intended
                    // destination
					$request->setModuleName('frontend')
					        ->setControllerName('login')
					        ->setActionName('index')
							->setDispatched(false);
				}

			}
		}
		catch (Exception $thrown)
		{
            $this->forwardException($request, $thrown, 'frontend');
		}

    }

	/**
	* Builds a complete (absolute) URL for the current request
     *
     * $request - the current request
     * $scheme - can be used to override the current url scheme, to switch
     * between HTTP/HTTPS for example
	*/
	private function getFullRequestUrl($request, $scheme=null) {

        $host  = (isset($_SERVER['HTTP_HOST'])?$_SERVER['HTTP_HOST']:'');

        if (empty($scheme)){
            $proto = (isset($_SERVER['HTTPS'])&&$_SERVER['HTTPS']!=="off") ? 'https' : 'http';
            $port = (isset($_SERVER['SERVER_PORT'])?$_SERVER['SERVER_PORT']:80);
        }
        else{
            $proto = $scheme;
            if ($proto == 'http'){
                $port = 80;
            }else if ($proto == 'https'){
                $port = 443;
            }
        }

        $uri   = $proto . '://' . $host;
        if ((('http' == $proto) && (80 != $port)) || (('https' == $proto) && (443 != $port))) {
            $uri .= ':' . $port;
        }

        $uri = strtolower($uri) . $request->getServer('REQUEST_URI');

        return $uri;
    }

    /**
	* Forwards the given exception to the appropriate error handler
	*/
    private function forwardException($request, $exception, $module)
    {
        $error = new ArrayObject(array(), ArrayObject::ARRAY_AS_PROPS);
        $error->exception = $exception;
        $error->type = 'EXCEPTION_OTHER';

        // Keep a copy of the original request
        $error->request = clone $request;

        // Forward to the error handler
        $request->setParam('error_handler', $error)
            ->setModuleName($module)
            ->setControllerName('error')
            ->setActionName('error')
            ->setDispatched(false);
    }
}
