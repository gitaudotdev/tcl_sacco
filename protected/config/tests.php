<?php
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'NEXSYS',

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
			'connectionString' => 'mysql:host=127.0.0.1;dbname=sacco',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => 'Treasure@!#2023',
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
                'PROFILE_DOCUMENT_SIZE_LIMIT_MBS' => 25,
		'PROFILE_DOCUMENT_EXTENSIONS' => array('pdf','jpg','jpeg','docx','doc','png'),
		'PROFILE_DOCUMENT_CATEGORIES' => array(
			'IDENTIFICATION_DOCUMENT','PASSPORT_PHOTO','REGISTRATION_FORM','BANK_STATEMENT','KRA_PIN','NHIF_DOCUMENT',
			'NSSF_DOCUMENT','EDUCATION_CERTIFICATE','EMPLOYMENT_DOCUMENT','PAYSLIP','COVER_LETTER','RESUME',
			'BUSINESS_PERMIT','OTHER_DOCUMENT','CRB_DOCUMENT','GOOD_CONDUCT_DOCUMENT','HELB_CERITIFICATE'
		),
		'PROFILE-UPDATE-PROPAGATE-TABLES' => array(
			'savingaccounts','loanaccounts','loanrepayments','loancomments','guarantors','fixed_payments','out_payments'
		),
		'PROFILE_CONFIG_TYPES' => array(
			'LOAN_LIMIT','LOAN_INTEREST_RATE','SAVINGS_INTEREST_RATE','EMAIL_ALERTS','SMS_ALERTS','FIXED_PAYMENT_LISTED',
			'COMMENTS_DASHBOARD_LISTED','PAYROLL_LISTED','PAYROLL_AUTO_PROCESS','SUPERVISORIAL_ROLE','SALES_TARGET','COLLECTIONS_TARGET',
			'SALARY','BONUS_PERCENT','COMMISSION_PERCENT','PROFIT_PERCENT'
		),
		'PROFILE_CONFIG_TYPES_KEYS_VALUES' => array(
			'EMAIL_ALERTS'             => 'ACTIVE',
			'SMS_ALERTS'               => 'ACTIVE',
			'FIXED_PAYMENT_LISTED'     => 'DISABLED',
			'COMMENTS_DASHBOARD_LISTED'=> 'DISABLED',
			'PAYROLL_LISTED'           => 'DISABLED',
			'PAYROLL_AUTO_PROCESS'     => 'DISABLED',
			'SUPERVISORIAL_ROLE'       => 'DISABLED',
			'LOAN_LIMIT'               => 10000,
			'LOAN_INTEREST_RATE'       => 20,
			'SAVINGS_INTEREST_RATE'    => 0.5,
			'SALARY'                   => 0.00
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
		'MAILER_HOST'=>'smtp.zoho.com',
		'MAILER_AUTH_USER'=>'mail@admin.co.ke',
		'MAILER_AUTH_PWD'=>'cAQ8brNw6Ds', 
		'MAILER_SMTP_PROTOCOL'=>'tls',
		'MAILER_PORT_NUMBER'=>587,
		'MAILER_AUTH_SENDER_EMAIL'=>'mail@admin.co.ke',
		'MAILER_AUTH_SENDER_NAME'=>'Nex',
		//'MAILER_AUTH_RECEIVER_BCC' => 'conrade@tclfinance.co.ke',
		/***********************

			STK Push - C2B

		***************************/
		'X-CONSUMERSOURCE-KEY'=>'pbc8s4HmhW8p0shovd8alBXGOJ0xWhp',
		'X-CONSUMERSOURCE-SECRET'=>'rtoWe(@b6igxYOY',
		'X-ACCOUNTBALANCE-URL'=>'https://api.safaricom.co.ke/mpesa/accountbalance/v1/query',
		'X-TRANSACTIONSTATUS-URL'=>'https://api.safaricom.co.ke/mpesa/transactionstatus/v1/query',
		'X-APIAUTHTOKEN-URL'=>'https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials',
		'X-CONSUMERSECURITY-KEY'=>'NxBWVs2XaMvH4HHkjH8i64vhKLz9kUQ3A1NzqDdLsrhzMBoKjDYJZlXitUFT3vasGeEpJ0hMLdOTEVr3oD8vEUOt5T39S7bFAjcUaCo334Uyh9cF3EKYjwaLDpWW8MTNyotpMa+CGXL9fWA+4ZG/XNgS3thHTjvculbU08m5rQoHZxVPLAgwZgxA77u3SCDBzNFGMkdyp+E5LKKrX7xpaFOHZwRBQNM9Hi1WMKh7UGB2BvIR3qT3aQ/3tgfdBeMTcppe87UXUKEeBfY+KjXYUU7RGRuRP3j9CX0jao6xVfFQ/APSAkbDHZU/A==',
		'X-BUSINESSCONSUMER-INITIATOR-NAME'=>'API USER ***',
		'X-CUSTOMER-STKPUSH-SHORTCODE'=>'***',
		'X-CUSTOMER-STKPUSH-URL'=>'https://api.safaricom.co.ke/mpesa/stkpush/v1/processrequest',
		'X-CUSTOMER-STKPUSH-QUERY-URL'=>'https://api.safaricom.co.ke/mpesa/stkpushquery/v1/query',
		'X-CUSTOMER-STKPUSH-CALLBACK'=>'https://loans.manager.co.ke/sacco_scripts/commitPayment.php',
		'X-CUSTOMER-STKPUSH-PASSKEY'=>'c84a6380cde2863kju57da2168a1f3cd2ac2d7e4e34aa36a1e2fb6a21751',
		'X-CUSTOMER-STKPUSH-ENCODED-SECRET-KEY'=>base64_encode('pbc8s4HmhW8xTlhovop[o8alBXWhp:rtoWeFSb6OigxYOY'),
		/******************

			B2C

		***********************/
		'X-B2C-URL'=>'https://api.safaricom.co.ke/mpesa/b2c/v1/paymentrequest',
		'X-B2C-CONSUMER-KEY'=>'pzS4Jx89a55haE8lPGAADWhCb',
		'X-B2C-CONSUMER-SECRET'=>'39741ONkiGsm3dmw',
		'X-B2C-INTIATOR-NAME'=>'Kelvin',
		'X-B2C-SECURITY-CREDENTIAL'=>'gjgo0irtir7867874524iobCDWZN5r88/D3OqHtBO/u8BXqXqoK0A/puXDYdp7mwqHmlcwTp5xb5aoK/fpFEY8uabzkvOuyACVotFLXvHZ6t6WPEHPea0H2O95VNWDWlzSQnGMWiVKGspMGJTlvgnpAKqLdixrpgQgwdZwHwBfliPzqcpvS95lkayw7oX9bAsPA8Ruk3Td3pNqD4xNiGYRYLvCKQr80T63BaCC517bCRT1+cMOGhloHBitLx+RSr9Dw9YZ6lyT2A0DtM97CvWCFFhceRzgsiH8X06w==',
		'X-B2C-SHORT-CODE'=>'***',
		'X-B2C-QUEUE-TIMEOUT-URL'=>'https://31f2-197-156-190-139.ngrok.io',
		'X-B2C-RESULT-URL'=>'https://31f2-197-156-190-139.ngrok.io',
		'X-B2C-BALANCE-RESULT-URL'=>'https://loans.manager.co.ke/sacco_scripts/balance_result.php',
		/******************

			AFRICASTALKING

		***********************/
		'AfricaStalking_Username'=>'**',
		'AfricaStalking_Key'=>'***',
		'AfricaStalking_From'=>'**',
		/******************

			SYS ADMIN

		***********************/
		'adminEmail'=>'makamukevin@gmail.com',
		/******************

			DOCS MGMT

		***********************/
		'website'=>'loans.manager.co.ke',
		'homeDocs'=>'https://loans.manager.co.ke/docs',
		//'homeDocs'=>'http://localhost/tcl/docs',
		//'loanDocs'=>"/opt/lampp/htdocs/tcl/docs/loans/files",
		'loanDocs'=>"/var/www/html/docs/loans/files",
		'csvDocs'=>"/var/www/html/docs/csvs/loans/",
		'profileDocs'=>"/var/www/html/docs/members",
		'expenseDocs'=>"/var/www/html/docs/expenses",
	),
);


