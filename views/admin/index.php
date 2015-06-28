<?php

	/** добавляем папки в include_path */
	$path = array('lib');
	if (phpversion()<5.3){
		define('__DIR__',dirname(__FILE__));
	}
    $path = get_include_path().PATH_SEPARATOR.__DIR__.'/'.implode(PATH_SEPARATOR.__DIR__.'/',$path);
    set_include_path($path);
	require_once 'class.page.php';
    require_once 'class.controller.php';
	require_once 'class.invis.db.php';
    $page = Page :: getInstance();
	$page -> setDoctype(Page :: $XHTML);
    $controller = new controller();
	$db=db :: getInstance();
    $controller -> getView();
   
    
    if($controller->getController()=='admin')
    {
        $controller -> getView('admin');
        $admin='views/admin/';
    }
    else
    {
        $admin='views/';
    }
    $root = $_SERVER['DOCUMENT_ROOT'];
    print $controller -> view;
	if (is_file($controller -> view))
				{  
				    
	   
				    if($controller->getController()=='admin' AND $controller->view!='views/admin/adminIn.phtml' AND $controller->view!='views/admin/adminLogin.phtml')
                    {
                        require_once "include.php";
                        require_once "lock.php";
                    }
				    require_once $admin.'_header.phtml';
					require_once ($controller -> view);
					require_once $admin.'_footer.phtml';
			
	} else {
		header("HTTP/1.1 404 Not Found");
		require_once $admin.'_header.phtml';
		require_once $admin."404.phtml";
		require_once $admin.'_footer.phtml';
		
	}
?>
