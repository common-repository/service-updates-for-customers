<?php

if ( isset( $_POST['status-add'] ) ) {

	if ( isset( $_POST['status-name'] ) && $_POST['status-name'] != '' && !in_array( $_POST['status-name'], $this->statuses ) ) {
		if( isset ( $_POST['status-id'] ) && $_POST['status-id'] != '' ) { 
			$this->statuses[$_POST['status-id']] = array(
					'name' => stripslashes( $_POST['status-name'] ),
					'desc' =>  stripslashes( $_POST['status-desc'] ) // Wordpress seems to addslashes the post data
				);
		} else {
			$this->statuses[] = array( 
					'name' => stripslashes( $_POST['status-name'] ), 
					'desc' =>  stripslashes( $_POST['status-desc'] ) // Wordpress seems to addslashes the post data
				);
		}
	}
	$this->save_options();
} elseif ( isset( $_POST['doaction'] ) ) {
	if ( $_POST['action'] == 'delete' && isset( $_POST['delete_status'] ) ) {
		foreach ( $_POST['delete_status'] as $delete) {
			unset($this->statuses[$delete]);
		}	
	} 
	$this->save_options();
}
?>
<div class="wrap">
	<div id="icon-options-general" class="icon32"></div>
	<h2>Service Updates For Customers plugin Settings</h2>
	<div id="col-right">
		<div class="col-wrap">
			<form id="delstatus" method="post">
				<div class="tablenav top">
					<div class="alignleft actions">
						<select name="action">
							<option selected="selected" value="-1">Bulk Actions</option>
							<option value="delete">Delete</option>
						</select>
						<input id="doaction" class="button-secondary action" type="submit" value="Apply" name="doaction" />
					</div>
				</div>
				<table class="wp-list-table widefat fixed">
					<thead>
						<tr>
							<th id="cb" class="column-cb check-column"><input type="checkbox"></th>
							<th>Name</th>
							<th>Description</th>
							<th width="30">&nbsp;</th>
						</tr>
					</thead>	
					<tfoot>
						<tr>
							<th id="cb" class="column-cb check-column"><input type="checkbox"></th>
							<th>Name</th>
							<th>Description</th>
							<th>&nbsp;</th>
						</tr>
					</tfoot>
					<tbody>
						<?php
						
						foreach ($this->statuses as $key => $status) {
							?>
							<tr>
								<th class="check-column" scope="row">
									<input type="checkbox" value="<?php echo $key ?>" name="delete_status[]" />
								</th>
								<td><?php echo $status['name'] ?></td>
								<td><?php echo $status['desc'] ?></td>
								<td><a href="<?php echo $_SERVER["REQUEST_URI"] ?>&status-id=<?php echo $key ?>">edit</a></td>
							</tr>
							<?php
						}
						?>
					</tbody>
				</table>
			</form>
		</div>
	</div>
	<div id="col-left">
		<div class="col-wrap">
			<h3><?php echo (isset( $_GET['status-id'] )) ? 'Edit' : 'Add new' ?> status</h3>
			<form id="addstatus" method="post" action="<?php echo $_SERVER["SCRIPT_NAME"].'?post_type='.$this->post_type.'&page='.$this->post_type ?>">
				<div class="form-field form-required">
					<label for="status-name">Name </label>
					<input id="status-name" type="text" aria-required="true" size="40" value="<?php echo (isset( $_GET['status-id'] )) ? $this->statuses[$_GET['status-id']]['name'] : '' ?>" name="status-name">
					<p>A short name for this status e.g. Issue, Resloved, Failure etc </p>
				</div>
				<div class="form-field form-required">
					<label for="status-desc">Description</label>
					<textarea id="status-desc" cols="40" rows="5" name="status-desc"><?php echo (isset( $_GET['status-id'] )) ? $this->statuses[$_GET['status-id']]['desc'] : '' ?></textarea>
					<p> Description, If you want to show it to End user , we ll use just a status symbol </p>
				</div>
				<p class="submit"><input type="submit" class="button" id="status-add" name="status-add" value="<?php echo (isset( $_GET['status-id'] )) ? 'Save' : 'Add new' ?> Status" /></p>
				<input type="hidden" id="status-id" name="status-id" value="<?php echo (isset( $_GET['status-id'] )) ? $_GET['status-id'] : '' ?>" />
			</form>
		</div>
	</div>
</div>
