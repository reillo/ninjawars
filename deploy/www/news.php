<?php
$private = false;
$alive   = false;

if ($error = init($private, $alive)) {
	display_error($error);
} else {

	$view = 'news.tpl';
	$parts = array(
		'target' => 'news.php',
		'field_size' => '40',
	);

	// Route the request
	if (in('new') && self_char_id()) {
		// Display submit form
		$view = 'news-create.tpl';
	} elseif (in('news_submit')) {
		// Handle POST
		$news_title = in('news_title');
		$news_content = in('news_content');
		$tag = in('tag');

		// Create new post
		if ( ! empty($news_content)) {
			try {
				// News Model
				$news = new model\News();
				$me = model\Base::query('Players')->findPK(self_char_id());
				$news->createPost($news_title, $news_content, $me->getAccountss()->getFirst()->getAccountId(), $tag);
				$parts['new_successful_submit'] = true;
			} catch (InvalidArgumentException $e) {
				$parts['new_successful_submit'] = false;
			}
		}
	}

	// Fetch the news
	try {
		$news = new model\News();

		if (in('tag_query')) {
			// Search for specific tag
			$all_news = $news->findByTag(in('tag_query'));
			$parts['search_title'] = 'Result for #'.htmlentities(in('tag_query'));
		} else {
			$all_news = $news->all();
		}
	} catch (InvalidArgumentException $e) {
		$all_news = array();
	}
	$parts['all_news'] = $all_news;

	$template = prep_page(
		  $view	
		, 'News Board'
		, $parts
		, array ()
	);

	function to_tags($str_tags) {
		$tags = array();

		if (strpos($str_tags, ',') !== false) {
			$tags = explode(',', $str_tags);
		} elseif ( ! empty($str_tags)) {
			$tags = array($str_tags);
		}

		if (empty($tags)) {
			return '-';
		} else {
			$str_tags = '';
			foreach ($tags as $tag) {
				// Build tag anchors
				$tag = trim($tag);
				$str_tags .= '<a href="news.php?tag_query='.htmlentities(url($tag)).'" target="main">#'.htmlentities($tag).'</a> ';
			}

			return $str_tags;
		}
	}

	function to_playerid($account) {
		if ($account instanceof \model\orm\Accounts) {
			return $account->getPlayerss()->getFirst()->getPlayerId();
		}
	}

	function to_playername($account) {
		if ($account instanceof \model\orm\Accounts) {
			return $account->getPlayerss()->getFirst()->getUname();
		}
	}

	$template->registerPlugin("function","to_playerid", "to_playerid");
	$template->registerPlugin("function","to_playername", "to_playername");
	$template->registerPlugin("function","to_tags", "to_tags");

	$template->fullDisplay();
}