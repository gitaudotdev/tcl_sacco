<?php
class Mailer{

	public static function startBody(){
		return 
            '<div align="center" style="background-color:#fff;background-image:none;background-repeat:repeat;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;font-family:Helvetica,Arial,sans-serif;font-size:14px;line-height:20px">
            <table cellspacing="0" cellpadding="0" border="0" width="600" style="border-collapse:separate;width:600px;margin-bottom:3%;"> 
                <tbody><tr> 
                    <td style="font-family:Helvetica,Arial,sans-serif;font-size:14px;line-height:20px;padding-bottom:0;padding-right:0;color:#555;overflow:hidden;padding-top:10px;padding-left:10px"><a href="http://www.tclfinance.co.ke" style="color:#008ad7!important;text-decoration:none;font-weight:bold" target="_blank">Treasure Capital Systems</a><hr></td> 
                    <td style="font-family:Helvetica,Arial,sans-serif;line-height:20px;padding-bottom:0;padding-left:0;overflow:hidden;padding-top:30px;padding-right:10px;color:#999999;font-size:12px;text-align:right"></td> 
                </tr> 

            </tbody>
        </table>';
	}

	public static function endBody($name){
		return 
        '<table cellspacing="0" cellpadding="0" border="0" width="600" style="border-collapse:separate;width:600px"> 
            <tbody><tr> 
                <td align="center" style="font-family:Helvetica,Arial,sans-serif;font-size:14px;line-height:20px;padding-right:0;padding-left:0;color:#555;overflow:hidden;padding-top:15px;padding-bottom:60px"> 
                    <hr> 
                    <p style="font-family:Helvetica,Arial,sans-serif; padding-top:0; padding-bottom:0; padding-right:0; padding-left:0; overflow:hidden; font-size:11px; line-height:15px; margin-top:0; margin-bottom:8px; margin-right:0; margin-left:0; color:#999999">You received this message because this address has been registered in <a style = "color:#999999" href="www.tclfinance.co.ke">Treasure Capital</a></p>
                    <p style="font-family:Helvetica,Arial,sans-serif;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;overflow:hidden;font-size:11px;line-height:15px;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;color:#999999">&copy; '.date('Y').' Treasure Capital Systems. All Rights Reserved.</p> 
                 </td> 
            </tr> 
        </tbody>
        </table>
        </div>';
	}

	public static function Body($subject, $message, $user_full_name){
		return '<table cellspacing="0" cellpadding="0" border="0" width="600" style="border-collapse:separate;width:600px"> 
                <tbody>
                <tr> 
                    <td align="left" style="font-family:Helvetica,Arial,sans-serif;font-size:14px;line-height:20px;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;color:#555;overflow:hidden"> 
                        <table width="600" cellspacing="0" cellpadding="0" border="0" style="border-collapse:separate;width:600px;background-color:#ffffff"> 
                            <tbody><tr> 
                                <td style="font-family:Helvetica,Arial,sans-serif;font-size:14px;line-height:20px;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;color:#555;overflow:hidden"> 
                                    <div style="margin-right:30px;margin-left:30px;padding-bottom:15px"> 
                                        <p style="font-family:Helvetica,Arial,sans-serif;font-size:14px;font-weight:bold;line-height:20px;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;color:#555;overflow:hidden;margin-top:0;margin-bottom:15px;margin-right:0;margin-left:0">Hello '.$user_full_name.', </p>
                                        <p style="font-family:Helvetica,Arial,sans-serif;font-size:14px;line-height:20px;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;color:#555;overflow:hidden;margin-top:0;margin-bottom:15px;margin-right:0;margin-left:0">'.$message.'</p>
                                        <p style="font-family:Helvetica,Arial,sans-serif;font-size:14px;line-height:20px;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;color:#555;overflow:hidden;margin-top:0;margin-bottom:15px;margin-right:0;margin-left:0">Kind Regards, <br> Treasure Capital Systems</p>
                                    </div>
                                </td> 
                            </tr> 
                        </tbody></table> 
                    </td> 
                </tr> 
            </tbody></table>';
	}
	
	public static function Build($name,$subject,$message, $user_full_name){
		$content  = Mailer::startBody();
		$content .= Mailer::Body($subject,$message,$user_full_name);
		$content .= Mailer::endBody($name);
		return $content;
	}
}
?>