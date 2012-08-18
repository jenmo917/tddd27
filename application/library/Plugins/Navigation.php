<?PHP
class Plugins_Navigation extends Zend_Controller_Plugin_Abstract
{
    /**
    * Instance of Zend_Translate
    * @var Zend_Translate $_translator
    */
    protected $_translate;
    protected $_pagePrefix;
    protected $_delimiter;

    /*
    * Get the top Level menu.
    * @author	Jens Moser <jenmo917@gmail.com>
    * @since	v0.1
    * @return	array
    */
    public function getTopLevelMenu()
    {
    	$del = $this->_delimiter;
    	$pre = $this->_pagePrefix;
        $pages = array(
                array(
                    'label'		=> $this->_translate->translate('Start'),
                    'module'	=> 'default',
                    'controller'=> 'index',
                    'action'	=> 'index',
                    'resource'	=> $pre.$del.'default'.$del.'index'.$del.'index',
                    'privilege'	=> 'resourceStackCheck',
                ),
                array(
                    'label'     => $this->_translate->translate('Create Event'),
                    'module'    => 'admin',
                    'controller'=> 'event',
                    'action'    => 'create-event',
                    'resource'  => $pre.$del.'admin'.$del.'event'.$del.'create-event',
                    'privilege'	=> 'resourceStackCheck',
                ),
                array(
                    'label'     => $this->_translate->translate('My Events'),
                    'module'    => 'admin',
                    'controller'=> 'event',
                    'action'    => 'index',
                    'resource'  => $pre.$del.'admin'.$del.'event'.$del.'index',
                    'privilege'	=> 'resourceStackCheck',
                ),
                array(
                    'label'     => 'Sign in', //TODO: Translate!
                    'module'    => 'login',
                    'controller'=> 'index',
                    'action'    => 'index',
                    'resource'  => $pre.$del.'login'.$del.'index'.$del.'index',
                    'privilege'	=> 'resourceStackCheck',
                	),
                array(
                    'label'     => 'Sign out', //TODO: Translate!
                    'module'    => 'login',
                    'controller'=> 'index',
                    'action'    => 'logout',
                    'resource'  => $pre.$del.'login'.$del.'index'.$del.'logout',
                    'privilege'	=> 'resourceStackCheck',
                	),
            );

        return $pages;
    }

    /*
    * Get the event menu.
    * @author	Jens Moser <jenmo917@gmail.com>
    * @since	v0.1
    * @return	array
    */
    public function getEventMenu($params)
    {
       $pages = array(
            array(
                'label'     => $this->_translate->translate('Overview'),
                'module'    => 'admin',
                'controller'=> 'event',
                'action'    => 'index',
                'params'    => array('event_id' => $params['event_id'])
            ),
            array(
                'label'     => $this->_translate->translate('Sell Tickets'),
                'module'    => 'admin',
                'controller'=> 'event',
                'action'    => 'sell',
                'params'    => array('event_id' => $params['event_id'])
            ),
            array(
                'label'     => $this->_translate->translate('Attendees'),
                'module'    => 'admin',
                'controller'=> 'event',
                'action'    => 'attendees',
                'params'    => array('event_id' => $params['event_id'])
            )

        );
        return $pages;
    }

    /*
    * Init menus
    * @author	Jens Moser <jenmo917@gmail.com>
    * @since	v0.1
    * @return	array
    */
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        $this->_translate = Zend_Registry::get('Zend_Translate');
    	$this->_pagePrefix = Acl_Resources::PAGEPREFIX;
    	$this->_delimiter = Acl_Resources::DELIMITER;

        // get params
        $params = $request->getParams();
        // Always view the top level menu
        $topLevelMenu = $this->getTopLevelMenu();
        // Create menu container
        $topLevelMenu   = new Zend_Navigation(new Zend_Config($topLevelMenu));
        // Save menu in registry
        Zend_Registry::set('topLevelMenu',$topLevelMenu);

        // View event menu if controller is equal to event
        if($params['controller'] == 'event')
        {
            // Get menu
            $eventMenu = $this->getEventMenu($params);
            // Create menu container
            $eventMenu   = new Zend_Navigation(new Zend_Config($eventMenu));
            // Save menu in registry
            Zend_Registry::set('verticalMenu',$eventMenu);
        }
    }
}
?>
