<?php
namespace app\Controller;

require_once(CORE.'data/ClanFactory.php'); ///TODO autoload this

use Exception;
use \Player as Player;
use \ClanFactory as ClanFactory;

class ClanController { //extends Controller
	const CLAN_CREATOR_MIN_LEVEL = 20;
	const ALIVE                  = false;
	const PRIV                   = false;

	public function view() {
		$clanID = (int)in('clan_id', null);

		if (!$clanID) {
			$player = new Player(self_char_id());

			if ($player->id()) {
				$clan = ClanFactory::clanOfMember($player);
			}
		} else {
			$clan = ClanFactory::find($clanID);
		}

		if ($clan) {
			$parts = [
				'title'     => $clan->getName(),
				'clan'      => $clan,
				'pageParts' => [
					'info',
					'member-list',
				],
			];
		} else {
			$parts = [
				'title'     => 'Clan Not Found',
				'clans'     => clans_ranked(),
				'error'     => 'The clan you requested does not exist. Pick one from the list below',
				'pageParts' => [
					'list',
				],
			];
		}

		$player = new Player(self_char_id());

		if ($player->id()) {
			$myClan = ClanFactory::clanOfMember($player);

			if ($myClan) {
				if ($clan) {
					if ($this->playerIsLeader($player, $clan)) {
						array_unshift($parts['pageParts'], 'manage');
					} else if ($myClan->id() === $clan->id()) {
						array_unshift($parts['pageParts'], 'non-leader-panel');
					} else {
						array_unshift($parts['pageParts'], 'reminder-member');
					}
				} else {
					array_unshift($parts['pageParts'], 'reminder-member');
				}
			} else {
				array_unshift($parts['pageParts'], 'join');
				array_unshift($parts['pageParts'], 'reminder-no-clan');
			}
		}

		return $this->render($parts);
	}

	public function invite() {
		$player = new Player(self_char_id());
		$clan   = ClanFactory::clanOfMember($player);

		if (!$this->playerIsLeader($player, $clan)) {
			throw new Exception('You must be a clan leader to invite new members');
		}

		$person_to_invite = new Player(in('person_invited', ''));

		$parts = [
			'clan'      => $clan,
			'title'     => 'Invite players to your clan',
			'pageParts' => [
				'edit',
				'info',
				'member-list',
			],
		];

		if ($person_to_invite) {
			$error = $clan->invite($person_to_invite, $player);

			if ($error === null) {
				$parts['action_message'] = 'Invited '.$person_to_invite->name().' to your clan.';
			} else {
				$parts['error'] = $error;
			}
		} else {
			$parts['error'] = 'Sorry, unable to find a ninja to invite by that name.';
		}

		return $this->render($parts);
	}

	public function leave() {
		$player = new Player(self_char_id());
		$clan   = ClanFactory::clanOfMember($player);

		if ($this->playerIsLeader($player, $clan)) {
			throw new Exception('You are the only leader of your clan. You must disband your clan if you wish to leave.');
		}

		$clan->leave($player);

		return $this->render([
			'action_message' => 'You have left your clan.',
			'title'          => 'You have left your clan.',
			'clans'          => clans_ranked(),
			'pageParts'      => [
				'reminder-no-clan',
				'list',
			]
		]);
	}

	public function create() {
		$player = new Player(self_char_id());

		if ($player->level() >= ClanController::CLAN_CREATOR_MIN_LEVEL) {
			$default_clan_name = 'Clan '.$player->name();

			while (!is_unique_clan_name($default_clan_name)) {
				$default_clan_name = $default_clan_name.rand(1,999);
			}

			$clan = createClan($player->id(), $default_clan_name);

			$parts = [
				'action_message' => 'Your clan was created with the default name: '.$clan->getName().'. Change it below.',
				'title'          => 'Clan '.$clan->getName(),
				'clan'           => $clan,
				'pageParts'      => [
					'edit',
				],
			];
		} else {
			$parts = [
				'error'     => 'You do not have enough renown to create a clan. You must be at least level '.ClanController::CLAN_CREATOR_MIN_LEVEL.'.',
				'title'     => 'You cannot create a clan yet',
				'clans'     => clans_ranked(),
				'pageParts' => [
					'list',
				],
			];
		}

		return $this->render($parts);
	}

