<?php
/**
 * Bootstrap class file.
 * @author Christoffer Niska <ChristofferNiska@gmail.com>
 * @copyright Copyright &copy; Christoffer Niska 2011-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @version 1.0.0
 */

/**
 * Bootstrap application component.
 */
class Bootstrap extends CApplicationComponent
{
	// Bootstrap plugins.
	const PLUGIN_ALERT = 'alert';
    const PLUGIN_BUTTON = 'button';
    const PLUGIN_CAROUSEL = 'carousel';
    const PLUGIN_COLLAPSE = 'collapse';
    const PLUGIN_DROPDOWN = 'dropdown';
    const PLUGIN_MODAL = 'modal';
    const PLUGIN_POPOVER = 'popover';
    const PLUGIN_SCROLLSPY = 'scrollspy';
    const PLUGIN_TAB = 'tab';
    const PLUGIN_TOOLTIP = 'tooltip';
    const PLUGIN_TRANSITION = 'transition';
    const PLUGIN_TYPEAHEAD = 'typeahead';
    // todo: add the affix plugin in version 2.1.0

	/**
	 * @var boolean whether to register the Bootstrap core CSS (bootstrap.min.css).
	 * Defaults to true.
	 */
	public $coreCss = true;
	/**
	 * @var boolean whether to register the Bootstrap responsive CSS (bootstrap-responsive.min.css).
	 * Defaults to false.
	 */
	public $responsiveCss = true;
	/**
	 * @var boolean whether to register the Yii-specific CSS missing from Bootstrap.
	 * @since 0.9.12
	 */
	public $yiiCss = true;
	/**
	 * @var boolean whether to register jQuery and the Bootstrap JavaScript.
	 * @since 0.9.10
	 */
	public $enableJS = true;
	/**
	 * @var array plugin initial options (name=>options).
	 * Each array key-value pair represents the initial options for a single plugin class,
	 * with the array key being the plugin name, and array value being the initial options array.
	 * @since 0.9.8
	 */
	public $plugins = array();
	/**
	 * @var string default popover CSS selector.
	 * @since 0.10.0
	 */
	public $popoverSelector = 'a[rel="popover"]';
	/**
	 * @var string default tooltip CSS selector.
	 * @since 0.10.0
	 */
	public $tooltipSelector = 'a[rel="tooltip"]';

	protected $_assetsUrl;

	/**
	 * Initializes the component.
	 */
	public function init()
	{
		// Register the bootstrap path alias.
		if (Yii::getPathOfAlias('bootstrap') === false)
			Yii::setPathOfAlias('bootstrap', realpath(dirname(__FILE__).'/..'));

		// Prevents the extension from registering scripts and publishing assets when ran from the command line.
		if (Yii::app() instanceof CConsoleApplication)
			return;

		if ($this->coreCss !== false)
			$this->registerCoreCss();

		if ($this->responsiveCss !== false)
			$this->registerResponsiveCss();

		if ($this->yiiCss !== false)
			$this->registerYiiCss();

		if ($this->enableJS !== false)
			$this->registerCoreScripts();

        parent::init();
	}

	/**
	 * Registers the Bootstrap CSS.
	 */
	public function registerCoreCss()
	{
		// $baseUrl = Yii::app()->baseUrl;
		// Yii::app()->clientScript->registerCssFile($baseUrl.'/css/insp/bootstrap.min.css');
		//Yii::app()->clientScript->registerCssFile($this->getAssetsUrl().'/css/font-awesome.min.css');
		//Yii::app()->clientScript->registerCssFile($this->getAssetsUrl().'/css/smartadmin-production.css');
		//Yii::app()->clientScript->registerCssFile($this->getAssetsUrl().'/css/smartadmin-skins.css');
		//Yii::app()->clientScript->registerCssFile($this->getAssetsUrl().'/css/demo.css');
	}

	/**
	 * Registers the Bootstrap responsive CSS.
	 * @since 0.9.8
	 */
	public function registerResponsiveCss()
	{
		/** @var CClientScript $cs */
		// $cs = Yii::app()->getClientScript();
		// $cs->registerMetaTag('width=device-width, initial-scale=1.0', 'viewport');
	}

	/**
	 * Registers the Yii-specific CSS missing from Bootstrap.
	 * @since 0.9.11
	 */
	public function registerYiiCss()
	{
		// Yii::app()->clientScript->registerCssFile($this->getAssetsUrl().'/css/bootstrap-yii.css');
	}

