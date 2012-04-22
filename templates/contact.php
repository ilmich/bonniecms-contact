<?php if ($showForm) {?>	
	<style>
		form label {
			display: block;
			margin-top: 5px;
		}	
		form textarea {
			resize: none;
		}	
		.text {
			width: 100%;
		}	
		#captcha {
			margin: 5px 0px;
		}	
		#captcha img {
			vertical-align: middle;
			margin-right: 15px;	
		}	
		.error {
			color: #ff0000;		
		}	
		.alert {
			border: 1px solid #ff0000;
			padding: 2px;
			display: block;
		}	
	</style>	
		<form action="contact.php" method="post">
			<label><?php __text('LABEL_EMAIL'); ?>
					<?php if (isset(Error::getError()->errors['email'])) { ?>&nbsp;&nbsp;<span class="error"><?php echo Error::getError()->errors['email'][0]; ?></span> <?php } ?></label>
			<input type="text" class="text" name="email" value="<?php echo isset($email) ? $email : ''; ?>"></input>
			<label><?php __text('LABEL_SUBJECT'); ?>
					<?php if (isset(Error::getError()->errors['subject'])) { ?>&nbsp;&nbsp;<span class="error"><?php echo Error::getError()->errors['subject'][0]; ?></span> <?php } ?></label>
			<input type="text" class="text" name="subject" value="<?php echo isset($subject) ? $email : ''; ?>"></input>
			<label><?php __text('LABEL_MESSAGE'); ?>
					<?php if (isset(Error::getError()->errors['text'])) { ?>&nbsp;&nbsp;<span class="error"><?php echo Error::getError()->errors['text'][0]; ?></span> <?php } ?></label>
			<textarea class="text" name="message" value=""><?php echo isset($text) ? $text : ''; ?></textarea>
			<div id="captcha">
				<?php if (isset(Error::getError()->errors['captcha'])) { ?><span class="error"><?php echo Error::getError()->errors['captcha'][0]; ?></span> <?php } ?>
				<label><?php __text('LABEL_CAPTCHA'); ?></label>
				<img src="captcha.php"></img>
				<input type="text" name="captcha"></input>	
			</div>
			<input type="submit" value="Send">
		</form>
<?php } else if ($formOk) { ?>
		<p><?php __text('LABEL_MAIL_OK'); ?></p>
<?php } else if (!$formOk) { ?>
		<p><?php __text('LABEL_MAIL_KO'); ?></p>
<?php }?>