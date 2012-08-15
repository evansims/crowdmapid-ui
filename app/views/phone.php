
						<h3>Link Your Phone</h3>
						<p>Linking your SMS-capable phone to your CrowdmapID account allows you to sign in using your phone number instead of your email address, and provides a convenient means of resetting forgotten passwords.</p>

						<p>Upon changing your number we will send an SMS message containing your confirmation code.</p>

						<p>Be sure to include your country code (i.e. +1, followed by your area code, for the United States.)</p>

						<?php if(isset($set_action_message) && strlen($set_action_message)): ?>
						<p class="info_message"><?php echo($set_action_message); ?></p>
						<?php endif; ?>

						<?php if(isset($set_action_error) && strlen($set_action_error)): ?>
						<p class="error_message"><?php echo($set_action_error); ?></p>
						<?php endif; ?>

						<form method="post" action="<?php echo($site['url']); ?>/phone" id="update_phone">
							<input type="hidden" id="activity" name="activity" value="update_phone" />

							<div class="form-row">
								<label for="number">Phone Number:</label>
								<div class="text"><input type="text" id="number" name="number" placeholder="Please provide your phone number." value="<?php echo($user->phone->number); ?>" onclick="this.focus(); this.select(); return false;" /></div>
							</div>

							<?php if($user->phone->number && !$user->phone->confirmed): ?>
							<div class="form-row">
								<label for="code">Confirmation:</label>
								<div class="text"><input type="text" id="code" name="code" placeholder="Enter the code you received in your SMS." value="" onclick="this.focus(); this.select(); return false;" /></div>
							</div>
							<?php endif; ?>

							<div class="form-row">
								<label class="filler">&nbsp;</label>
								<div class="buttons">
									<input type="submit" class="submit" value="<?php echo($button_text); ?>" />
								</div>
							</div>

						</form>

						<?php if(isset($add_action_error) && strlen($add_action_error)): ?>
						<script type="text/javascript">
						document.getElementById('email').focus();
						</script>
						<?php endif; ?>