	/**
	 * Registers the core JavaScript.
	 * @since 0.9.8
	 */
	public function registerCoreScripts()
	{
		// $this->registerJS(Yii::app()->clientScript->coreScriptPosition);
		// $this->registerTooltip();
		// $this->registerPopover();
	}

	/**
	 * Registers the Bootstrap JavaScript.
	 * @param int $position the position of the JavaScript code.
	 * @see CClientScript::registerScriptFile
	 */
	public function registerJS($position = CClientScript::POS_END)
	{
		/** @var CClientScript $cs */
		// $cs = Yii::app()->getClientScript();
		// $cs->registerScriptFile($this->getAssetsUrl().'/js/libs/jquery-2.0.2.min.js');
		// $cs->registerScriptFile($this->getAssetsUrl().'/js/libs/jquery-ui-1.10.3.min.js', CClientScript::POS_END);
        // $cs->registerScriptFile($this->getAssetsUrl().'/js/bootstrap/bootstrap.min.js');
        // $cs->registerScriptFile($this->getAssetsUrl().'/js/notification/SmartNotification.min.js', CClientScript::POS_END);
        // $cs->registerScriptFile($this->getAssetsUrl().'/js/smartwidgets/jarvis.widget.min.js', CClientScript::POS_END);
        // $cs->registerScriptFile($this->getAssetsUrl().'/js/plugin/easy-pie-chart/jquery.easy-pie-chart.min.js', CClientScript::POS_END);
        // $cs->registerScriptFile($this->getAssetsUrl().'/js/plugin/sparkline/jquery.sparkline.min.js', CClientScript::POS_END);
        // $cs->registerScriptFile($this->getAssetsUrl().'/js/plugin/jquery-validate/jquery.validate.min2.js', CClientScript::POS_END);
        // $cs->registerScriptFile($this->getAssetsUrl().'/js/plugin/masked-input/jquery.maskedinput.min.js', CClientScript::POS_END);
        // //$cs->registerScriptFile($this->getAssetsUrl().'/js/plugin/select2/select2.min.js', CClientScript::POS_END);
        // $cs->registerScriptFile($this->getAssetsUrl().'/js/plugin/bootstrap-slider/bootstrap-slider.min.js', CClientScript::POS_END);
        // $cs->registerScriptFile($this->getAssetsUrl().'/js/plugin/msie-fix/jquery.mb.browser.min.js', CClientScript::POS_END);
        // $cs->registerScriptFile($this->getAssetsUrl().'/js/plugin/fastclick/fastclick.js', CClientScript::POS_END);
        //$cs->registerScriptFile($this->getAssetsUrl().'/js/demo.js', CClientScript::POS_END);
        //$cs->registerScriptFile($this->getAssetsUrl().'/js/app.js', CClientScript::POS_END);
	}

	/**
	 * Registers the Bootstrap alert plugin.
	 * @param string $selector the CSS selector
	 * @param array $options the plugin options
	 * @see http://twitter.github.com/bootstrap/javascript.html#alerts
	 * @since 0.9.8
	 */
	public function registerAlert($selector = null, $options = array())
	{
		$this->registerPlugin(self::PLUGIN_ALERT, $selector, $options);
	}

	/**
	 * Registers the Bootstrap buttons plugin.
	 * @param string $selector the CSS selector
	 * @param array $options the plugin options
	 * @see http://twitter.github.com/bootstrap/javascript.html#buttons
	 * @since 0.9.8
	 */
	public function registerButton($selector = null, $options = array())
	{
		$this->registerPlugin(self::PLUGIN_BUTTON, $selector, $options);
	}

	/**
	 * Registers the Bootstrap carousel plugin.
	 * @param string $selector the CSS selector
	 * @param array $options the plugin options
	 * @see http://twitter.github.com/bootstrap/javascript.html#carousel
	 * @since 0.9.8
	 */
	public function registerCarousel($selector = null, $options = array())
	{
		$this->registerPlugin(self::PLUGIN_CAROUSEL, $selector, $options);
	}

	/**
	 * Registers the Bootstrap collapse plugin.
	 * @param string $selector the CSS selector
	 * @param array $options the plugin options
	 * @see http://twitter.github.com/bootstrap/javascript.html#collapse
	 * @since 0.9.8
	 */
	public function registerCollapse($selector = null, $options = array())
	{
		$this->registerPlugin(self::PLUGIN_COLLAPSE, $selector, $options, '.collapse');
	}

