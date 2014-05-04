<?php
class FlagUserJob extends AbstractUserJob
{
	public function execute()
	{
		$user = $this->user;
		$key = TextHelper::reprUser($user);

		$flagged = SessionHelper::get('flagged', []);
		if (in_array($key, $flagged))
			throw new SimpleException('You already flagged this user');
		$flagged []= $key;
		SessionHelper::set('flagged', $flagged);

		LogHelper::log('{user} flagged {subject} for moderator attention', [
			'user' => TextHelper::reprUser(Auth::getCurrentUser()),
			'subject' => TextHelper::reprUser($user)]);

		return $user;
	}

	public function requiresPrivilege()
	{
		return
		[
			Privilege::FlagUser,
			Access::getIdentity($this->user)
		];
	}

	public function requiresAuthentication()
	{
		return false;
	}

	public function requiresConfirmedEmail()
	{
		return false;
	}
}
