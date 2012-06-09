
						<p><strong><?php echo($site['title']); ?> supports logging in with multiple email accounts.</strong> Howevever, you will only receive email through the address you identify as your primary account. If you're using <a href="http://en.gravatar.com/" rel="no-follow" target="_blank">Gravatar</a>, your avatar will also be chosen by your primary account setting.</p>

						<h3>Registered Accounts (<?php echo(count($user->emails)); ?>)</h3>

						<?php if(isset($edit_action_message) && strlen($edit_action_message)): ?>
						<p class="info_message"><?php echo($edit_action_message); ?></p>
						<?php endif; ?>

						<?php if(isset($edit_action_error) && strlen($edit_action_error)): ?>
						<p class="error_message"><?php echo($edit_action_error); ?></p>
						<?php endif; ?>

						<form method="post" action="<?php echo($site['url']); ?>/accounts" id="edit_account">
							<input type="hidden" id="activity" name="activity" value="edit" />

							<ul class="preferences-table">
								<li>
									<span class="title">Primary</span>
									<span class="email"><strong><?php echo $user->emails[0]->email; ?></strong></span>
									<span class="info">Registered <?php echo(timeSince($user->emails[0]->registered)); ?> ago.</span>
									<span class="links"></span>
								</li>
								<?php if(count($user->emails) > 1): for($e = 1; $e < count($user->emails); $e++): ?>
								<li data-email="<?php echo $user->emails[$e]->email; ?>">
									<span class="title"></span>
									<span class="email"><?php echo $user->emails[$e]->email; ?></span>
									<span class="info">Registered <?php echo(timeSince($user->emails[$e]->registered)); ?> ago.</span>
									<span class="links">
										<a class="promote" href="#" title="Make this address your primary contact.">Promote</a> &nbsp;
										<a class="remove" href="#" title="Delete this address from your account.">Remove</a></span>
								</li>
								<?php endfor; endif; ?>
							</ul>

						</form>




						<h3>Add an Account</h3>

						<?php if(isset($add_action_message) && strlen($add_action_message)): ?>
						<p class="info_message"><?php echo($add_action_message); ?></p>
						<?php endif; ?>

						<?php if(isset($add_action_error) && strlen($add_action_error)): ?>
						<p class="error_message"><?php echo($add_action_error); ?></p>
						<?php endif; ?>

						<form method="post" action="<?php echo($site['url']); ?>/accounts" id="add_account">
							<input type="hidden" id="activity" name="activity" value="add" />

							<div class="form-row">
								<label for="email">Email Address:</label>
								<div class="text"><input type="text" id="email" name="email" placeholder="Please provide an address." value="<?php echo($add_action_value); ?>" onclick="this.focus(); this.select(); return false;" /></div>
							</div>

							<div class="form-row">
								<label class="filler">&nbsp;</label>
								<div class="buttons">
									<input type="submit" class="submit" value="Add" />
								</div>
							</div>

						</form>

						<?php if(isset($add_action_error) && strlen($add_action_error)): ?>
						<script type="text/javascript">
						document.getElementById('email').focus();
						</script>
						<?php endif; ?>