	/**
	 * Registers the Bootstrap dropdowns plugin.
	 * @param string $selector the CSS selector
	 * @param array $options the plugin options
	 * @see http://twitter.github.com/bootstrap/javascript.html#dropdowns
	 * @since 0.9.8
	 */
	public function registerDropdown($selector = null, $options = array())
	{
		$this->registerPlugin(self::PLUGIN_DROPDOWN, $selector, $options, '.dropdown-toggle[data-dropdown="dropdown"]');
	}

	/**
	 * Registers the Bootstrap modal plugin.
	 * @param string $selector the CSS selector
	 * @param array $options the plugin options
	 * @see http://twitter.github.com/bootstrap/javascript.html#modal
	 * @since 0.9.8
	 */
	public function registerModal($selector = null, $options = array())
	{
		$this->registerPlugin(self::PLUGIN_MODAL, $selector, $options);
	}

	/**
	 * Registers the Bootstrap scrollspy plugin.
	 * @param string $selector the CSS selector
	 * @param array $options the plugin options
	 * @see http://twitter.github.com/bootstrap/javascript.html#scrollspy
	 * @since 0.9.8
	 */
	public function registerScrollSpy($selector = null, $options = array())
	{
		$this->registerPlugin(self::PLUGIN_SCROLLSPY, $selector, $options);
	}

	/**
	 * Registers the Bootstrap popover plugin.
	 * @param string $selector the CSS selector
	 * @param array $options the plugin options
	 * @see http://twitter.github.com/bootstrap/javascript.html#popover
	 * @since 0.9.8
	 */
	public function registerPopover($selector = null, $options = array())
	{
		$this->registerTooltip(); // Popover requires the tooltip plugin
		$this->registerPlugin(self::PLUGIN_POPOVER, $selector, $options, $this->popoverSelector);
	}

	/**
	 * Registers the Bootstrap tabs plugin.
	 * @param string $selector the CSS selector
	 * @param array $options the plugin options
	 * @see http://twitter.github.com/bootstrap/javascript.html#tabs
	 * @since 0.9.8
	 */
	public function registerTabs($selector = null, $options = array()){
		$this->registerPlugin(self::PLUGIN_TAB, $selector, $options);
	}

	/**
	 * Registers the Bootstrap tooltip plugin.
	 * @param string $selector the CSS selector
	 * @param array $options the plugin options
	 * @see http://twitter.github.com/bootstrap/javascript.html#tooltip
	 * @since 0.9.8
	 */
	public function registerTooltip($selector = null, $options = array())
	{
		$this->registerPlugin(self::PLUGIN_TOOLTIP, $selector, $options, $this->tooltipSelector);
	}

	/**
	 * Registers the Bootstrap typeahead plugin.
	 * @param string $selector the CSS selector
	 * @param array $options the plugin options
	 * @see http://twitter.github.com/bootstrap/javascript.html#typeahead
	 * @since 0.9.8
	 */
	public function registerTypeahead($selector = null, $options = array())
	{
		$this->registerPlugin(self::PLUGIN_TYPEAHEAD, $selector, $options);
	}

	/**
	 * Registers a Bootstrap JavaScript plugin.
	 * @param string $name the name of the plugin
	 * @param string $selector the CSS selector
	 * @param array $options the plugin options
	 * @param string $defaultSelector the default CSS selector
	 * @since 0.9.8
	 */
	protected function registerPlugin($name, $selector = null, $options = array(), $defaultSelector = null)
	{
		if (!isset($selector) && empty($options))
		{
			// Initialization from extension configuration.
			$config = isset($this->plugins[$name]) ? $this->plugins[$name] : array();

			if (isset($config['selector']))
				$selector = $config['selector'];

			if (isset($config['options']))
				$options = $config['options'];

			if (!isset($selector))
				$selector = $defaultSelector;
		}

		if (isset($selector))
		{
			$key = __CLASS__.'.'.md5($name.$selector.serialize($options).$defaultSelector);
			$options = !empty($options) ? CJavaScript::encode($options) : '';
			Yii::app()->clientScript->registerScript($key, "jQuery('{$selector}').{$name}({$options});");
		}
	}

	/**
	* Returns the URL to the published assets folder.
	* @return string the URL
	*/
	protected function getAssetsUrl()
	{
		if (isset($this->_assetsUrl))
			return $this->_assetsUrl;
		else
		{
			$assetsPath = Yii::getPathOfAlias('bootstrap.assets');
			$assetsUrl = Yii::app()->assetManager->publish($assetsPath, false, -1);
			return $this->_assetsUrl = $assetsUrl;
		}
	}

    /**
     * Returns the extension version number.
     * @return string the version
     */
    public function getVersion()
    {
        return '1.0.0';
    }
}