	public function join() {
		$clanID = (int) in('clan_id', null);
		$clan   = ClanFactory::find($clanID);

		send_clan_join_request(self_char_id(), $clanID);

		$leader = $clan->getLeaderInfo();

		return $this->render([
			'action_message' => "Your request to join {$clan->getName()} has been sent to $leader[uname]",
			'title'          => 'Viewing a clan',
			'clan'           => $clan,
			'pageParts'      => [
				'reminder-no-clan',
				'info',
				'member-list',
			],
		]);
	}

	public function disband() {
		$player = new Player(self_char_id());
		$clan   = ClanFactory::clanOfMember($player);
		$sure   = in('sure', '');

		if (!$this->playerIsLeader($player, $clan)) {
			throw new Exception('You may not disband a clan you are not a leader of.');
		}

		if ($sure === 'yes') {
			$clan->disband();

			$parts = [
				'action_message' => 'Your clan has been disbanded.',
				'title'          => 'Clan disbanded',
				'clans'          => clans_ranked(),
				'pageParts'      => [
					'reminder-no-clan',
					'list',
				],
			];
		} else {
			$parts = [
				'title'     => 'Confirm disbanding of your clan',
				'pageParts' => [
					'confirm-disband',
				],
			];
		}

		return $this->render($parts);
	}

	public function kick() {
		$kicker = new Player(self_char_id());
		$clan   = ClanFactory::clanOfMember($kicker);
		$kicked = in('kicked', '');
		$kicked_name = get_char_name($kicked);

		if (!$this->playerIsLeader($kicker, $clan)) {
			throw new Exception('You may not kick members from a clan you are not a leader of.');
		}

		$clan->kickMember($kicked, $kicker);

		return $this->render([
			'action_message' => "You have removed $kicked_name from your clan",
			'title'          => 'Manage your clan',
			'clan'           => $clan,
			'pageParts'      => [
				'manage',
				'info',
				'member-list',
			]
		]);
	}

	public function update() {
		$player = new Player(self_char_id());
		$clan   = ClanFactory::clanOfMember($player);

		if (!$this->playerIsLeader($player, $clan)) {
			throw new Exception('You may not update a clan you are not a leader of.');
		}

		$new_clan_avatar_url  = in('clan-avatar-url');
		$new_clan_description = in('clan-description');
		$new_clan_name        = trim(in('new_clan_name', ''));
		$error                = null;

		if ($new_clan_name != $clan->getName()) {
			if (is_valid_clan_name($new_clan_name)) {
				if (is_unique_clan_name($new_clan_name)) {
					// *** Rename the clan if it is valid.
					$new_clan_name = rename_clan($clan->getID(), $new_clan_name);
					$clan->setName($new_clan_name);
				} else {
					$error = 'That clan name is already in use!';
				}
			} elseif ($new_clan_name) {
				$error = 'Sorry, too many special symbols in your clan name.';
			}
		}

		// Saving incoming changes to clan leader edits.
		if (clan_avatar_is_valid($new_clan_avatar_url)) {
			save_clan_avatar_url($new_clan_avatar_url, $clan->getID());
			$clan->setAvatarUrl($new_clan_avatar_url);
		} else {
			$error = 'That avatar url is not valid.';
		}

		// Truncate at 500 chars if necessary.
		$truncated_clan_desc = substr((string)$new_clan_description, 0, 500);
		if ($truncated_clan_desc != (string) $new_clan_description) {
			$new_clan_description = $truncated_clan_desc;
		}

		if ($new_clan_description) {
			save_clan_description($new_clan_description, $clan->getID());
			$clan->setDescription($new_clan_description);
		}

		///TODO accumulate error messages
		return $this->render([
			'action_message' => 'Your clan has been updated.',
			'title'          => 'Edit your clan',
			'clan'           => $clan,
			'error'          => $error,
			'pageParts'      => [
				'edit',
				'info',
				'member-list',
			],
		]);
	}

	public function edit() {
		$player = new Player(self_char_id());
		$clan   = ClanFactory::clanOfMember($player);

		return $this->render([
			'clan'      => $clan,
			'title'     => 'Edit your clan',
			'pageParts' => [
				'edit',
				'info',
			],
		]);
	}

	public function message() {
		$player = new Player(self_char_id());

		if ($player->id()) {
			$myClan = ClanFactory::clanOfMember($player);

			if ($myClan) {
				$message = in('message', null, null); // Don't filter messages

				message_to_clan($message);

				$myClan = ClanFactory::clanOfMember($player);

				$parts = [
					'clan'           => $myClan,
					'title'          => 'Your clan',
					'action_message' => 'Message sent to your clan.',
					'pageParts'      => [
						'info',
						'member-list',
					],
				];

				if ($this->playerIsLeader($player, $myClan)) {
					array_unshift($parts['pageParts'], 'manage');
				} else {
					array_unshift($parts['pageParts'], 'non-leader-panel');
				}

				return $this->render($parts);
			} else {
				return $this->listClans();
			}
		} else {
			return $this->listClans();
		}
	}

