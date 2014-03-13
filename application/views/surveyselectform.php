<?php
/**
 * @var $survey SurveyObj
 */
?>

<div class="container">
	<div class="row">
		<div class="col-sm-offset-2 col-sm-8" id="items-div">
			<div class="itembox-div" data-toggle="buttons-radio">
				<ul>
					<?php
					foreach ($survey->items as $i => $item)
					{
						// btn state define page loaded start view
						$add_class = ($is_voted ? ' disabled' : '');
						$add_class .= (isset($select) && $i == $select) ? ' btn-info' . ($is_voted ? '' : ' active' ) : '';
						?>
						<li><button type="button" id="item<?= $i ?>" name="<?= $i ?>" class="btn btn-item btn-lg btn-block btn-default<?= $add_class ?>"><?= $item ?></button></li>
					<?php } ?>
				</ul>
			</div>
		</div>
	</div>

	<div class="row">
		<?php if (!$is_voted)
		{ ?>
			<form action="<?= base_url("survey/regist/{$survey->id}") ?>" method="POST">
				<!--div class="col-sm-offset-2 col-sm-6" id="text-div">
					<textarea name="vote-text" id="vote-textarea" rows="3"></textarea>
				</div-->
				<div class="col-sm-offset-2 col-sm-8" id="submit-div">
					<input type="hidden" id="vote-value" name="vote-value" />
					<input type="hidden" name="token" value="<?= $token ?>" />
					<button type="submit" id="submit-main" class="btn btn-primary btn-lg btn-block btn-default disabled">投票</button>
				</div>
			</form>
			<?php
		} else
		{
			if ($is_voted)
			{
				$share_uri = base_url($survey->id);
				$share_text = totext_share($survey->items[$select], $survey->title, $share_uri);
			}
			?>
			<div class="col-sm-offset-2 col-sm-8" id="voteend-div">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title"><?= totext_voted($survey->items[$select]) ?></h3>
					</div>
					<div class="panel-body">
						<div class="input-group">
							<span class="input-group-addon"><i class="glyphicon glyphicon-file"></i>コピペ用</span>
							<input id="share-text" type="text" class="form-control" value="<?= $share_text ?>">
							<span class="input-group-btn">
								<!--button id="copy-btn" class="btn btn-default disabled" data-toggle="tooltip" data-placement="top" title="クリップボードにコピー">
									<i class="glyphicon glyphicon-file"></i>
								</button-->
							</span>
						</div>
						<div class="sharebtns-div">
							<p>
	<?php sharebtn_twitter($share_text, $share_uri) ?>
							</p>
						</div>
					</div>
					<div class="panel-footer">
					</div>
				</div>
			</div>
<?php } ?>
		<iframe name="hide-frame" class="no-display"></iframe>
	</div>
</div>