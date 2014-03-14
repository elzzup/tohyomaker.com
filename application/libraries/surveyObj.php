<?php

/**
 * anable vote working
 * @see $state
 */
define('SURVEY_STATE_PROGRESS', '0');
/**
 * vote is finish, saveing vote record
 * @see $state
 */
define('SURVEY_STATE_RESULT', '1');
/**
 * saving only result
 * @see $state
 */
define('SURVEY_STATE_END', '2');

/**
 * timestamp string format
 * @see $state
 */
define('DATE_FORMAT', 'Y年m月d日 H:i');

class SurveyObj
{

	public $id;
	public $title;
	public $description;
	public $timestamp;
	public $state;
	public $num_item;
	public $is_anonymous;
	public $target;

	/** @var UserObj */
	public $owner;
	public $items;
	private $items_sorted;
	public $tags;
	public $results;

	function __construct($data = NULL, array $items = NULL, array $tags = NULL, UserObj $owner = NULL, $results = NULL)
	{
		if (!empty($data))
		{
			$this->set($data, $items, $tags, $owner, $results);
		}
	}

	function set(stdClass $data, array $items = NULL, array $tags = NULL, UserObj $owner = NULL, $results = NULL)
	{
		$this->id = $data->id_survey;
		$this->title = $data->title;
		$this->target = (empty($data->target) ? '' : $data->target);
		$this->description = (empty($data->description) ? '' : $data->description);
		$this->num_item = $data->num_item;
		$this->timestamp = $data->timestamp;
		$this->state = $data->state;
		$this->is_anonymous = empty($data->is_anonymous);


		if (isset($items))
		{
			$this->set_items($items);
		}
		if (isset($tags))
		{
			$this->set_tags($tags);
		}
		if (isset($owner))
		{
			$this->owner = $owner;
		}
		// after set items
		if (isset($results))
		{
			$this->set_results($results);
		}

		$this->get_time_remain();
	}

	public function set_items(array $data)
	{
		$this->items = array();
		$this->items = $this->create_items($data);
	}

	public function create_items($data)
	{
		$items = array();
		foreach ($data as $datum)
		{
			$item = new ItemObj($datum);
			$items[$item->index] = $item;
		}
		ksort($items);
		return $items;
	}

	public function set_tags(array $data)
	{
		$this->tags = array();
		foreach ($data as $datum)
		{
			$this->tags[] = $datum->value;
		}
	}

	public function set_results(array $data)
	{
		if (($results = $this->cureate_results($data)))
		{
			$this->results = $results;
		}
	}

	public function create_results($data)
	{
		if (!isset($this->items))
		{
			return FALSE;
		}
		$results = array();
		foreach ($data as $datum)
		{
			$results[] = $this->_create_result($datum);
		}
	}

	private function _create_result($data)
	{
		$values = explode(',', $data->result);
		$items = $this->_create_map_item($values);
		return new ResultObj($data, $items);
	}

	private function _create_map_item($values)
	{
		$items = $this->items;
		foreach ($values as $i => $value)
		{
			$items[$i]->value = $value;
		}
		return $items;
	}

	public function get_total()
	{
		$sum = 0;
		/** @var $item ItemObj */
		foreach ($this->items as $item)
		{
			$sum += $item->num;
		}
		return $sum;
	}

	public function get_text_items($glue = ' / ')
	{
		return implode($glue, $this->items);
	}

	public function get_state_update()
	{
		if ($this->state == SURVEY_STATE_END)
		{
			return FALSE;
		}
		$remain = $this->get_time_remain();
		if ($remain <= 0)
		{
			return SURVEY_STATE_END;
		}
		if ($remain <= 345600)
		{
			return SURVEY_STATE_RESULT;
		}
		return FALSE;
	}

	public function get_time_progress_par()
	{
		$progress_time = $this->get_time_progress();
		$day3_time = strtotime('+3 day', 0);
		$v = $progress_time * 100 / $day3_time;
		return round($v);
	}

	public function get_time_progress()
	{
		$start_time = strtotime($this->timestamp);
		$now = time();
		return $now - $start_time;
	}

	public function get_time_remain_str()
	{
		$remain = $this->get_time_remain() - strtotime('+4 day', 0);
		if ($remain < 3600)
		{
			return floor($remain / 60) . '分';
		} elseif ($remain < 86400)
		{
			return floor($remain / 3600) . '時間';
		}
		return floor($remain / 86400) . '日';
	}

	public function get_time_remain()
	{
		// TODO: create another type case 
		$start_time = strtotime($this->timestamp);
		$end_time = strtotime('+1 week', $start_time);
		$now = time();
		return $end_time - $now;
	}

	public function get_time()
	{
		return date(DATE_FORMAT, strtotime($this->timestamp));
	}

	public function get_sorted()
	{
		if (!isset($this->items_sorted))
		{
			$this->items_sorted = $this->create_sorted_items($this->items);
		}
		return $this->items_sorted;
	}

	public function create_sorted_items($items)
	{

		//sort to base ItemObj's field num
		function cmp(ItemObj $a, ItemObj $b)
		{
			if ($a->num == $b->num)
			{
				return 0;
			}
			return ($a->num < $b->num) ? 1 : -1;
		}

		usort($items, 'cmp');
		$this->urveyObj->_rank_item($items);
		return $items;
	}

	private function _rank_item(&$items_sorted)
	{
		if (!isset($items_sorted))
		{
			return false;
		}
		$pre_n = -1;
		$rank = 0;
		for ($i = 0; $i < count($items_sorted); $i++)
		{
			$n = $items_sorted[$i]->num;
			if ($pre_n != $n)
			{
				$rank = $i + 1;
			}
			$items_sorted[$i]->set_rank($rank);
			$pre_n = $n;
		}
	}

}
