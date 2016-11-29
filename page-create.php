<?
	if (@$_POST['createstream']) {
		$success=no_displayed_error_result($createtxid, multichain('createfrom',
			$_POST['from'], 'stream', $_POST['name'], true));
				
		if ($success)
			output_success_text('Stream successfully created in transaction '.$createtxid);
	}
	
	$labels=multichain_labels();

	if (no_displayed_error_result($getaddresses, multichain('getaddresses', true))) {
		foreach ($getaddresses as $index => $address)
			if (!$address['ismine'])
				unset($getaddresses[$index]);
				
		if (no_displayed_error_result($listpermissions,
			multichain('listpermissions', 'create', implode(',', array_get_column($getaddresses, 'address')))
		))
			$createaddresses=array_unique(array_get_column($listpermissions, 'address'));
	}
	
	no_displayed_error_result($liststreams, multichain('liststreams', '*', true));

?>

			<div class="row">

				<div class="col-sm-4">
					<h3>Streams</h3>
			
<?
	foreach ($liststreams as $stream) {
?>
						<table class="table table-bordered table-condensed table-break-words <?=($success && ($stream['name']==@$_POST['name'])) ? 'bg-success' : 'table-striped'?>">
							<tr>
								<th style="width:30%;">Name</th>
								<td><?=html($stream['name'])?></td>
							</tr>
							<tr>
								<th>Opened by</td>
								<td class="td-break-words small"><?=format_address_html($stream['creators'][0], false, $labels)?></td>
							</tr>
							<tr>
								<th>Items</th>
								<td><?
									if ($stream['subscribed']) { 

								?><?=html($stream['items'])?><?

									} else {

								?>not subscribed<?

									}
								?></td>
							</tr>
						</table>
<?
	}
?>
				</div>
				
				<div class="col-sm-8">
					<h3>Create Stream</h3>
					
					<form class="form-horizontal" method="post" action="./?chain=<?=html($_GET['chain'])?>&page=<?=html($_GET['page'])?>">
						<div class="form-group">
							<label for="from" class="col-sm-2 control-label">From address:</label>
							<div class="col-sm-9">
							<select class="form-control col-sm-6" name="from" id="from">
<?
	foreach ($createaddresses as $address) {
?>
								<option value="<?=html($address)?>"><?=format_address_html($address, true, $labels)?></option>
<?
	}
?>						
							</select>
							</div>
						</div>
						<div class="form-group">
							<label for="name" class="col-sm-2 control-label">Stream name:</label>
							<div class="col-sm-9">
								<input class="form-control" name="name" id="name" placeholder="stream1">
								<span id="helpBlock" class="help-block">In this demo, the stream will be open, so anyone can write to it.</span>
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-offset-2 col-sm-9">
								<input class="btn btn-default" type="submit" name="createstream" value="Create Stream">
							</div>
						</div>
					</form>

				</div>
			</div>