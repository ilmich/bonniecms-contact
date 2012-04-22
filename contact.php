<?php
		require_once 'includes/master.inc.php';
		
		Lang::loadMessages('contact');
		$req = Cms::getHttpRequest();		
		$resp = null;		
		
		if ($req->isGet()) {
			
			$resp = new HttpResponse('text/html');	//new empty response
			
			//get cached response
			$body = Cms::getCachedObject($req->getRequestUri());			 			
			if (is_null($body)) { //if no cached response avaible
				//start logic
				$resp = new HttpResponse();
				
				$template = getTemplateName();				
				//load main template
				$tpl = loadTemplate('index.php',$template);
				$tpl->showForm = true;
				if (is_null($tpl)) {		
					die ('Unable to load template for rendering');		
				}
				//load and render the component template
				$tpl->mainBody = $tpl->renderFile(findTemplate('contact.php',$template));		
				
				$resp->setBody($tpl->render());
				Cms::putObjectInCache($req->getRequestUri(), $resp->getBody());
			} else {
				$resp->setBody($body);
			}
		}
		elseif ($req->isPost()) {
			$resp = new HttpResponse();
			$err = Error::getError();
			
			$template = getTemplateName();				
			//load main template
			$tpl = loadTemplate('index.php',$template);
			$tpl->showForm = true;
			if (is_null($tpl)) {		
				die ('Unable to load template for rendering');		
			}
			//validate input
			$email = trim($req->getParam('email'));
			if (!valid_email($email)) {
				$err->add('email','Insert a valid mail');
			} else {
				$tpl->email = $email;
			}
			$subject = trim($req->getParam('subject'));
			if ($subject === '') {
				$err->add('subject','Insert a non empty subject');
			} else {
				$tpl->subject = $subject;
			}
			$text = trim($req->getParam('message'));
			if ($text === '') {
				$err->add('text','Insert a non empty message');
			} else {
				$tpl->text = $text;
			}
			//get session captcha
			$captcha = Cms::getFromSession('BONNIECMS_CAPTCHA');
			//get request captcha
			$rcaptcha = trim($req->getParam('captcha'));			
			if ($captcha !== $rcaptcha) {
				$err->add('captcha','The text you have entered is wrong');
			}
			
			if ($err->ok()) {
				$tpl->showForm = false; //disable form
				$headers = 'From: '.$email . PHP_EOL .
						'X-Mailer: PHP-' . phpversion() . PHP_EOL;
				if (mail('ardutu@gmail.com', $subject, $text, $headers)) {
         			$tpl->formOk = false; 
                }else {
                	$tpl->formOk = false;
                }                
			}	
					
			$tpl->errors = $err->alert();
			//load and render the component template
			$tpl->mainBody = $tpl->renderFile(findTemplate('contact.php',$template));
				
			$resp->setBody($tpl->render()); 
		}else {
			//create new response
			$resp = new HttpResponse();						
			$resp->setStatus(405)
				 ->setBody($resp->getStatusCodeMessage(405))
				 ->send();		
		}
		
		Cms::sendHttpResponse($resp);