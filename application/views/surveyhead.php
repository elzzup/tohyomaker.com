<?php
/* @var $survey Surveyobj */
/* @var $owner Userobj */
/* @var $type int */
/* @var $f_num int */
?>

<div class="container">
	<div class="row">
		<div class="col-sm-offset-2 col-sm-8" id="survey-head-div">

			<div class="row" id="survey-pager-div">
				<div class="col-xs-12 col-xs-offset-0">
					<div class="btn-group btn-group-justified">
						<a <?= attr_href(PATH_VOTE, $survey->id) ?> class="btn btn-success<?= (($type === PAGETYPE_VOTE) ? ' disabled hidden-xs' : '') ?>">
							<i class="<?= ICON_VOTE ?>"></i>
							<span>投票ページ</span>
						</a>
						<a <?= attr_href(PATH_VIEW, $survey->id) ?> class="btn btn-success<?= (($type === PAGETYPE_VIEW) ? ' disabled hidden-xs' : '') ?>">
							<i class="<?= ICON_RESULT ?>"></i>
							<span>結果ページ</span>
						</a>
						<!--TODO: friend's vote page-->
						<a <?= attr_href(PATH_FRIEND, $survey->id) ?> class="btn btn-success<?= (($type === PAGETYPE_FRIEND) ? ' disabled hidden-xs' : '') ?>">
							<i class="<?= ICON_FRIEND ?>"></i>
							<span>フレンド<span class="badge"><?= $f_num ?: '' ?></span></span>
						</a>
					</div>
				</div>
			</div>

			<div class="well">
				<div class="row">
					<div class="col-xs-8 survey-cont timestamp">
						<?= tag_icon(ICON_TIME, TRUE) ?>
						<?= $survey->get_time() ?>
					</div>
					<div class="col-xs-4 survey-cont owner-name">
						<?php
						if (!$survey->is_anonymous)
						{
							?>
							<?= tag_icon(ICON_USER, TRUE) ?>
							<a class="hidden-xs" <?= attr_href(PATH_USER, $survey->owner->id) ?><?= attr_tooltip("作者: {$survey->owner->screen_name}") ?> >
								@<?= $survey->owner->screen_name ?>
							</a>
							<a class="visible-xs" style="display: inline !important" <?= attr_href(PATH_USER, $survey->owner->id) ?><?= attr_tooltip("作者: {$survey->owner->screen_name}") ?> >
								作者
							</a>
							<?php
						} else
						{
							?>
							<div class="hidden-xs">
								<?= tag_icon(ICON_USER) ?><?= NO_PARAM_STR ?>
								<a href="#" <?= attr_tooltip("作者非公開") ?>>
								</a>
							</div>
							<?php
						}
						?>
					</div>
				</div>

				<div class="row">
					<div class="col-xs-8 survey-cont" >
						<?php
						if ($survey->state != SURVEY_STATE_END)
						{
							?>
							<div class="col-xs-12 col-sm-3 no-container" <?= attr_tooltip('集計まで残り時間') ?>>
								<?= tag_icon(ICON_FLAG, TRUE) ?>
								<span class="visible-inline-xs">集計まで</span><?= $survey->get_time_remain_str() ?>
							</div>
							<?php
							$stylelib = explode(',', 'success,end,end');
							$pbstyle = $stylelib[$survey->state];
							?>
							<div class="hidden-xs col-sm-9">
								<div class="progress">
									<div class="progress-bar progress-bar-<?= $pbstyle ?>" style="width: <?= $survey->get_time_progress_par() ?>%;"></div>
								</div>
							</div>
							<?php
						} else
						{
							// TODO: result type 
							?>
							<div class="col-xs-12 col-sm-3 no-container" <?= attr_tooltip('集計まで残り時間') ?>>
								<?= tag_icon(ICON_FLAG, TRUE) ?>
								<a <?= attr_href(PATH_VIEW . '/' . $survey->id) ?>>集計結果あり</a>
							</div>
							<?php
						}
						?>
					</div>
					<div class="col-xs-4 hidden-xs">
						<?php
						$share_uri = fix_url(base_url(PATH_VOTE . $survey->id));
						$share_text = totext_share_survey($survey);
						echo sharebtn_twitter($share_text, $share_uri);
						?>
					</div>
					<div class="col-xs-4 visible-xs">
						<?= sharebtn_twitter($share_text, $share_uri, 'ツイート', FALSE); ?>
					</div>
				</div>

				<div class="row">
					<div class="col-xs-12 survey-cont tagbox">
						<div class="btn-group-tags">
							<?= tag_icon(ICON_TAG, TRUE) ?>
							<?php
							foreach ($survey->tags as $tag)
							{
								?>
								<a <?= attr_href(PATH_TAG, $tag) ?> class=""><?= $tag ?></a>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>

			<div class="row description">
				<div class="col-sm-12">
					<div class="well">
						<?php
						if (isset($survey->description))
						{
							?>
							<p><span><?= $survey->description ?></span></p>
						<?php }
						if ($survey->is_img)
						{
							?>
							<a href="<?= $survey->get_full_imgurl(TRUE)?>" target="_blank"><img src="<?= $survey->get_full_imgurl() ?>" alt="" /></a>
<?php } ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>