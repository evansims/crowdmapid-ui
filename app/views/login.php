
						<p><?php echo(CFG_SITE_LONG_DESCRIPTION); ?></p>

						<h3>Login using third-party services</h3>

						<p>
							<a href="#" id="facebookLogin"><span class="icon-facebook"></span> Facebook</a>
							<!-- <a href="#" id="twitterLogin"><span class="icon-twitter"></span> Twitter</a> -->
						</p>

						<p class="small">We'll never post to your stream without your permission.</p>


						<h3>Login using traditional credentials</h3>

						<?php if(Sessions::storageGet('login_message', true)): ?>
						<p class="info_message"><?php echo(Sessions::storageGet('login_message', true)); ?></p>
						<?php endif; ?>

						<?php if(isset($page['errors']['login_error'])): ?>
						<p class="error_message"><strong>Whoops!</strong> <?php echo($page['errors']['login_error']); ?></p>
						<?php endif; ?>

						<form id="account_login" method="post" action="<?php echo($site['url']); ?>/login" autocomplete="off">

							<div class="form-row">
								<label for="email">Email Address:</label>
								<div class="text"><input type="text" id="email" name="email" /></div>
							</div>

							<div class="form-row">
								<label for="password">Password:</label>
								<div class="text"><input type="password" id="password" name="password" /></div>
							</div>

							<div class="form-row">
								<label class="filler">&nbsp;</label>
								<div class="buttons">
									<input type="submit" class="submit" value="Log In" />
									<input type="button" class="button" value="Recover Password" onclick="window.location = '<?php echo($site['url']); ?>/recovery'; return false" />
								</div>
							</div>

						</form>
