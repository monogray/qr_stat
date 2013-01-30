$(document).ready(function() {
	// Hide pack properties area
	$("#package_action_content").hide();

	// Toogle Show/Hide pack properties area
	$("#package_action_header").click(function() {
		$("#package_action_content").toggle("slow", function() {
			// Animation complete
		});
	});
	
	// Package selection function
	$("#package_select_bt").click(function() {
		var _val = $('input[name$="package_select"]').val();
		var _val_arr = _val.split(",");
		for (var i = 0; i < _val_arr.length; i++) {
			var _val_arr_inner = _val_arr[i].split("-");
			if(_val_arr_inner.length == 2){
				for (var j = parseInt(_val_arr_inner[0]); j <=  parseInt(_val_arr_inner[1]); j++) {
					try{
						$('input[name$="guests_pack[]"]:eq('+(j-1).toString()+')').attr("checked", true);
					} catch(e) {
					} finally {
					}
				}
			}else{
				var _val = parseInt(_val_arr[i]) - 1;
				$('input[name$="guests_pack[]"]:eq('+_val.toString()+')').attr("checked", true);
			}
		}
	
	});
	
	$("#package_add_bt").click(function() {
		if (confirm("Add?")) {
			$("#guests_pack_processing").attr("action", "index.php?chapter=admin&action=guests_pack_add");
			$("#guests_pack_processing").submit();
		}
	});
	
	// Package normalize qr
	$("#guests_pack_normalize_qr").click(function() {
		if (confirm("Normalize?")) {
			$("#guests_pack_processing").attr("action", "index.php?chapter=admin&action=guests_pack_normalize_qr");
			$("#guests_pack_processing").submit();
		}
	});
	
	// Package drop function
	$("#guests_pack_drop").click(function() {
		if (confirm("Delete?")) {
			$("#guests_pack_processing").attr("action", "index.php?chapter=admin&action=guests_pack_drop");
			$("#guests_pack_processing").submit();
		}
	});

	// Package moving to new group
	$("#guests_pack_move_to_grop").click(function() {
		if (confirm("Move to groupe?")) {
			$("#guests_pack_processing").attr("action", "index.php?chapter=admin&action=guests_pack_move_to_grop");
			$("#guests_pack_processing").submit();
		}
	});

});		