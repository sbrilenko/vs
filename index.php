<?php
    session_start();
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
    require_once 'uservar.php';
    include_once "class.product.php";
    $page = Page :: getInstance();
	$page -> setDoctype(Page :: $XHTML);
    $controller = new controller();
    $controller->setDelimiter('activate');
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
        	if (is_file($controller -> view))
        				{

        				    if($controller->getController()=='admin' AND $controller->view!='views/admin/adminIn.phtml' AND $controller->view!='views/admin/adminLogin.phtml')
                            {
                                require_once "include.php";
                                require_once "lock.php";
                            }
                            if($controller->view=="views/admin/adminIndex.phtml")
                            {
                                header('Location: /admin/firm');
                            }

                                require_once $admin.'_header.phtml';
            					require_once ($controller -> view);
            				    require_once $admin.'_footer.phtml';


        	} else {
        	   header("Location:/");
        	}

    
?>
