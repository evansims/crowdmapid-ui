
						<p><strong><?php echo $site['title']; ?> provides a number of security measures to protect your account.</strong> You should rotate your passwords often to ensure your account remains secure.</p>

						<?php if(!$yubi_paired): ?>

						<h3>Password</h3>
						<p>Your password was last updated <strong><?php echo $passwordChanged; ?>.</strong> We recommend using a strong password generator like <a href="https://agilebits.com/onepassword" rel="no-follow" target="_blank">1Password</a> or <a href="https://lastpass.com/" rel="no-follow" target="_blank">LastPass</a> to secure your account. Use a <strong>unique password</strong>. Never reuse passwords you use elsewhere.</p>

						<p>We support passwords <strong>up to 128 characters</strong> in length. Please use randomized <strong>capitalization, numbers, and symbols</strong>.</p>

						<?php if($password_update_message): ?>
						<p class="info_message"><?php echo($password_update_message); ?></p>
						<?php endif; ?>

						<?php if($password_update_error): ?>
						<p class="error_message"><?php echo($password_update_error); ?></p>
						<?php endif; ?>

						<form method="post" action="<?php echo($site['url']); ?>/security" id="password" autocomplete="off">
							<input type="hidden" id="activity" name="activity" value="password" />

							<div class="form-row">
								<label for="new_password">New Password:</label>
								<div class="text"><input type="password" id="new_password" name="new_password" placeholder="" value="" onclick="this.focus(); this.select(); return false;" /></div>
							</div>

							<div class="form-row">
								<label for="confirm_password">Confirm Password:</label>
								<div class="text"><input type="password" id="confirm_password" name="confirm_password" placeholder="" value="" onclick="this.focus(); this.select(); return false;" /></div>
							</div>

							<div id="password_strength_container" class="form-row" style="display: none">
								<label>&nbsp;</label>
								<div class="text"><p id="password_strength">&nbsp;</p></div>
							</div>

							<div class="form-row">
								<label class="filler">&nbsp;</label>
								<div class="buttons">
									<input type="submit" class="submit" value="Update Password" />
								</div>
							</div>
						</form>

						<?php endif; ?>

						<h3>Yubikey</h3>
						<p><img src="<?php echo($site['url']); ?>/images/yubikey.png" style="float: right; margin: 0 0 1em 1em" /><?php echo($site['title']); ?> supports <strong><a href="http://www.yubico.com/products" rel="no-follow" target="_blank">Yubikey</a> <a href="http://www.yubico.com/technical-description" rel="no-follow" target="_blank">OTP</a></strong> as an optional layer of stronger security. Once enabled, you will no longer be able to sign into with your account password.</p>

						<p>Yubikeys are an excellent means of securing your account from unwanted access, as <strong>they require a unique, physical device in order to log in.</strong></p>

						<p>When prompted to log in on one of our sites insert your Yubikey, click on the password input field, and press the button on the device to sign in. <strong>To pair your device</strong> click the form field below, insert your Yubikey, and press the button on your device.</p>

						<?php if($yubikey_edit_message): ?>
						<p class="info_message"><?php echo($yubikey_edit_message); ?></p>
						<?php endif; ?>

						<?php if($yubikey_edit_error): ?>
						<p class="error_message"><?php echo($yubikey_edit_error); ?></p>
						<?php endif; ?>

						<?php if($yubi_paired): ?>

						<form method="post" action="<?php echo($site['url']); ?>/security#yubikey_remove" id="yubikey_remove" autocomplete="off">
							<input type="hidden" id="activity" name="activity" value="yubikey_remove" />


							<div class="form-row">
								<label for="email">Yubikey:</label>
								<div class="text"><p style="color: green">This account is paired with Yubikey #<?php echo $yubi_paired; ?></p></div>
							</div>

							<div class="form-row">
								<label class="filler">&nbsp;</label>
								<div class="buttons">
									<input type="submit" class="submit" value="Remove Yubikey" />
								</div>
							</div>
						</form>

						<?php else: ?>

						<form method="post" action="<?php echo($site['url']); ?>/security#yubikey_pair" id="yubikey_pair" autocomplete="off">
							<input type="hidden" id="activity" name="activity" value="yubikey_pair" />


							<div class="form-row">
								<label for="otp">Yubikey:</label>
								<div class="text"><input type="password" id="otp" name="otp" placeholder="Insert your Yubikey and press the button on the device." value="" onclick="this.focus(); this.select(); return false;" /></div>
							</div>

							<div class="form-row">
								<label class="filler">&nbsp;</label>
								<div class="buttons">
									<input type="submit" class="submit" value="Pair Yubikey" />
								</div>
							</div>
						</form>

						<?php endif; ?>



						<h3>Secret Question</h3>
						<p>In the event that you lose your password and are unable to access your <a href="<?php echo($site['url']); ?>/accounts">primary email account</a>, we may require you to answer a secret question for us to prove your ownership.</p>

						<p>Please note that for your security <strong>we encrypt all secret answers.</strong> Should we ask you for this answer, we will compare the <a href="http://en.wikipedia.org/wiki/Salt_(cryptography)" rel="no-follow" target="_blank">salted</a> <a href="http://en.wikipedia.org/wiki/SHA-1" rel="no-follow" target="_blank">hashes</a> of your answers to determine authenticity. For this reason, <strong>you should use one word answers</strong> that you will <strong>easily remember</strong>.</p>

						<?php if($question_update_message): ?>
						<p class="info_message"><?php echo($question_update_message); ?></p>
						<?php endif; ?>

						<?php if($question_update_error): ?>
						<p class="error_message"><?php echo($question_update_error); ?></p>
						<?php endif; ?>

						<form method="post" action="<?php echo($site['url']); ?>/security#secret_question" id="secret_question" autocomplete="off">
							<input type="hidden" id="activity" name="activity" value="secret_question" />

							<div class="form-row">
								<label for="question">Question:</label>
								<div class="dropdown"><select id="question" name="question">
									<?php echo($challenge_question); ?>
									<?php foreach($questions as $question) :?><option value="<?php echo($question); ?>"><?php echo($question); ?></option><?php endforeach; ?>
									<option value="" disabled="disabled">Reload the page for other options.</option>
								</select></div>
							</div>

							<div class="form-row">
								<label for="answer">Answer:</label>
								<div class="text"><input type="password" id="answer" name="answer" placeholder="Please provide your answer." value="" onclick="this.focus(); this.select(); return false;" /></div>
							</div>

							<div class="form-row">
								<label class="filler">&nbsp;</label>
								<div class="buttons">
									<input type="submit" class="submit" value="Update Account" />
								</div>
							</div>
						</form>