	public function listClans() {
		$parts = [
			'title'     => 'Clan List',
			'clans'     => clans_ranked(),
			'pageParts' => ['list'],
		];

		$player = new Player(self_char_id());

		if ($player->id()) {
			$clan = ClanFactory::clanOfMember($player);

			if ($clan) {
				array_unshift($parts['pageParts'], 'reminder-member');
			} else {
				array_unshift($parts['pageParts'], 'reminder-no-clan');
			}
		}

		return $this->render($parts);
	}

	/**
	 * Review a requet to join a clan
	 *
	 * @param joiner int the id of the player object to accept into the clan
	 * @return Array
	 */
	public function review() {
		$ninja = new Player(self_char_id());
		$clan  = ClanFactory::clanOfMember($ninja->id());

		$joiner = new Player(in('joiner'));
		$confirmation = (int) in('confirmation');

		$parts = [
			'title' => 'Accept a New Clan Member',
		];

		if ($clan && $this->playerIsLeader($ninja, $clan)) {
			if ($joiner->id()) {
				$parts['pageParts'] = [
					'reminder-join-request',
					'form-confirm-join',
				];

				$parts['joiner'] = $joiner;
				$parts['confirmation'] = $confirmation;
			} else {
				$parts['error'] = 'No such ninja to bring into the clan.';
			}
		} else {
			$parts['error'] = 'You are not the leader of your clan.';
		}

		return $this->render($parts);
	}

	/**
	 * Accept a player as a new member of a clan
	 *
	 * @param joiner int the id of the player object to accept into the clan
	 * @param confirmation int A nonce that prevents clan leaders from adding unwilling members
	 * @return Array
	 *
	 * @par Preconditions:
	 * Active player must be leader of a clan
	 */
	public function accept() {
		$ninja = new Player(self_char_id());
		$clan  = ClanFactory::clanOfMember($ninja->id());

		$joiner = new Player(in('joiner'));
		$confirmation = (int) in('confirmation');

		$parts = [
			'title' => 'Accept a New Clan Member',
		];

		if ($clan && $this->playerIsLeader($ninja, $clan)) {
			if ($joiner->id()) {
				$parts['pageParts'] = [
					'reminder-join-request',
				];

				$parts['joiner'] = $joiner;

				// Allow joining as long as the verification number is correct.
				if ($confirmation === $joiner->getVerificationNumber()) {
					$result = $clan->addMember($joiner, $ninja);

					if ($result === true) {
						$parts['pageParts'][] = 'result-join';
					} else {
						$parts['error'] = (is_string($result) ? $result : 'Unable to add member, please try again later.');
					}
				} else {
					$parts['error'] = 'That request was old or invalid, please try inviting that ninja again.';
				}
			} else {
				$parts['error'] = 'No such ninja to bring into the clan.';
			}
		} else {
			$parts['error'] = 'You are not the leader of your clan.';
		}

		return $this->render($parts);
	}

	private function render($p_parts) {
		if (!isset($p_parts['pageParts'])) {
			$p_parts['pageParts'] = [];
		}

		if (!isset($p_parts['error'])) {
			$p_parts['error'] = null;
		}

		if (!isset($p_parts['action_message'])) {
			$p_parts['action_message'] = null;
		}

		$p_parts['player'] = new Player(self_char_id());
		$p_parts['myClan'] = ($p_parts['player'] ? ClanFactory::clanOfMember($p_parts['player']) : null);

		$p_parts['clan_creator_min_level'] = ClanController::CLAN_CREATOR_MIN_LEVEL;

		return [
			'template' => 'clan.tpl',
			'title'    => $p_parts['title'],
			'parts'    => $p_parts,
			'options'  => [
				'body_classes' => 'clan',
				'quickstat' => false,
			],
		];
	}

	private function playerIsLeader($p_objPlayer, $p_objClan) {
		$records = get_clan_leaders($p_objClan->id(), true);

		foreach ($records AS $record) {
			if ($record['player_id'] == $p_objPlayer->id()) {
				return true;
			}
		}

		return false;
	}
}
