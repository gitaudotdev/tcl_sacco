<?php
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'TCL',

	// preloading 'log' component
	'preload'=>array('log','input','bootstrap','booster','PHPPowerpoint'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
		'application.extensions.input.components.*',
		'application.extensions.phpexcel.*',
		'application.extensions.select2.*',
		'application.extensions.booster.*',
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'@!critical',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		),
		
	),

	// application components
	'components'=>array(

		'session' => array(
			'timeout' => 3600,
		),
		
		'bootstrap'=>array(
	      'class'=>'ext.bootstrap.components.Bootstrap',
	  ),
	  
  	'booster' => array(
        'class' => 'ext.booster.components.Booster'
    ),

		'user'=>array(
			/*cookie-based authentication*/
			'allowAutoLogin'=>true,
		),

		// uncomment the following to enable URLs in path-format
		
		'urlManager'=>array(
			'urlFormat'=>'path',
			'rules'=>array(
				/*SiteController*/
				'16bb91f54a1eec76759b8e0d314360708364signin59bb55a6759bb8bb55a894bb55a894'=>'site/login',
				'0216bb91f54a1eec7675916bb9159bb55a6759bb8f54a1eec76759bbsignupbb55a894'=>'site/sign_up',
				'10216bb91f54a1eec76759b8e0d3143607083643forgottbb55a894enbb55a894'=>'site/forgot',
				'10216bb91f54a1eec3643bb55a8946erbb55a89476759b8e0d31436070833643bb55a894664'=>'site/token',
				'contact_us'=>'site/contact',
				'216bb91f54a1eec76759b8e0d31436070836459bb55a6759bb8signout59bb55a6759bb8'=>'site/logout',
				/*DashboardController*/
				'10216bb91f54a1eec76759badmbb55a894in32sd132ec76759b8e0d31436070836'=>'dashboard/admin',
				'10216bb91f54a1eec76759bdbbb55a894bb55a894b55a894ashboard59bb55a6759bb8bb55a894'=>'dashboard/default',
				'8e0d3143607083643bb55a8946e59bb55a6759bb8sbb55a894tatsbb55a894'=>'dashboard/index',
				'8e0d3143607083643bb55a8946ebranch59bb55a6759bb8stats59bb55a6759bb8'=>'dashboard/branch',
				'10216bb91f54a1eec76759b59bb55a6759bb8staff59bb55a6759bb8stats'=>'dashboard/staff',
				'8e0d3143607083643bb55a894610216bb91f54a1eec76759be59bb55a6759bb8profile59bb55a6759bb8'=>'dashboard/profile',
				'10216bb91f54a1eec76759b8e0d3143607083643passwor59bb55a6759bb8d59bb55a6759bb8'=>'dashboard/password',
				/*Loans*/
				'10216bb91f54a1eec76759ghsfsg762561ac59bb55a6759bb8counts59bb55a6759bb8'=>'loanaccounts/admin',
				'10216bb91f54a1eec76759ghsfsg762561ab91f54a1ee59bb55ac59bb55a6759bb8b91f54a1ee59bb55a5955a6759bb8'=>'alertConfigs/admin',
				'10216bb91f54a1eec76759ghsfsg762561ac59bb55a6759bb8cou8e0d314368b5nts59bb55a6759bb8'=>'loanaccounts/due',
				'10216bb91f54a1eec76759gh8ee0dd3bb5759bb8c76sfs8ee0dd3bb5759bb8c76g762561ac59b8ee0dd3bb5759bb8c76b55a6759bb8cou8e0d314368b5nts59bb55a6759bb8'=>'loanaccounts/profitAndLoss',
				'10216bb91f54a1eec76759ghsfsg762561ac59bbcsr9b8ee0dd3bb5759bb8c7655a6759bb8counts59bb55a6759bb8'=>'loanaccounts/create',
				'8e0d3143607083643bb55a8946YH07083640CQEr70836476759b859bb55a6759bb8e0d3143'=>'loancomments/admin',
				'8e0d3143607083643bb55a8946accruedc76759b859bb55a6759bb8e0d3143'=>'loaninterests/admin',
				'8e0d3143607083643bb55a8946erepopay1f54a1e59bb55a6759bb8ec76759b'=>'loanrepayments/repo',
				'10216bb91f54a1eec76759b59bb55a6759bb8repadmin54a1eeca67593607083643bb55'=>'loanrepayments/admin',
				'10216bb91f54a1eec76759b59bb55a6759bb8r3143607083614fren54a1eeca67593603143607083614fre7083643bb55'=>'strayRepayments/admin',
				'10216bb91f54a1eec767598sbb55a8b59bb55a6759bb8rep8sbb55a8adm8sbb55a8in54a1eeca67593607083643bb55'=>'loanrepayments/accountCollections',
				'8e0d3143607083643bb55a894610216bb91f54a1ee59b6759bb8e0d314368b55a6759bb8c767597bb55a6759bb808364'=>'loanaccounts/disbursedAccounts',
				'8e0d3143607083643bb55a894610216bb91f54a1ee59b6759bb8e0d314368b55a6759bb8c76759dailbb55a6759bb808364yr76759ghsfsg7'=>'loanaccounts/dailyAccountReport',
				'8e0d3143607083643bb55a894610216bb91f54a1ee59bb55a6759bb8c76759dailyr76759ghsfsg7'=>'loanaccounts/dailyReport',
				'8e0d3143607083643bb55a894610216bb91f54a1ea6759bb8e0d31e59bb55a6759ba6759bb8e0d31b8c76759a6759bb8e0d31'=>'writeOffs/writeoffsReport',
				/*Savings*/
				'10216bb91f54a1eec7083643bb55a6759bb55a6759bb8e0d314368e0d3ec76759b8e0d31436070836143607083643'=>'savingaccounts/admin',
				'10216bb91f54a1eec7083643bb55a9b8e0d314360706759bb55a6759bb8e0d314368e9b8e0d314360700d3ec76759b8e0d31436070836143607083643'=>'savingaccounts/savingAccountsReport',
				'10216bb91f54a1eec7083643bb55a6759bb8e0d3143607083614360759bb55a6759bb8083643bb55a894'=>'savingtransactions/admin',
				/*Member*/
				'10216bb91f54abb55a891eec708364rwqu643bb5atscsr9b8e0dd3bb5759bb8c756TSmsns6759708361excyt76755a6759bb8e0d31436070836143607083643'=>'reports/executiveSummary',
				'10216bb91f54a1eec708364brwxcsr9b8e0dd3bb55sbs545FR234a6759bb8e0d3143607083614fre5a6759bb8c73607bb55a894083643'=>'interestFreezes/admin',
				'10216bb91ff54a1e360759bb55a6759bb80ec54a1eec360759bb55a6759bb80708364brwxcsrf54a1eeca6759bb8e0d3143607083614360708364bb55a8943'=>'borrowergroup/admin',
				/*Roles*/
				'f3867574c819c87b49597341bb3fa479d0759bb55a6a446c55f7bc5a63fb5dbb36' => 'transfers/admin',
				'f3867574c819c87b49597341b83643bb55a6759bb8e0216bb91ff54d314b3fa479d0759qu643bbb8e0d314368bb5ats3bb8e0d314c5a63fb5dbb36' => 'savingpostings/admin',
				'f3867574c819c87b49597341b83643bb55a6759bb8e0d314b3fa479d0759bb55a6a446c55f7b83643bb55a6759bb8e0d314c5a63fb5dbb36' => 'withdrawals/admin',
				'10216bb91f54abb55a891eec708364rwqu643bb5atscsr9b8e0dd3bb55a6759bb8e0d31436070836143607083643'=>'roles/admin',
				'10216bb91f54abb55a891eec708364rwqu643bb5ats368e0d3ec76759b8e9b8e0dd3bb55a6759bb8e368e0d3ec76753607083643'=>'assets/admin',
				'10216bb91f54abbb8e0d314368bb55a891ebb8e0d314368bec708364rwqu643bbb8e0d314368bb5ats368e0d3ec76759b8e9b8e0dd3bb55a6759bb8e368e0d3ec76753607083643'=>'assets/create',
				'10216bb91f54abb55a891eec708364rwqu643bb5ats368e0d3ec76759b8e0d3csr9b8e0dd3bb55a6759bb8e368e0d3ec76759b8e0d30d31436070836143607083643'=>'assets/assetsReport',
				'10216bb91f54abb55a891eec708364rwqu643bb5atscsr9b8e0dd3bb5759bb8c76759dailyr76755a6759bb8e0d31436070836143607083643'=>'profiles/admin',
				'10216bb91ff54a1e360759bb55a6759bb805a675954a1eec360759bb55a6759bb4a1eeca6759bb8e0d3143607083614360708364bb55a8943'=>'profiles/membersReport',
				'10216bb91ff54a1e360759bb55a6759bb805a675954a1eb5759bb8c767ec360759bb55a6759bb4a1eeca67b5759bb8c76759bb8e0d3143607083614360708364bb55a8943'=>'collateral/collateralReport',
				'10216bb91f54abb55a891eec708364rwqu643bb5atscsr9b808364brwxcsr9b8ee0dd3bb5759bb8c76759add08364brwxcsr9b8e6755a6759bb8e0d31436070836143607083643'=>'profiles/addProfile',
				/*Others*/
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
			'showScriptName'=>false,
		),
		
		/*Database Configs*/ 
		'db'=>array(
			'class' => 'CDbConnection',
			'connectionString' => 'mysql:host=127.0.0.1;dbname=davies_repo2',
			'emulatePrepare' => true,
			'username' => 'tcl',
			'password' => 'BOSS2023',
			'charset'  => 'utf8',
		),

		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>YII_DEBUG ? null : 'site/error',
		),

		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
			),
		),
		'ePdf2' => array(
			'class'        => 'ext.yii-pdf.EYiiPdf',
			'params'       => array(
				'mpdf'     => array(
					'librarySourcePath' => 'application.vendors.mpdf.*',
					'constants'         => array(
						'_MPDF_TEMP_PATH' => Yii::getPathOfAlias('application.runtime'),
					),
					'class'=>'mpdf', // the literal class filename to be loaded from the vendors folder
					'defaultParams'     => array( // More info: http://mpdf1.com/manual/index.php?tid=184
						'mode'              => '', //  This parameter specifies the mode of the new doc.
						'format'            => 'A4', // format A4, A5, ...
						'default_font_size' => 12, // Sets the default document font size in points (pt)
						'default_font'      => 'Times', // Sets the default font-family for the new doc.
						'mgl'               => 5, // margin_left.Sets the margins for the new document.
						'mgr'               => 5, // margin_right
						'mgt'               => 6, // margin_top
						'mgb'               => 6, // margin_bottom
						'mgh'               => 9, // margin_header
						'mgf'               => 9, // margin_footer
						'orientation'       => 'L',// landscape or portrait orientation
					),
				),
			),
		),
		'mailer' => array(
            'class'       => 'application.extensions.mailer.EMailer',
            'pathViews'   => 'application.views.email',
            'pathLayouts' => 'application.views.email.layouts'
     ),

	),
	
	'params'=>array(
		'PROFILE-UPDATE-PROPAGATE-TABLES' => array(
			'savingaccounts','loanaccounts','loanrepayments','loancomments','guarantors','fixed_payments','out_payments'
		),
		'PROFILE_CONFIG_TYPES' => array(
			'LOAN_LIMIT','LOAN_INTEREST_RATE','SAVINGS_INTEREST_RATE','EMAIL_ALERTS','SMS_ALERTS','FIXED_PAYMENT_LISTED',
			'COMMENTS_DASHBOARD_LISTED','PAYROLL_LISTED','PAYROLL_AUTO_PROCESS','SUPERVISORIAL_ROLE','SALES_TARGET','COLLECTIONS_TARGET',
			'SALARY','BONUS_PERCENT','COMMISSION_PERCENT','PROFIT_PERCENT','INSURANCE_PERCENT','PROCESSING_PERCENT'
		),
		'PROFILE_CONFIG_TYPES_KEYS_VALUES' => array(
			'EMAIL_ALERTS'             => 'ACTIVE',
			'SMS_ALERTS'               => 'ACTIVE',
			'FIXED_PAYMENT_LISTED'     => 'DISABLED',
			'COMMENTS_DASHBOARD_LISTED'=> 'DISABLED',
			'PAYROLL_LISTED'           => 'DISABLED',
			'PAYROLL_AUTO_PROCESS'     => 'DISABLED',
			'SUPERVISORIAL_ROLE'       => 'DISABLED',
            'SALARY'                   => 0.00,
//			'LOAN_LIMIT'               => 5000,
//			'LOAN_INTEREST_RATE'       => 15,
//			'SAVINGS_INTEREST_RATE'    => 0.0,
//            'INSURANCE_PERCENT'       => 0.00,
//            'PROCESSING_PERCENT'      => 0.00,
		),
		'DEFAULTRECORDSPERPAGE'     => 30,
		'DEFAULTREPAYMENTPERIOD'    => 1,
		'DEFAULTREPAYMENTCYCLE'     => '2',
		'DEFAULTREPAYMENTSTARTDATE' => date("Y-m-d",strtotime(date('Y-m-d').'+30 days')),
		'DEFAULTMAXLOANAMOUNT'      => 10000,
		'DEFAULTLOANSINTEREST'      => 20,
		'DEFAULTSAVINGSINTEREST'    => 0.5,
		'DEFAULTSALARYBAND'         => 0.00,
		'DEFAULTREPAYMENTPHONE'     => 'OTHER',
		/***********************

			SYSTEM MAILER 

		***************************/
		'MAILER_HOST'              => 'smtp.zoho.com',
		'MAILER_AUTH_USER'         => 'admin@tclfinance.co.ke',
		'MAILER_AUTH_PWD'          => 'bYep9A0t6Vfx',
		'MAILER_SMTP_PROTOCOL'     => 'tls',
		'MAILER_PORT_NUMBER'       => 587,
		'MAILER_AUTH_SENDER_EMAIL' => 'email@admin.co.ke',
		'MAILER_AUTH_SENDER_NAME'  => 'TCL',
		//'MAILER_AUTH_RECEIVER_BCC' => 'makamupmkelvin@gmail.com',

		/***********************

			BUSINESS TO CONSUMER

		***************************/
		'X-CONSUMERSOURCE-KEY'=>'pzS4Jxv89arphaE8lPqEAcvnGAADWhCb',
		'X-CONSUMERSOURCE-SECRET'=>'39931ONkiGsm3dmw',
		'X-ACCOUNTBALANCE-URL'=>'https://api.safaricom.co.ke/mpesa/accountbalance/v1/query',
		'X-TRANSACTIONSTATUS-URL'=>'https://api.safaricom.co.ke/mpesa/transactionstatus/v1/query',
		'X-APIAUTHTOKEN-URL'=>'https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials',
		'X-BUSINESSCONSUMER-URL'=>'https://api.safaricom.co.ke/mpesa/b2c/v1/paymentrequest',
		'X-CONSUMERSECURITY-KEY'=>'D90VxmwyuyJ601OYg4KXKtoBWhm0IsM5zDSB/KO/nt4/YLzgkYgugrmJTeRJ/TF+5hG/inw4RPANiDGSgQEhloOfBO24tlN2RmESBqnSq/R15XN03e2sTSUMdb/wrfGYF361eBRjsx1HY7Avg5THO/PNcaHMBYKdKpfPuttIpXQpvYarYFazGcfd7CiEz05u+gQ1ZXum/pUhsgF0hur3Lc6qSptOrIu84sQtn6VRQXPGr7pxowlrF4/OdXVQybE6Z7G4+W00sBLye4gHMGYTDLGW/P5fBd+9XpQx4CikpfYV817rqNiKSZFjlJkCbA9cGgnSaYxvuymAvlsZUM1MDA==',
		'X-QUEUETIMEOUT-URL'=>'https://test2.manager.co.ke/replyQueue',
		'X-CONSUMERAPIRESULTS-URL'=>'https://test2.manager.co.ke/sacco_scripts/balance_result.php',
		'X-BUSINESSCONSUMER-SHORTCODE'=>'3028831',
		'X-BUSINESSCONSUMER-INITIATOR-NAME'=>'API User 3028831',
		'X-CUSTOMER-STKPUSH-SHORTCODE'=>'4075143',
		'X-CUSTOMER-STKPUSH-URL'=>'https://api.safaricom.co.ke/mpesa/stkpush/v1/processrequest',
		'X-CUSTOMER-STKPUSH-QUERY-URL'=>'https://api.safaricom.co.ke/mpesa/stkpushquery/v1/query',
		'X-CUSTOMER-STKPUSH-CALLBACK'=>'https://test2.manager.co.ke/sacco_scripts/commitPayment.php',
		'X-CUSTOMER-STKPUSH-PASSKEY'=>'c9696991aea3a9776c276c8e8a4ad40c0a2e3427c2f64a811ced319ca07781dd',
		'X-CUSTOMER-STKPUSH-ENCODED-SECRET-KEY'=>base64_encode('BhzTERDBffApjKWXiMKCvgdNqHBDI1bu:mn6B64XU271PYJ6X'),
		/******************

			AFRICASTALKING

		***********************/
		'AfricaStalking_Username'=>'conrade',
		'AfricaStalking_Key'=>'cb2f0cc6ccb71631c905de2301774e5fea449888d957c3fc656c84fb565251d0',
		'AfricaStalking_From'=>'Messenja',
		/******************

			SYS ADMIN

		***********************/
		'adminEmail'=>'makamupmkelvin@gmail.com',
		/******************

			DOCS MGMT

		***********************/
		'website'=>'https://www.manager.ke',
		'homeDocs'=>'https://test2.manager.co.ke/docs',
		//'homeDocs'=>'http://localhost/tcl/docs',
		//'loanDocs'=>"/opt/lampp/htdocs/tcl/docs/loans/files",
		'loanDocs'=>"/var/www/html/test2.manager.co.ke/public_html/docs/loans/files",
		'expenseDocs'=>"/var/www/html/test2.manager.co.ke/public_html/docs/expenses",

		// PASSWORD ENCRIPTION 

	),
);
