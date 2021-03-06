<?php 
//(服务器文件）微信服务器调用，是被动的调用，无法查看输出的内容
	function checkSignature(){
		//signature是微信用下面相同的方法加密而成的
		$signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        $echostr = $_GET["echostr"];
        //输入的token
        $token = "ningbo";
        $tmpArr = array($token, $timestamp, $nonce);
		//排序
		sort($tmpArr, SORT_STRING);
		//转字符串
		$tmpStr = implode( $tmpArr );
		//sha1加密：密文传输，防止中途抓包
		$tmpStr = sha1( $tmpStr );
		if( $tmpStr == $signature ){
			echo $echostr;
		}
	}//验证签名:一次通过后就不再使用了
	//checkSignature();
	
	//服务器处理微信转发过来的数据
	//微信使用post形式传过来数据，是微信调用我们。
	// print_r($_POST);:无法看到数据信息，被输出到微信调用的地方，
	
	
	use sinacloud\sae\Storage as Storage;

	//获取到微信服务器发送过来的数据
	$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

	//初始化对象
	$s = new Storage();
	//往buket中存储字符
	$s->putObject($postStr, "weixin", "debug.txt", array(), array('Content-Type' => 'text/plain;charset=utf-8'));

	if(!empty($postStr)){
		// 禁止加载外部实体
		libxml_disable_entity_loader(true);
		// 把xml字符串解析为对象类型
        $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
        //获取属性
        $MsgType = $postObj->MsgType;
        switch ($MsgType) {
        	case 'text'://文本
        		# 文本处理
        		handleText($postObj);
        		break;
        	case 'image'://图片
        		# 图片处理
        		handleImage($postObj);
        		break;
        	case 'voice'://语音
        		# 语音处理
        		handleVoice($postObj);
        		break;
        	default:
        		echo ("success");
        		break;
        }
	}//if判断结束

	/*
	<xml>文本消息接受
	 <ToUserName><![CDATA[toUser]]></ToUserName>
	 <FromUserName><![CDATA[fromUser]]></FromUserName>
	 <CreateTime>1348831860</CreateTime>
	 <MsgType><![CDATA[text]]></MsgType>
	 <Content><![CDATA[你好]></Content>
	 <MsgId>1234567890123456</MsgId>
	 </xml>	
	 */ 
	/*文本回复
	<xml>
		<ToUserName><![CDATA[toUser]]></ToUserName>
		<FromUserName><![CDATA[fromUser]]></FromUserName>
		<CreateTime>12345678</CreateTime>
		<MsgType><![CDATA[text]]></MsgType>
		<Content><![CDATA[你好]]></Content>
	</xml>
	 */ 
	
	//文本处理
	function handleText($postObj){
		//公众号的id
		$ToUserName = $postObj->ToUserName;
		//粉丝id
		$FromUserName = $postObj->FromUserName;
		//粉丝发的内容
		$Content = $postObj->Content;
		//当前时间戳
		$CreateTime = time();
		//回复内容
		$sendText = "收到".$Content;
		$echostr = <<<TTT
		<xml>
			<ToUserName><![CDATA[{$FromUserName}]]></ToUserName>
			<FromUserName><![CDATA[{$ToUserName}]]></FromUserName>
			<CreateTime>{$CreateTime}</CreateTime>
			<MsgType><![CDATA[text]]></MsgType>
			<Content><![CDATA[{$sendText}]]></Content>
		</xml>
TTT;
		//发送回复
		echo $echostr;
	}

	/*图片收到
	<xml>
	 <ToUserName><![CDATA[toUser]]></ToUserName>
	 <FromUserName><![CDATA[fromUser]]></FromUserName>
	 <CreateTime>1348831860</CreateTime>
	 <MsgType><![CDATA[image]]></MsgType>
	 <PicUrl><![CDATA[this is a url]]></PicUrl>
	 <MediaId><![CDATA[media_id]]></MediaId>
	 <MsgId>1234567890123456</MsgId>
	 </xml>
	 */ 
	/*图片发送
	<xml>
		<ToUserName><![CDATA[toUser]]></ToUserName>
		<FromUserName><![CDATA[fromUser]]></FromUserName>
		<CreateTime>12345678</CreateTime>
		<MsgType><![CDATA[image]]></MsgType>
		<Image>
			<MediaId><![CDATA[media_id]]></MediaId>
		</Image>
	</xml>
	 */ 
	
	//处理图片消息
	function handleImage($postObj){
		//公众号的id
		$ToUserName = $postObj->ToUserName;
		//粉丝id
		$FromUserName = $postObj->FromUserName;
		//当前时间戳
		$CreateTime = time();
		//图片链接
		$PicUrl = $postObj->PicUrl;
		//图片
		$MediaId = $postObj->MediaId;

		//回复文本内容
		/*
		$echostr = <<<TTT
		<xml>
			<ToUserName><![CDATA[{$FromUserName}]]></ToUserName>
			<FromUserName><![CDATA[{$ToUserName}]]></FromUserName>
			<CreateTime>{$CreateTime}</CreateTime>
			<MsgType><![CDATA[text]]></MsgType>
			<Content><![CDATA[{$PicUrl}]]></Content>
		</xml>
TTT;
		//发送回复
		echo $echostr;
		*/
	
		//回复图片
		$echostr = <<<TTT
		<xml>
			<ToUserName><![CDATA[{$FromUserName}]]></ToUserName>
			<FromUserName><![CDATA[{$ToUserName}]]></FromUserName>
			<CreateTime>$CreateTime</CreateTime>
			<MsgType><![CDATA[image]]></MsgType>
			<Image>
				<MediaId><![CDATA[{$MediaId}]]></MediaId>
			</Image>
		</xml>
TTT;
		//发送回复
		echo $echostr;
	}



	/*语音接受
	<xml>
		<ToUserName><![CDATA[toUser]]></ToUserName>
		<FromUserName><![CDATA[fromUser]]></FromUserName>
		<CreateTime>1357290913</CreateTime>
		<MsgType><![CDATA[voice]]></MsgType>
		<MediaId><![CDATA[media_id]]></MediaId>
		<Format><![CDATA[Format]]></Format>
		<MsgId>1234567890123456</MsgId>
	</xml>
	 */
	/*语音发送
	<xml>
		<ToUserName><![CDATA[toUser]]></ToUserName>
		<FromUserName><![CDATA[fromUser]]></FromUserName>
		<CreateTime>12345678</CreateTime>
		<MsgType><![CDATA[voice]]></MsgType>
		<Voice>
			<MediaId><![CDATA[media_id]]></MediaId>
		</Voice>
	</xml>
	 */ 
	
	//语音消息
	function handleVoice($postObj){
		//公众号的id
		$ToUserName = $postObj->ToUserName;
		//粉丝id
		$FromUserName = $postObj->FromUserName;
		//当前时间戳
		$CreateTime = time();
		//语音ID
		$MediaId = $postObj->MediaId;

		$echostr = <<<TTT
		<xml>
			<ToUserName><![CDATA[{$FromUserName}]]></ToUserName>
			<FromUserName><![CDATA[{$ToUserName}]]></FromUserName>
			<CreateTime>$CreateTime</CreateTime>
			<MsgType><![CDATA[voice]]></MsgType>
		<Voice>
			<MediaId><![CDATA[{$MediaId}]]></MediaId>
		</Voice>
		</xml>
TTT;
		//发送回复
		echo $echostr;
	}
 ?>