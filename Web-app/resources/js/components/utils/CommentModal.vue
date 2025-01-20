<template>

	<div>

		<button type="button" class="btn btn-sm me-2 btn-success" data-bs-toggle="modal" :data-bs-target="'#commentModal' + itemID">Edit Comment</button>

		<div class="modal fade" :id="'commentModal' + itemID" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">			
			
			<div class="modal-dialog">
				
				<div class="modal-content">
					
					<div class="modal-header">
						
						<h5 class="modal-title" id="exampleModalLabel">Edit Comment</h5>
						
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					
					</div>

					<div class="alert alert-success d-flex align-items-center" role="alert" v-if="success">
						Comment Edited Successfully!
					</div>

					<div class="modal-body">

							<div class="alert alert-danger d-flex align-items-center" role="alert" v-if="errors !== null" v-for="error in errors">
								{{error}} 
							</div>

							<div>
							
								<form>

									<div class="row mb-3">
																					
										<div class="input-group mb-3">
  										
										  <span class="input-group-text" id="basic-addon1">Comment</span>
						  
										  <textarea rows="4" cols="50" class="form-control" placeholder="Comment" v-model="commentUpdate" aria-describedby="basic-addon1"></textarea>
									
									</div>
									
									</div>

								</form>

							</div>

						</div>

						<div class="modal-footer">
			
							<button type="button" class="btn btn-secondary" :id="'closeBtn' + itemID" data-bs-dismiss="modal">Close</button>
							<button type="button" class="btn btn-success" @click.prevent="editComment()">Edit</button>

						</div>

					</div>
		
				</div>

			</div>

		</div>

</template>

<script>

export default {

	props : {
		itemID : {
			type: Number
		},
		comment: {
			type: String
		}
	},
	
	data() {
		return {
		commentUpdate: this.comment,
		errors : null,
		success: false
		}
	},

	mounted : function(){ },

	watch: {
		comment(newValue) {
			this.commentUpdate = newValue;
		}
	},

	methods: {
		editComment: function() {
			var commentData = new FormData();
			commentData.append('comment', this.commentUpdate || " ");
			commentData.append('user_id', this.$root.user.id);
			commentData.append('post_id', this.itemID);

			this.axios.post(`/api/edit-comment/${this.itemID}`, commentData).then(response => {
				this.success = true;
				this.errors = [];
				this.$emit('callback');
				this.updatedComment = "";

			}).catch(e => {
				this.errors = e.response.data;
			});
		},

	},
}


</script>