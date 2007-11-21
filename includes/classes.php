<?php

/* Configure Smarty */
require( SMARTY_DIR . '/Smarty.class.php');
class Smarty_PacerCMS extends Smarty
{   function Smarty_PacerCMS()   {        
        // These automatically get set with each new instance.        $this->Smarty();        $this->template_dir  = TEMPLATES_PATH;
		$this->compile_dir   = TEMPLATES_C_PATH;		$this->config_dir    = CONFIGS_PATH;		$this->cache_dir     = CACHE_PATH;
		$this->plugins_dir   = SMARTY_DIR . '/plugins';
		$this->debugging     = DEBUG_MODE;				$this->caching       = USE_TEMPLATE_CACHE;
		$this->compile_check = COMPILE_CHECK;
	    
	    switch( CM_INSTALLING )
	    {
    	    case 0:
  
        		$this->assign('site_name', site_info('name'));
        		$this->assign('site_url', site_info('url'));
        		$this->assign('site_email', site_info('email'));
        		$this->assign('site_description', site_info('description'));
        		$this->assign('site_address', site_info('address'));
        		$this->assign('site_city', site_info('city'));
        		$this->assign('site_state', site_info('state'));
        		$this->assign('site_zipcode', site_info('zipcode'));
        		$this->assign('site_telephone', site_info('telephone'));
        		$this->assign('site_fax', site_info('fax'));
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

	   }}

// Create Smarty object
$smarty = new Smarty_PacerCMS;
$smarty->caching = USE_TEMPLATE_CACHE;