
						<h3>Recover an Account</h3>

						<?php if($recovery_message): ?>
						<p class="info_message"><?php echo($recovery_message); ?></p>
						<?php endif; ?>

						<?php if($recovery_error): ?>
						<p class="error_message"><?php echo($recovery_error); ?></p>
						<?php endif; ?>

						<form id="account_recovery" method="post" action="<?php echo($site['url']); ?>/recovery" autocomplete="off">
							<input type="hidden" id="activity" name="activity" value="reset_mode" />

							<div class="form-row">
								<label for="email">Email Address:</label>
								<div class="text"><input type="text" id="email" name="email" /></div>
							</div>

							<div class="form-row">
								<label for="question">Choose One:</label>
								<div class="dropdown"><select id="mode" name="mode">
									<option selected="selected" value="reset_password">I've lost my password and need it reset.</option>
									<option value="reset_question">I've lost my password and can not access my email.</option>
									<option value="reset_yubikey">I've lost the Yubikey paired to my account.</option>
								</select></div>
							</div>

							<div class="form-row">
								<label class="filler">&nbsp;</label>
								<div class="buttons">
									<input type="submit" class="submit" value="Continue" />
								</div>
							</div>

						</form>
