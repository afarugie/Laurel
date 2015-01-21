<?php //strict

require_once 'BaseController.php';
require_once 'HelperFactory.php';
require_once 'Helper.php';
require_once 'BaseHelper.php';


$helpers = new HelperFactory();
$helper = $helpers->newInstance();


$dir = new DirectoryIterator(HELPER_PATH);

foreach ($dir as $fileinfo) {
    if (!$fileinfo->isDot()) {
    

        $helperInfo = explode('.',$fileinfo->getFilename());
        $helperName = $helperInfo[0];
        
        require_once HELPER_PATH.'/'.$helperName.'.php';
        
		$helper->set($helperName,function() use($helperName){
			
			return new $helperName();
		    
		}); 
		       
    }
}


