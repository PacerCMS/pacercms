<?php

require( ADODB_DIR . '/adodb.inc.php'); // Database Layer
require( SMARTY_DIR . '/Smarty.class.php'); // Smarty Template Engine


/* Configure Smarty */
class Smarty_PacerCMS extends Smarty
{
        // These automatically get set with each new instance.
		$this->debugging     = DEBUG_MODE;		
		$this->compile_check = COMPILE_CHECK;
	    
	    switch( CM_INSTALLING )
	    {
    	    case 0:
  
        		$this->assign('site_name', site_info('name'));
        		$this->assign('site_url', site_info('url'));
        		$this->assign('site_description', site_info('description'));
        		$this->assign('site_sections', section_list('array'));
        		$this->assign('site_templates_folder', TEMPLATES_FOLDER);
        		$this->assign("current_issue_date", current_issue('date') . " 00:00:01");
        
            break;
            
            case 1:
                
                $this->debugging     = false;
                $this->caching       = false;
                $this->compile_check = false;
                
            break;
	    }
        		
		$this->assign('site_cm_version', CM_VERSION);

	   }